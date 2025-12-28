<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== QUICK DIAGNOSTICS ===\n\n";

// 1. Check PHP version
echo "1. PHP Version: " . phpversion() . "\n";

// 2. Check if PDO is available
echo "2. PDO Available: " . (extension_loaded('pdo') ? "YES" : "NO") . "\n";

// 3. Check if MySQL PDO driver is available
echo "3. MySQL PDO Driver: " . (extension_loaded('pdo_mysql') ? "YES" : "NO") . "\n";

// 4. Try to connect to database
echo "4. Database Connection Test:\n";
try {
    $conn = new PDO("mysql:host=localhost;dbname=testdb", "root", "");
    echo "   ✓ Connected to testdb\n";
    
    // 5. Check if tables exist
    echo "5. Database Tables:\n";
    $tables = ['users', 'tasks', 'habits', 'habit_tracking', 'achievements'];
    foreach ($tables as $table) {
        try {
            $stmt = $conn->prepare("SELECT 1 FROM $table LIMIT 1");
            $stmt->execute();
            echo "   ✓ $table exists\n";
        } catch (Exception $e) {
            echo "   ✗ $table missing\n";
        }
    }
} catch (PDOException $e) {
    echo "   ✗ Connection failed: " . $e->getMessage() . "\n";
}

// 6. Check session
echo "6. Session Status:\n";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "   Session ID: " . session_id() . "\n";
echo "   User ID: " . ($_SESSION['user_id'] ?? "Not logged in") . "\n";

echo "\n=== If you see errors above, that's the problem ===\n";
?>
