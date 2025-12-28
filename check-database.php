<?php
session_start();
header('Content-Type: application/json');

require_once 'Data.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // Check if tables exist
    $stmt = $conn->query("SHOW TABLES LIKE 'habit%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) >= 2) {
        // Check table structure
        $stmt = $conn->query("DESCRIBE habits");
        $habitsColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->query("DESCRIBE habit_tracking");
        $trackingColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'message' => 'Database tables are properly set up',
            'tables' => $tables,
            'habits_structure' => $habitsColumns,
            'tracking_structure' => $trackingColumns
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing tables. Found: ' . implode(', ', $tables),
            'tables' => $tables
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking database: ' . $e->getMessage()
    ]);
}
?>
