<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

session_start();
header('Content-Type: application/json');

// Add debugging headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once 'Data.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false, 
        'message' => 'Not authenticated',
        'debug' => [
            'session_id' => session_id(),
            'session_data' => $_SESSION ?? [],
            'cookies' => $_COOKIE ?? []
        ]
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

$taskManager = new TaskManager();
$userManager = new UserManager();
$habitManager = new HabitManager();

switch ($action) {
    case 'getTasks':
        $tasks = $taskManager->getTasks($user_id);
        $response = ['success' => true, 'tasks' => $tasks];
        break;

    case 'addTask':
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $date = $data['date'] ?? '';
        $time = $data['time'] ?? '';
        $color = $data['color'] ?? '#4aa3ff';

        error_log("addTask data: name='$name', date='$date', time='$time', color='$color'");

        if (!empty($name) && !empty($date) && !empty($time)) {
            if ($taskManager->addTask($user_id, $name, $date, $time, $color)) {
                $response = ['success' => true, 'message' => 'Task added successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to add task'];
            }
        } else {
            $response = ['success' => false, 'message' => 'All fields are required'];
        }
        break;

    case 'updateTask':
        $data = json_decode(file_get_contents('php://input'), true);
        $task_id = $data['id'] ?? '';
        $name = $data['name'] ?? '';
        $date = $data['date'] ?? '';
        $time = $data['time'] ?? '';
        $color = $data['color'] ?? '#4aa3ff';

        if ($taskManager->updateTask($task_id, $name, $date, $time, $color)) {
            $response = ['success' => true, 'message' => 'Task updated successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Failed to update task'];
        }
        break;

    case 'deleteTask':
        $task_id = $_GET['id'] ?? '';
        if ($taskManager->deleteTask($task_id)) {
            $response = ['success' => true, 'message' => 'Task deleted successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Failed to delete task'];
        }
        break;

    case 'completeTask':
        $task_id = $_GET['id'] ?? '';
        $done = $_GET['done'] ?? 0;
        if ($taskManager->updateTaskStatus($task_id, $done)) {
            $response = ['success' => true, 'message' => 'Task status updated'];
        } else {
            $response = ['success' => false, 'message' => 'Failed to update task status'];
        }
        break;

    case 'getHistory':
        $tasks = $taskManager->getHistoryTasks($user_id);
        error_log("API getHistory returning: " . json_encode($tasks));
        $response = ['success' => true, 'tasks' => $tasks];
        break;

    case 'getProfile':
        $user = $userManager->getUser($user_id);
        $response = ['success' => true, 'user' => $user];
        break;

    case 'updateProfile':
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';

        if (!empty($email)) {
            if ($userManager->updateProfile($user_id, $email)) {
                $response = ['success' => true, 'message' => 'Profile updated successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update profile'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Email is required'];
        }
        break;

    case 'logout':
        session_destroy();
        $response = ['success' => true, 'message' => 'Logged out successfully'];
        break;

    // Daily Habits endpoints
    case 'getHabits':
        $habits = $habitManager->getHabits($user_id);
        $response = ['success' => true, 'habits' => $habits];
        break;

    case 'addHabit':
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $color = $data['color'] ?? '#4aa3ff';

        if (!empty($name)) {
            if ($habitManager->addHabit($user_id, $name, $color)) {
                $response = ['success' => true, 'message' => 'Habit added successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to add habit'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Habit name is required'];
        }
        break;

    case 'deleteHabit':
        $habit_id = $_GET['id'] ?? '';
        if ($habitManager->deleteHabit($habit_id)) {
            $response = ['success' => true, 'message' => 'Habit deleted successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Failed to delete habit'];
        }
        break;

    case 'checkHabit':
        $data = json_decode(file_get_contents('php://input'), true);
        $habit_id = $data['habit_id'] ?? '';
        $date = $data['date'] ?? date('Y-m-d');
        $checked = $data['checked'] ?? 0;

        if (!empty($habit_id)) {
            if ($habitManager->checkHabit($habit_id, $date, $checked)) {
                $response = ['success' => true, 'message' => 'Habit status updated'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update habit status'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Habit ID is required'];
        }
        break;

    case 'getHabitStats':
        $habit_id = $_GET['id'] ?? '';
        $days = $_GET['days'] ?? 30;
        
        if (!empty($habit_id)) {
            $stats = $habitManager->getHabitStats($habit_id, $days);
            $response = ['success' => true, 'stats' => $stats];
        } else {
            $response = ['success' => false, 'message' => 'Habit ID is required'];
        }
        break;

    case 'getHabitDetails':
        $habit_id = $_GET['id'] ?? '';
        
        if (!empty($habit_id)) {
            $habit = $habitManager->getHabitDetails($habit_id);
            $streak = $habitManager->getSimpleStreak($habit_id);
            $completion = $habitManager->getCompletionPercentage($habit_id, 30);
            $response = [
                'success' => true, 
                'habit' => $habit,
                'streak' => $streak,
                'completion' => $completion
            ];
        } else {
            $response = ['success' => false, 'message' => 'Habit ID is required'];
        }
        break;

    case 'getUserStats':
        $stats = $habitManager->getUserStats($user_id);
        $response = ['success' => true, 'stats' => $stats];
        break;

    case 'updateHabit':
        $data = json_decode(file_get_contents('php://input'), true);
        $habit_id = $data['id'] ?? '';
        $name = $data['name'] ?? '';
        $color = $data['color'] ?? '#4aa3ff';
        $description = $data['description'] ?? '';
        $category = $data['category'] ?? '';
        $frequency = $data['frequency'] ?? '';

        if (!empty($habit_id) && !empty($name)) {
            if ($habitManager->updateHabit($habit_id, $name, $color, $description, $category, $frequency)) {
                $response = ['success' => true, 'message' => 'Habit updated successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update habit'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Habit ID and name are required'];
        }
        break;
}

echo json_encode($response);
?>
