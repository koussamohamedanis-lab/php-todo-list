<?php
echo "=== DATABASE CONNECTION TEST ===\n\n";

require_once 'Data.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "✓ Database Connected Successfully\n";
        echo "Driver: PDO MySQL\n";
        
        // Test each table
        echo "\n=== TABLE VERIFICATION ===\n";
        $tables = ['users', 'tasks', 'habits', 'habit_tracking', 'achievements'];
        
        foreach ($tables as $table) {
            try {
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM $table");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "✓ $table: OK (rows: " . $result['count'] . ")\n";
            } catch (Exception $e) {
                echo "✗ $table: ERROR - " . $e->getMessage() . "\n";
            }
        }
        
        // Test actual data retrieval
        echo "\n=== DATA RETRIEVAL TEST ===\n";
        
        // Start session to get user_id
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            echo "Current User ID: $user_id\n";
            
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    echo "✓ User found: " . $user['username'] . "\n";
                } else {
                    echo "✗ User not found in database\n";
                }
            } catch (Exception $e) {
                echo "✗ Error fetching user: " . $e->getMessage() . "\n";
            }
            
            try {
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM habits WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "✓ Habits count for user: " . $result['count'] . "\n";
            } catch (Exception $e) {
                echo "✗ Error fetching habits: " . $e->getMessage() . "\n";
            }
            
            try {
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tasks WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "✓ Tasks count for user: " . $result['count'] . "\n";
            } catch (Exception $e) {
                echo "✗ Error fetching tasks: " . $e->getMessage() . "\n";
            }
        } else {
            echo "⚠ Not logged in - can't test user data\n";
        }
        
    } else {
        echo "✗ Connection returned null\n";
    }
} catch (PDOException $e) {
    echo "✗ Database Connection Failed\n";
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
