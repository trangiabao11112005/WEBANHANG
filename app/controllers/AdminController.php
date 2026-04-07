<?php
require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/LogHelper.php';

class AdminController
{
    private $db;

    public function __construct()
    {
        SessionHelper::start();

        if (!SessionHelper::isAdmin()) {
            die('Bạn không có quyền truy cập');
        }

        $this->db = (new Database())->getConnection();
    }

    public function index()
    {
        $stats = $this->getStats();
        $logs = $this->getLogs();
        require_once 'app/helpers/LogHelper.php';
        LogHelper::log('VIEW_DASHBOARD', 'Admin xem bảng điều khển');
        include 'app/views/admin/dashboard.php';
    }

    private function getStats()
    {
        return [
            'products' => $this->getCount('product'),
            'categories' => $this->getCount('category'),
            'orders' => $this->getCount('orders'),
            'users' => $this->getCount('account'),
            'admins' => $this->getCount('account', "role = 'admin'"),
        ];
    }

    private function getCount($table, $where = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM {$table}";
        if ($where !== '') {
            $sql .= " WHERE {$where}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] ?? 0;
    }

    private function getLogs()
    {
        $logFile = 'logs/activity.log';
        if (file_exists($logFile)) {
            $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            return array_reverse($logs); // Newest first
        }
        return [];
    }

    public function getLogsAjax()
    {
        header('Content-Type: application/json');
        $logs = $this->getLogs();
        echo json_encode($logs);
    }

    public function security()
    {
        $securityStats = $this->getSecurityStats();
        $accounts = $this->getAllAccounts();
        include 'app/views/admin/security.php';
    }

    private function getSecurityStats()
    {
        $attackLog = 'logs/attack.log';
        $stats = [
            'total_attacks' => 0,
            'attacks_by_type' => [],
            'attacks_by_ip' => [],
            'blocked_ips' => [],
            'blocked_accounts' => []
        ];

        if (file_exists($attackLog)) {
            $lines = file($attackLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $stats['total_attacks'] = count($lines);

            foreach ($lines as $line) {
                // Parse: "2026-04-06 04:31:42 | IP: ::1 | TYPE: SQLi | DATA: ' OR 1=1#"
                $parts = explode(' | ', $line);
                if (count($parts) >= 3) {
                    $ip = str_replace('IP: ', '', $parts[1]);
                    $type = str_replace('TYPE: ', '', $parts[2]);

                    if (!isset($stats['attacks_by_type'][$type])) {
                        $stats['attacks_by_type'][$type] = 0;
                    }
                    $stats['attacks_by_type'][$type]++;

                    if (!isset($stats['attacks_by_ip'][$ip])) {
                        $stats['attacks_by_ip'][$ip] = 0;
                    }
                    $stats['attacks_by_ip'][$ip]++;
                }
            }
        }

        // Load blocked IPs with timestamps
        $blockedIpFile = 'logs/blocked_ips.txt';
        if (file_exists($blockedIpFile)) {
            $lines = file($blockedIpFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode('|', $line);
                $stats['blocked_ips'][] = $parts[0];
            }
        }

        // Load blocked accounts with timestamps
        $blockedAccountFile = 'logs/blocked_accounts.txt';
        if (file_exists($blockedAccountFile)) {
            $lines = file($blockedAccountFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode('|', $line);
                $stats['blocked_accounts'][] = $parts[0];
            }
        }

        return $stats;
    }

    private function getAllAccounts()
    {
        $query = "SELECT username, role FROM account";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function blockIp()
    {
        if (!isset($_POST['ip'])) {
            echo json_encode(['success' => false, 'message' => 'IP không được cung cấp']);
            return;
        }

        $ip = trim($_POST['ip']);
        $blockedFile = 'logs/blocked_ips.txt';
        $timestamp = time();

        $lines = [];
        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $found = false;
        foreach ($lines as &$line) {
            $parts = explode('|', $line);
            if ($parts[0] === $ip) {
                $line = $ip . '|' . $timestamp;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $lines[] = $ip . '|' . $timestamp;
        }

        file_put_contents($blockedFile, implode("\n", $lines) . "\n");

        echo json_encode(['success' => true, 'message' => 'IP đã bị chặn']);
    }

    public function unblockIp()
    {
        if (!isset($_POST['ip'])) {
            echo json_encode(['success' => false, 'message' => 'IP không được cung cấp']);
            return;
        }

        $ip = trim($_POST['ip']);
        $blockedFile = 'logs/blocked_ips.txt';

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_filter($lines, function($line) use ($ip) {
                $parts = explode('|', $line);
                return $parts[0] !== $ip;
            });
            file_put_contents($blockedFile, implode("\n", $lines) . "\n");
        }

        echo json_encode(['success' => true, 'message' => 'IP đã bỏ chặn']);
    }

    public function blockAccount()
    {
        if (!isset($_POST['username'])) {
            echo json_encode(['success' => false, 'message' => 'Tên tài khoản không được cung cấp']);
            return;
        }

        $username = trim($_POST['username']);
        $blockedFile = 'logs/blocked_accounts.txt';
        $timestamp = time();

        $lines = [];
        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $found = false;
        foreach ($lines as &$line) {
            $parts = explode('|', $line);
            if ($parts[0] === $username) {
                $line = $username . '|' . $timestamp;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $lines[] = $username . '|' . $timestamp;
        }

        file_put_contents($blockedFile, implode("\n", $lines) . "\n");

        echo json_encode(['success' => true, 'message' => 'Tài khoản đã bị chặn']);
    }

    public function unblockAccount()
    {
        if (!isset($_POST['username'])) {
            echo json_encode(['success' => false, 'message' => 'Tên tài khoản không được cung cấp']);
            return;
        }

        $username = trim($_POST['username']);
        $blockedFile = 'logs/blocked_accounts.txt';

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_filter($lines, function($line) use ($username) {
                $parts = explode('|', $line);
                return $parts[0] !== $username;
            });
            file_put_contents($blockedFile, implode("\n", $lines) . "\n");
        }

        echo json_encode(['success' => true, 'message' => 'Tài khoản đã bỏ chặn']);
    }
}
