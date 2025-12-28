<?php
// This script creates all necessary tables for the To-Do List application

require_once 'Data.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>body{background:#0d0d0d;color:white;font-family:Arial;padding:20px;}</style></head><body>";
    echo "<h1 style='color:red'>❌ Database Connection Failed</h1>";
    echo "<p>Please check your database credentials in Data.php</p>";
    echo "</body></html>";
    exit;
}

try {
    // Create users table
    $sql1 = "CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `email` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    // Create tasks table
    $sql2 = "CREATE TABLE IF NOT EXISTS `tasks` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `date` DATE NOT NULL,
        `time` TIME NOT NULL,
        `color` VARCHAR(7) DEFAULT '#4aa3ff',
        `done` BOOLEAN DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    // Create habits table
    $sql3 = "CREATE TABLE IF NOT EXISTS `habits` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `color` VARCHAR(7) DEFAULT '#4aa3ff',
        `description` VARCHAR(500),
        `category` VARCHAR(50) DEFAULT 'General',
        `frequency` VARCHAR(20) DEFAULT 'Daily',
        `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    // Create habit_tracking table
    $sql4 = "CREATE TABLE IF NOT EXISTS `habit_tracking` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `habit_id` INT NOT NULL,
        `tracking_date` DATE NOT NULL,
        `completed` BOOLEAN DEFAULT 0,
        `notes` VARCHAR(500),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `unique_habit_date` (`habit_id`, `tracking_date`),
        FOREIGN KEY (`habit_id`) REFERENCES `habits`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    // Create achievements table
    $sql5 = "CREATE TABLE IF NOT EXISTS `achievements` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `habit_id` INT NOT NULL,
        `achievement_type` VARCHAR(50) NOT NULL,
        `value` INT NOT NULL DEFAULT 0,
        `achieved_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`habit_id`) REFERENCES `habits`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    // Execute statements
    $statements = [
        ['sql' => $sql1, 'name' => 'users'],
        ['sql' => $sql2, 'name' => 'tasks'],
        ['sql' => $sql3, 'name' => 'habits'],
        ['sql' => $sql4, 'name' => 'habit_tracking'],
        ['sql' => $sql5, 'name' => 'achievements']
    ];

    $results = [];
    foreach ($statements as $statement) {
        try {
            $conn->exec($statement['sql']);
            $results[] = ['success' => true, 'table' => $statement['name']];
        } catch (PDOException $e) {
            $results[] = ['success' => false, 'table' => $statement['name'], 'error' => $e->getMessage()];
        }
    }

    // Display results
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Setup Complete</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                background: linear-gradient(135deg, #0d0d0d 0%, #1a1a2e 100%);
                color: white;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                padding: 20px;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .container {
                background: #111;
                border: 2px solid #4aa3ff;
                border-radius: 15px;
                padding: 40px;
                max-width: 600px;
                box-shadow: 0 0 30px rgba(74, 163, 255, 0.3);
            }

            h1 {
                color: #4aa3ff;
                margin-bottom: 30px;
                text-align: center;
                font-size: 32px;
            }

            .status {
                background: #1a1a1a;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 8px;
                border-left: 4px solid #4aa3ff;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .status.success {
                border-left-color: #4caf50;
            }

            .status.error {
                border-left-color: #d32f2f;
            }

            .status-icon {
                font-size: 20px;
                min-width: 30px;
            }

            .status-text {
                flex: 1;
            }

            .table-name {
                color: #79b7ff;
                font-weight: bold;
            }

            .error-message {
                color: #ff6b6b;
                font-size: 12px;
                margin-top: 5px;
            }

            .summary {
                background: rgba(74, 163, 255, 0.1);
                border: 1px solid #4aa3ff;
                padding: 20px;
                border-radius: 8px;
                margin-top: 30px;
                text-align: center;
            }

            .summary-title {
                color: #4aa3ff;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .button-group {
                display: flex;
                gap: 10px;
                margin-top: 20px;
                justify-content: center;
            }

            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-weight: bold;
                text-decoration: none;
                display: inline-block;
                transition: 0.3s;
            }

            .btn-primary {
                background: #4aa3ff;
                color: white;
            }

            .btn-primary:hover {
                background: #2980b9;
                transform: translateY(-2px);
            }

            .btn-secondary {
                background: #666;
                color: white;
            }

            .btn-secondary:hover {
                background: #555;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🎯 Database Setup</h1>
            
            <?php foreach ($results as $result): ?>
                <div class="status <?php echo $result['success'] ? 'success' : 'error'; ?>">
                    <span class="status-icon"><?php echo $result['success'] ? '✓' : '✕'; ?></span>
                    <div class="status-text">
                        <div class="table-name"><?php echo ucfirst($result['table']) . ' table'; ?></div>
                        <?php if (!$result['success']): ?>
                            <div class="error-message"><?php echo $result['error']; ?></div>
                        <?php else: ?>
                            <small style="color: #888;">Created successfully</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="summary">
                <div class="summary-title">✓ Database Setup Completed!</div>
                <p style="color: #888; margin-bottom: 20px;">All tables have been created successfully. You can now use all features of the To-Do List application.</p>
                
                <div class="button-group">
                    <a href="auth.php" class="btn btn-primary">Go to Login</a>
                    <a href="index.html" class="btn btn-secondary">Skip to App</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php

} catch (PDOException $e) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                background: #0d0d0d;
                color: white;
                font-family: Arial;
                padding: 20px;
            }
            .error-container {
                background: rgba(211, 47, 47, 0.1);
                border: 2px solid #d32f2f;
                border-radius: 10px;
                padding: 20px;
                max-width: 600px;
                margin: 20px auto;
            }
            h1 {
                color: #ff6b6b;
            }
            code {
                background: #1a1a1a;
                padding: 10px;
                border-radius: 5px;
                display: block;
                margin: 10px 0;
                overflow-x: auto;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>❌ Database Error</h1>
            <p>An error occurred while setting up the database:</p>
            <code><?php echo htmlspecialchars($e->getMessage()); ?></code>
            <p style="margin-top: 20px;">Please check your database configuration in <strong>Data.php</strong></p>
        </div>
    </body>
    </html>
    <?php
}
?>
