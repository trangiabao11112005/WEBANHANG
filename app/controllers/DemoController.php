<?php
require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/SecurityMiddleware.php';

/**
 * Demo Controller - For Security Testing and Educational Purposes
 * WARNING: Use only in TEST/DEMO environment, NOT in production!
 */
class DemoController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Main demo page
     */
    public function index()
    {
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        include 'app/views/demo/index.php';
    }

    /**
     * SQL Injection test page
     */
    public function sqli()
    {
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $result = null;
        $testPassed = null;
        $testMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
            $username = $_POST['username'];
            
            // Store the test data for demonstration
            $testMessage = "Input received: " . htmlspecialchars($username);
            
            // Try to execute a potentially vulnerable query
            try {
                if (!$securityEnabled) {
                    // VULNERABLE: Using string concatenation (for demo purposes only!)
                    $query = "SELECT * FROM account WHERE username = '" . $username . "'";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $testPassed = true;
                } else {
                    // SAFE: Using prepared statements
                    $query = "SELECT * FROM account WHERE username = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$username]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $testPassed = true;
                }
            } catch (Exception $e) {
                $testPassed = false;
                $testMessage .= " | Error: " . $e->getMessage();
            }
        }

        include 'app/views/demo/sqli.php';
    }

    /**
     * XSS test page
     */
    public function xss()
    {
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $displayText = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
            $message = $_POST['message'];
            
            if (!$securityEnabled) {
                // VULNERABLE: Direct output without escaping
                $displayText = $message;
            } else {
                // SAFE: Properly escaped output
                $displayText = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            }
        }

        include 'app/views/demo/xss.php';
    }

    /**
     * CSRF test page
     */
    public function csrf()
    {
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $csrf_token = SecurityMiddleware::generateCSRF();
        
        include 'app/views/demo/csrf.php';
    }

    /**
     * Brute Force test page
     */
    public function bruteforce()
    {
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $attempts = isset($_SESSION['attempt']) ? $_SESSION['attempt'] : 0;
        
        include 'app/views/demo/bruteforce.php';
    }

    /**
     * Process test attack
     */
    public function testAttack()
    {
        header('Content-Type: application/json');

        $type = $_POST['type'] ?? '';
        $payload = $_POST['payload'] ?? '';
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();

        $result = [
            'security_enabled' => $securityEnabled,
            'attack_type' => $type,
            'payload' => htmlspecialchars($payload),
            'message' => ''
        ];

        if ($securityEnabled) {
            $result['message'] = '🛡️ Attack was blocked by security system!';
            $result['blocked'] = true;
        } else {
            $result['message'] = '⚠️ No security protection - attack went through!';
            $result['blocked'] = false;
        }

        echo json_encode($result);
    }

    /**
     * Get security status
     */
    public function status()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'security_enabled' => SecurityMiddleware::isSecurityEnabled(),
            'timestamp' => date('Y-m-d H:i:s'),
            'description' => SecurityMiddleware::isSecurityEnabled() 
                ? 'Security system is ACTIVE. Attacks will be blocked.'
                : 'Security system is INACTIVE. Attacks will pass through for demonstration.'
        ]);
    }
}
