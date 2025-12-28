<?php
// Check PHP errors and configuration
echo "=== PHP ERROR CHECK ===\n\n";

echo "Error Reporting: " . (ini_get('error_reporting') ? "ON" : "OFF") . "\n";
echo "Display Errors: " . (ini_get('display_errors') ? "ON" : "OFF") . "\n";
echo "Log Errors: " . (ini_get('log_errors') ? "ON" : "OFF") . "\n";

echo "\nPHP Version: " . phpversion() . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";

echo "\n=== LOADED EXTENSIONS ===\n";
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'json'];
foreach ($required_extensions as $ext) {
    $loaded = extension_loaded($ext) ? 'YES' : 'NO';
    echo "$ext: $loaded\n";
}

echo "\n=== ERROR LOG ===\n";
$log_file = ini_get('error_log');
if ($log_file && file_exists($log_file)) {
    echo "Log file: $log_file\n";
    echo "Recent errors (last 20 lines):\n";
    $lines = file($log_file);
    $recent = array_slice($lines, -20);
    foreach ($recent as $line) {
        echo $line;
    }
} else {
    echo "No error log found or not configured\n";
}

echo "\n=== SESSION ===\n";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "Session ID: " . session_id() . "\n";
echo "Session Data: " . json_encode($_SESSION) . "\n";
?>
