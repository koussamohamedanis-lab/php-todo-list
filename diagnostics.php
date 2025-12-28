<?php
// Comprehensive diagnostic page
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Diagnostics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            margin: 5px;
            font-weight: bold;
        }
        .ok {
            background: #4caf50;
            color: white;
        }
        .error {
            background: #f44336;
            color: white;
        }
        .warning {
            background: #ff9800;
            color: white;
        }
        code {
            background: #f0f0f0;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
        h2 {
            border-bottom: 2px solid #4aa3ff;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #4aa3ff;
            color: white;
        }
    </style>
</head>
<body>
    <h1>To-Do List Application Diagnostics</h1>

    <div class="section">
        <h2>Session Status</h2>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<span class="status ok">✓ Logged in (User ID: ' . $_SESSION['user_id'] . ')</span>';
        } else {
            echo '<span class="status error">✗ Not logged in</span>';
        }
        ?>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
    </div>

    <div class="section">
        <h2>Database Connection Test</h2>
        <?php
        require 'Data.php';
        try {
            $db = new Database();
            echo '<span class="status ok">✓ Database connected</span>';
            
            // Test if tables exist
            $tables = ['users', 'tasks', 'habits', 'habit_tracking', 'achievements'];
            echo '<table>';
            echo '<tr><th>Table</th><th>Status</th></tr>';
            foreach ($tables as $table) {
                try {
                    $stmt = $db->getConnection()->prepare("SELECT 1 FROM $table LIMIT 1");
                    $stmt->execute();
                    echo "<tr><td>$table</td><td><span class='status ok'>✓ Exists</span></td></tr>";
                } catch (Exception $e) {
                    echo "<tr><td>$table</td><td><span class='status error'>✗ Error</span></td></tr>";
                }
            }
            echo '</table>';
        } catch (Exception $e) {
            echo '<span class="status error">✗ Database connection failed</span>';
            echo '<p>Error: ' . $e->getMessage() . '</p>';
        }
        ?>
    </div>

    <div class="section">
        <h2>API Endpoints Test</h2>
        <p>Click the test button to check if API endpoints are working:</p>
        <button onclick="testAPIs()">Test All API Endpoints</button>
        <div id="apiResults"></div>
    </div>

    <div class="section">
        <h2>File Structure</h2>
        <?php
        $files = [
            'auth.php' => 'Authentication',
            'api.php' => 'API Endpoints',
            'Data.php' => 'Database Classes',
            'session-check.php' => 'Session Check',
            'index.html' => 'To-Do List Page',
            'dashboard.html' => 'Dashboard Page',
            'habits.html' => 'Habits Page',
            'history.html' => 'History Page',
            'profile.html' => 'Profile Page'
        ];
        
        echo '<table>';
        echo '<tr><th>File</th><th>Status</th><th>Description</th></tr>';
        foreach ($files as $file => $desc) {
            $exists = file_exists($file) ? '✓ Exists' : '✗ Missing';
            $status = file_exists($file) ? 'ok' : 'error';
            echo "<tr><td>$file</td><td><span class='status $status'>$exists</span></td><td>$desc</td></tr>";
        }
        echo '</table>';
        ?>
    </div>

    <script>
        function testAPIs() {
            const results = document.getElementById('apiResults');
            results.innerHTML = '<p>Testing APIs...</p>';

            const endpoints = [
                { name: 'Session Check', url: 'session-check.php' },
                { name: 'getUserStats', url: 'api.php?action=getUserStats' },
                { name: 'getHabits', url: 'api.php?action=getHabits' },
                { name: 'getTasks', url: 'api.php?action=getTasks' },
                { name: 'getHistory', url: 'api.php?action=getHistory' }
            ];

            let html = '<table>';
            html += '<tr><th>Endpoint</th><th>Status</th><th>Response</th></tr>';

            let completed = 0;
            endpoints.forEach(ep => {
                fetch(ep.url)
                    .then(r => r.json())
                    .then(data => {
                        const success = data.success !== false;
                        const status = success ? '<span class="status ok">✓ OK</span>' : '<span class="status error">✗ Failed</span>';
                        const response = JSON.stringify(data).substring(0, 50) + (JSON.stringify(data).length > 50 ? '...' : '');
                        
                        html += `<tr><td>${ep.name}</td><td>${status}</td><td><code>${response}</code></td></tr>`;
                        completed++;
                        if (completed === endpoints.length) {
                            html += '</table>';
                            results.innerHTML = html;
                        }
                    })
                    .catch(err => {
                        const status = '<span class="status error">✗ Error</span>';
                        html += `<tr><td>${ep.name}</td><td>${status}</td><td><code>${err.message}</code></td></tr>`;
                        completed++;
                        if (completed === endpoints.length) {
                            html += '</table>';
                            results.innerHTML = html;
                        }
                    });
            });
        }
    </script>
</body>
</html>
