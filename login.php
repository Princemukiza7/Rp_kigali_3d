<?php
/**
 * RP Kigali Indoor Geoportal - Login Handler
 * Handles user authentication and session management
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', '3dgeoportal');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP default - change for production

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Database connection
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

/**
 * Authenticate user
 */
function authenticateUser($username, $password) {
    $conn = getDBConnection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed', 'user_type' => null];
    }

    try {
        $stmt = $conn->prepare("SELECT id, username, email, password, user_type, full_name, is_active FROM users WHERE (username = :username OR email = :email) AND is_active = 1");
        $stmt->execute(['username' => $username, 'email' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute(['id' => $user['id']]);

            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'user_type' => $user['user_type'],
                    'full_name' => $user['full_name']
                ],
                'user_type' => $user['user_type']
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password', 'user_type' => null];
        }
    } catch (PDOException $e) {
        error_log("Authentication error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Authentication error: ' . $e->getMessage(), 'user_type' => null];
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_type'] === 'admin';
}

/**
 * Get current user info
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'user_type' => $_SESSION['user_type'],
        'full_name' => $_SESSION['full_name']
    ];
}

/**
 * Logout user
 */
function logout() {
    session_unset();
    session_destroy();
    return true;
}

/**
 * Register new user
 */
function registerUser($username, $email, $password, $fullName = '') {
    $conn = getDBConnection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    try {
        // Check if username or email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $checkStmt->execute(['username' => $username, 'email' => $email]);
        
        if ($checkStmt->fetch()) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user (default type is 'regular')
        $insertStmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, user_type) VALUES (:username, :email, :password, :full_name, 'regular')");
        $insertStmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'full_name' => $fullName ?: $username
        ]);

        return [
            'success' => true,
            'message' => 'Registration successful! Please sign in.'
        ];
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Registration error: ' . $e->getMessage()];
    }
}

/**
 * Handle login form submission
 */
function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Please enter username and password', 'user_type' => null]);
            exit;
        }

        $result = authenticateUser($username, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['email'] = $result['user']['email'];
            $_SESSION['user_type'] = $result['user']['user_type'];
            $_SESSION['full_name'] = $result['user']['full_name'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();

            echo json_encode([
                'success' => true, 
                'message' => 'Login successful',
                'user_type' => $result['user']['user_type'],
                'user' => $result['user'],
                'redirect' => 'rp-kigali-geoportal.php'
            ]);
        } else {
            echo json_encode($result);
        }
        exit;
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout')) {
        // Check if it's an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        logout();
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        } else {
            header('Location: rp-kigali-geoportal.php');
        }
        exit;
    }
}

/**
 * Handle signup form submission
 */
function handleSignup() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');

        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        if (strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username must be at least 3 characters']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
            exit;
        }

        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
            exit;
        }

        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit;
        }

        $result = registerUser($username, $email, $password, $fullName);
        echo json_encode($result);
        exit;
    }
}

// Handle requests
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            handleLogin();
            break;
        case 'signup':
            handleSignup();
            break;
        case 'logout':
            handleLogout();
            break;
        case 'check':
            header('Content-Type: application/json');
            if (isLoggedIn()) {
                echo json_encode([
                    'logged_in' => true,
                    'user' => getCurrentUser()
                ]);
            } else {
                echo json_encode(['logged_in' => false]);
            }
            break;
    }
}