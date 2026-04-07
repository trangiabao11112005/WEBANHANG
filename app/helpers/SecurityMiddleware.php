<?php

class SecurityMiddleware
{

    public static function handle()
    {
        self::startSession();
        self::checkRateLimit();
        self::checkRequest();
        self::checkBruteForce();
        self::checkBlockedIp();
        self::checkBlockedAccount();
    }

    // ========================
    // Start session an toàn
    // ========================
    private static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            session_start();
            return;
        }

        // Nếu phiên đã tồn tại, không thay đổi cấu hình session.
    }

    // ========================
    // Kiểm tra toàn bộ request
    // ========================
    private static function checkRequest()
    {
        foreach ($_REQUEST as $key => $value) {
            if (is_array($value)) continue;

            if (self::detectSQLi($value)) {
                self::logAttack("SQLi", $value);
                self::block("SQL Injection detected!");
            }

            if (self::detectXSS($value)) {
                self::logAttack("XSS", $value);
                self::block("XSS detected!");
            }
        }
    }

    // ========================
    // Detect SQL Injection
    // ========================
    private static function detectSQLi($input)
    {
        $patterns = [
            "/(\bor\b|\band\b).*=.*/i",
            "/union.*select/i",
            "/select.*from/i",
            "/insert.*into/i",
            "/delete.*from/i",
            "/drop.*table/i",
            "/--/",
            "/;/"
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }

    // ========================
    // Detect XSS
    // ========================
    private static function detectXSS($input)
    {
        return preg_match("/<script.*?>.*?<\/script>/i", $input) ||
            preg_match("/on\w+=/i", $input) ||
            preg_match("/javascript:/i", $input);
    }

    // ========================
    // Chặn request
    // ========================
    private static function block($message)
    {
        http_response_code(403);
        die("🚫 Bị chặn bởi SecurityMiddleware: " . $message);
    }

    // ========================
    // Log tấn công
    // ========================
    private static function logAttack($type, $input)
    {
        $log = date("Y-m-d H:i:s") .
            " | IP: " . $_SERVER['REMOTE_ADDR'] .
            " | TYPE: $type | DATA: $input\n";

        file_put_contents(__DIR__ . "/../../logs/attack.log", $log, FILE_APPEND);

        // Auto-block if too many attacks
        self::checkAutoBlock($_SERVER['REMOTE_ADDR']);
    }

    // ========================
    // Auto-block IP if too many attacks
    // ========================
    private static function checkAutoBlock($ip)
    {
        $attackLog = __DIR__ . "/../../logs/attack.log";
        $maxAttacks = 10; // block after 10 attacks
        $timeWindow = 3600; // in last hour

        if (!file_exists($attackLog)) return;

        $lines = file($attackLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $recentAttacks = 0;
        $currentTime = time();

        foreach ($lines as $line) {
            if (strpos($line, "IP: $ip") !== false) {
                // Extract timestamp
                $parts = explode(' | ', $line);
                if (count($parts) > 0) {
                    $timestamp = strtotime($parts[0]);
                    if (($currentTime - $timestamp) < $timeWindow) {
                        $recentAttacks++;
                    }
                }
            }
        }

        if ($recentAttacks >= $maxAttacks) {
            self::blockIp($ip);
        }
    }

    // ========================
    // Block IP
    // ========================
    private static function blockIp($ip)
    {
        $blockedFile = __DIR__ . "/../../logs/blocked_ips.txt";
        $lines = [];

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $found = false;
        foreach ($lines as &$line) {
            $parts = explode('|', $line);
            if ($parts[0] === $ip) {
                $line = $ip . '|' . time();
                $found = true;
                break;
            }
        }

        if (!$found) {
            $lines[] = $ip . '|' . time();
        }

        file_put_contents($blockedFile, implode("\n", $lines) . "\n");
    }

    // ========================
    // Block Account
    // ========================
    private static function blockAccount($username)
    {
        $blockedFile = __DIR__ . "/../../logs/blocked_accounts.txt";
        $lines = [];

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $found = false;
        foreach ($lines as &$line) {
            $parts = explode('|', $line);
            if ($parts[0] === $username) {
                $line = $username . '|' . time();
                $found = true;
                break;
            }
        }

        if (!$found) {
            $lines[] = $username . '|' . time();
        }

        file_put_contents($blockedFile, implode("\n", $lines) . "\n");
    }

    // ========================
    // Chống brute force login
    // ========================
    public static function checkBruteForce()
    {
        if (!isset($_SESSION['attempt'])) {
            $_SESSION['attempt'] = 0;
        }

        if ($_SESSION['attempt'] > 5) {
            self::block("Quá nhiều nỗ lực đăng nhập!");
        }
    }

    public static function increaseAttempt()
    {
        $_SESSION['attempt']++;
    }

    public static function resetAttempt()
    {
        $_SESSION['attempt'] = 0;
    }

    // ========================
    // CSRF Token
    // ========================
    public static function generateCSRF()
    {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public static function verifyCSRF($token)
    {
        if (!isset($_SESSION['csrf']) || $token !== $_SESSION['csrf']) {
            self::block("CSRF attack detected!");
        }
    }

    // ========================
    // Check blocked IP
    // ========================
    private static function checkBlockedIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $blockedFile = __DIR__ . "/../../logs/blocked_ips.txt";
        $blockDuration = 24 * 3600; // 24 hours

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $updatedLines = [];

            foreach ($lines as $line) {
                $parts = explode('|', $line);
                $blockedIp = $parts[0];
                $timestamp = isset($parts[1]) ? (int)$parts[1] : 0;

                if ($blockedIp === $ip) {
                    if (time() < $timestamp + $blockDuration) {
                        self::block("IP is blocked due to security violations!");
                        return;
                    }
                    // Expired, don't add to updatedLines
                } else {
                    $updatedLines[] = $line;
                }
            }

            // Save updated list without expired blocks
            if (count($updatedLines) !== count($lines)) {
                file_put_contents($blockedFile, implode("\n", $updatedLines) . "\n");
            }
        }
    }

    // ========================
    // Check blocked account
    // ========================
    private static function checkBlockedAccount()
    {
        if (!isset($_SESSION['username'])) return;

        $username = $_SESSION['username'];
        $blockedFile = __DIR__ . "/../../logs/blocked_accounts.txt";
        $blockDuration = 24 * 3600; // 24 hours

        if (file_exists($blockedFile)) {
            $lines = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $updatedLines = [];

            foreach ($lines as $line) {
                $parts = explode('|', $line);
                $blockedUser = $parts[0];
                $timestamp = isset($parts[1]) ? (int)$parts[1] : 0;

                if ($blockedUser === $username) {
                    if (time() < $timestamp + $blockDuration) {
                        self::block("Account is blocked due to security violations!");
                        return;
                    }
                    // Expired, don't add to updatedLines
                } else {
                    $updatedLines[] = $line;
                }
            }

            // Save updated list without expired blocks
            if (count($updatedLines) !== count($lines)) {
                file_put_contents($blockedFile, implode("\n", $updatedLines) . "\n");
            }
        }
    }

    // ========================
    // Rate limiting
    // ========================
    private static function checkRateLimit()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $rateFile = __DIR__ . "/../../logs/rate_limit.json";

        $currentTime = time();
        $window = 60; // 1 minute
        $maxRequests = 100; // requests per minute

        $rateData = [];
        if (file_exists($rateFile)) {
            $rateData = json_decode(file_get_contents($rateFile), true) ?: [];
        }

        if (!isset($rateData[$ip])) {
            $rateData[$ip] = [];
        }

        // Remove old requests outside the window
        $rateData[$ip] = array_filter($rateData[$ip], function ($timestamp) use ($currentTime, $window) {
            return ($currentTime - $timestamp) < $window;
        });

        // Check if over limit
        if (count($rateData[$ip]) >= $maxRequests) {
            self::block("Rate limit exceeded! Too many requests.");
        }

        // Add current request
        $rateData[$ip][] = $currentTime;

        // Save
        file_put_contents($rateFile, json_encode($rateData));
    }
}
