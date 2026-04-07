<?php
class LogHelper
{
    private static $logFile = 'logs/activity.log';

    public static function log($action, $details = '')
    {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';
        $logEntry = "$timestamp | IP: $ip | User: $user | Action: $action | Details: $details" . PHP_EOL;

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
?>