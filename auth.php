<?php
session_start();
require_once 'Data.php';

// Determine the action
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'register') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'All fields are required';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            $userManager = new UserManager();
            if ($userManager->register($username, $email, $password)) {
                $message = 'Registration successful! You can now login.';
                $action = 'login';
            } else {
                $error = 'Username already exists or registration failed';
            }
        }
    } elseif ($action == 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Username and password are required';
        } else {
            $userManager = new UserManager();
            $user_id = $userManager->login($username, $password);
            if ($user_id) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                // Set username in localStorage so client-side auth check passes
                echo '<script>localStorage.setItem("username", ' . json_encode($username) . '); window.location.href = "index.html";</script>';
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List - Authentication</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0d0d0d 0%, #1a1a2e 100%);
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-container {
            background: #111;
            border: 2px solid #4aa3ff;
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 30px rgba(74, 163, 255, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #4aa3ff;
            font-size: 28px;
        }

        .toggle-form {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .toggle-form a {
            color: #4aa3ff;
            cursor: pointer;
            text-decoration: none;
        }

        .toggle-form a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #79b7ff;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4aa3ff;
            box-shadow: 0 0 10px rgba(74, 163, 255, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #005fcc;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background: rgba(76, 175, 80, 0.2);
            color: #4caf50;
            border: 1px solid #4caf50;
        }

        .message.error {
            background: rgba(244, 67, 54, 0.2);
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <h1>To-Do List</h1>

    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <div id="loginForm" class="form-section <?php echo $action == 'login' ? 'active' : ''; ?>">
        <div class="toggle-form">
            Don't have an account? <a onclick="toggleForm()">Register</a>
        </div>

        <form method="POST" action="auth.php?action=login">
            <div class="form-group">
                <label for="login_username">Username</label>
                <input type="text" id="login_username" name="username" required>
            </div>

            <div class="form-group">
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="form-section <?php echo $action == 'register' ? 'active' : ''; ?>">
        <div class="toggle-form">
            Already have an account? <a onclick="toggleForm()">Login</a>
        </div>

        <form method="POST" action="auth.php?action=register">
            <div class="form-group">
                <label for="register_username">Username</label>
                <input type="text" id="register_username" name="username" required>
            </div>

            <div class="form-group">
                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="email" required>
            </div>

            <div class="form-group">
                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="password" required>
            </div>

            <div class="form-group">
                <label for="register_confirm">Confirm Password</label>
                <input type="password" id="register_confirm" name="confirm" required>
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>
    </div>
</div>

<script>
function toggleForm() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    loginForm.classList.toggle('active');
    registerForm.classList.toggle('active');
}
</script>

</body>
</html>
