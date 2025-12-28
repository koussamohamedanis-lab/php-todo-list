<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'testdb';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            return null;
        }
    }

    public function getConnection() {
        if (!$this->conn) {
            $this->connect();
        }
        return $this->conn;
    }
}

class TaskManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Add a new task
    public function addTask($user_id, $name, $date, $time, $color) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tasks (user_id, name, date, time, color, done) VALUES (?, ?, ?, ?, ?, FALSE)");
            return $stmt->execute([$user_id, $name, $date, $time, $color]);
        } catch (PDOException $e) {
            echo "Error adding task: " . $e->getMessage();
            return false;
        }
    }

    // Get all tasks for a user
    public function getTasks($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT id, user_id, name, date, time, color, done FROM tasks WHERE user_id = ? ORDER BY date, time");
            $stmt->execute([$user_id]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getTasks result: " . json_encode($tasks));
            return $tasks;
        } catch (PDOException $e) {
            error_log("Error fetching tasks: " . $e->getMessage());
            return [];
        }
    }

    // Update task status
    public function updateTaskStatus($task_id, $done) {
        try {
            $stmt = $this->db->prepare("UPDATE tasks SET done = ? WHERE id = ?");
            return $stmt->execute([$done, $task_id]);
        } catch (PDOException $e) {
            echo "Error updating task: " . $e->getMessage();
            return false;
        }
    }

    // Delete a task
    public function deleteTask($task_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
            return $stmt->execute([$task_id]);
        } catch (PDOException $e) {
            echo "Error deleting task: " . $e->getMessage();
            return false;
        }
    }

    // Update task details
    public function updateTask($task_id, $name, $date, $time, $color) {
        try {
            $stmt = $this->db->prepare("UPDATE tasks SET name = ?, date = ?, time = ?, color = ? WHERE id = ?");
            return $stmt->execute([$name, $date, $time, $color, $task_id]);
        } catch (PDOException $e) {
            echo "Error updating task: " . $e->getMessage();
            return false;
        }
    }

    // Get completed tasks for history
    public function getHistoryTasks($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT id, user_id, name, date, time, color, done FROM tasks WHERE user_id = ? AND done = TRUE ORDER BY date DESC");
            $stmt->execute([$user_id]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getHistoryTasks result: " . json_encode($tasks));
            return $tasks;
        } catch (PDOException $e) {
            error_log("Error fetching history: " . $e->getMessage());
            return [];
        }
    }
}

class UserManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Register a new user
    public function register($username, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            return $stmt->execute([$username, $email, $hashedPassword]);
        } catch (PDOException $e) {
            echo "Error registering user: " . $e->getMessage();
            return false;
        }
    }

    // Login user
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user['id'];
            }
            return false;
        } catch (PDOException $e) {
            echo "Error logging in: " . $e->getMessage();
            return false;
        }
    }

    // Get user by ID
    public function getUser($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching user: " . $e->getMessage();
            return null;
        }
    }

    // Update user profile
    public function updateProfile($user_id, $email) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET email = ? WHERE id = ?");
            return $stmt->execute([$email, $user_id]);
        } catch (PDOException $e) {
            echo "Error updating profile: " . $e->getMessage();
            return false;
        }
    }
}

class HabitManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Add a new daily habit
    public function addHabit($user_id, $name, $color) {
        try {
            $stmt = $this->db->prepare("INSERT INTO habits (user_id, name, color, created_date) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$user_id, $name, $color]);
        } catch (PDOException $e) {
            echo "Error adding habit: " . $e->getMessage();
            return false;
        }
    }

    // Get all habits for a user
    public function getHabits($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT id, user_id, name, color, created_date FROM habits WHERE user_id = ? ORDER BY created_date DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching habits: " . $e->getMessage());
            return [];
        }
    }

    // Delete a habit
    public function deleteHabit($habit_id) {
        try {
            // Also delete all related tracking records
            $stmt = $this->db->prepare("DELETE FROM habit_tracking WHERE habit_id = ?");
            $stmt->execute([$habit_id]);
            
            $stmt = $this->db->prepare("DELETE FROM habits WHERE id = ?");
            return $stmt->execute([$habit_id]);
        } catch (PDOException $e) {
            echo "Error deleting habit: " . $e->getMessage();
            return false;
        }
    }

    // Check/uncheck a habit for a specific date
    public function checkHabit($habit_id, $date, $checked) {
        try {
            // Check if record exists
            $stmt = $this->db->prepare("SELECT id FROM habit_tracking WHERE habit_id = ? AND tracking_date = ?");
            $stmt->execute([$habit_id, $date]);
            $exists = $stmt->fetch();

            if ($exists) {
                // Update existing record
                $stmt = $this->db->prepare("UPDATE habit_tracking SET completed = ? WHERE habit_id = ? AND tracking_date = ?");
                return $stmt->execute([$checked, $habit_id, $date]);
            } else {
                // Insert new record
                $stmt = $this->db->prepare("INSERT INTO habit_tracking (habit_id, tracking_date, completed) VALUES (?, ?, ?)");
                return $stmt->execute([$habit_id, $date, $checked]);
            }
        } catch (PDOException $e) {
            echo "Error checking habit: " . $e->getMessage();
            return false;
        }
    }

    // Get habit tracking data for the last N days
    public function getHabitStats($habit_id, $days = 30) {
        try {
            $stmt = $this->db->prepare("
                SELECT tracking_date, completed FROM habit_tracking 
                WHERE habit_id = ? AND tracking_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                ORDER BY tracking_date ASC
            ");
            $stmt->execute([$habit_id, $days]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching habit stats: " . $e->getMessage());
            return [];
        }
    }

    // Get today's habit tracking
    public function getTodayHabits($user_id) {
        try {
            $today = date('Y-m-d');
            $stmt = $this->db->prepare("
                SELECT h.id, h.name, h.color, COALESCE(ht.completed, 0) as completed
                FROM habits h
                LEFT JOIN habit_tracking ht ON h.id = ht.habit_id AND ht.tracking_date = ?
                WHERE h.user_id = ?
                ORDER BY h.created_date DESC
            ");
            $stmt->execute([$today, $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching today's habits: " . $e->getMessage());
            return [];
        }
    }

    // Calculate current streak for a habit
    public function getStreak($habit_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as streak FROM habit_tracking 
                WHERE habit_id = ? AND completed = 1 
                AND tracking_date >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)
                AND tracking_date <= CURDATE()
                ORDER BY tracking_date DESC
            ");
            $stmt->execute([$habit_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['streak'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error calculating streak: " . $e->getMessage());
            return 0;
        }
    }

    // Get current consecutive days streak
    public function getCurrentStreak($habit_id) {
        try {
            // Find consecutive days from today going backward
            $stmt = $this->db->prepare("
                WITH RECURSIVE date_range AS (
                    SELECT CURDATE() as check_date
                    UNION ALL
                    SELECT DATE_SUB(check_date, INTERVAL 1 DAY) 
                    FROM date_range
                    WHERE DATE_SUB(check_date, INTERVAL 1 DAY) >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)
                )
                SELECT COUNT(*) as streak 
                FROM date_range dr
                LEFT JOIN habit_tracking ht ON ht.habit_id = ? AND ht.tracking_date = dr.check_date AND ht.completed = 1
                WHERE ht.id IS NOT NULL
                ORDER BY dr.check_date DESC
                LIMIT 1
            ");
            $stmt->execute([$habit_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['streak'] ?? 0;
        } catch (PDOException $e) {
            // Fallback to simple calculation if RECURSIVE not supported
            return $this->getSimpleStreak($habit_id);
        }
    }

    // Simple streak calculation (fallback)
    public function getSimpleStreak($habit_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT tracking_date FROM habit_tracking 
                WHERE habit_id = ? AND completed = 1
                ORDER BY tracking_date DESC LIMIT 100
            ");
            $stmt->execute([$habit_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($results)) return 0;
            
            $streak = 0;
            $currentDate = new DateTime();
            
            foreach ($results as $row) {
                $trackDate = new DateTime($row['tracking_date']);
                $diff = $currentDate->diff($trackDate)->days;
                
                if ($diff === 0 || $diff === $streak) {
                    $streak++;
                    $currentDate = $trackDate;
                } else {
                    break;
                }
            }
            
            return $streak;
        } catch (Exception $e) {
            error_log("Error calculating simple streak: " . $e->getMessage());
            return 0;
        }
    }

    // Get overall completion percentage for a habit
    public function getCompletionPercentage($habit_id, $days = 30) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    ROUND(SUM(completed) / COUNT(*) * 100, 2) as percentage
                FROM habit_tracking 
                WHERE habit_id = ? AND tracking_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            ");
            $stmt->execute([$habit_id, $days]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['percentage'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error calculating completion: " . $e->getMessage());
            return 0;
        }
    }

    // Get all user statistics
    public function getUserStats($user_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(DISTINCT h.id) as total_habits,
                    COUNT(DISTINCT ht.tracking_date) as days_tracked,
                    ROUND(AVG(CASE WHEN ht.completed = 1 THEN 100 ELSE 0 END), 2) as avg_completion
                FROM habits h
                LEFT JOIN habit_tracking ht ON h.id = ht.habit_id
                WHERE h.user_id = ?
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user stats: " . $e->getMessage());
            return [];
        }
    }

    // Update habit details
    public function updateHabit($habit_id, $name, $color, $description = '', $category = '', $frequency = '') {
        try {
            $stmt = $this->db->prepare("
                UPDATE habits 
                SET name = ?, color = ?, description = ?, category = ?, frequency = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $color, $description, $category, $frequency, $habit_id]);
        } catch (PDOException $e) {
            error_log("Error updating habit: " . $e->getMessage());
            return false;
        }
    }

    // Get habit details
    public function getHabitDetails($habit_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM habits WHERE id = ?");
            $stmt->execute([$habit_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching habit details: " . $e->getMessage());
            return null;
        }
    }
}
