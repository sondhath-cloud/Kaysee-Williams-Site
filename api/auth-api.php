<?php
// Admin Authentication API
// Handles login/logout for admin access
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config with error handling
try {
    require_once 'config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Configuration error: ' . $e->getMessage()
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $action = $input['action'] ?? '';
            
            switch($action) {
                case 'login':
                    handleLogin($input);
                    break;
                case 'logout':
                    handleLogout();
                    break;
                case 'check':
                    checkAuthStatus();
                    break;
                default:
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid action'
                    ]);
            }
            break;
            
        case 'GET':
            checkAuthStatus();
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
    }
    
} catch (PDOException $e) {
    error_log("Auth API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

function handleLogin($input) {
    global $pdo;
    
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username and password are required'
        ]);
        return;
    }
    
    // Check credentials against database
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && md5($password) === $user['password_hash']) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['login_time'] = time();
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username']
            ]
        ]);
    } else {
        // Login failed
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }
}

function handleLogout() {
    // Clear session
    session_destroy();
    session_start();
    
    echo json_encode([
        'success' => true,
        'message' => 'Logged out successfully'
    ]);
}

function checkAuthStatus() {
    $isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    
    if ($isLoggedIn) {
        // Check if session is still valid (24 hour timeout)
        $loginTime = $_SESSION['login_time'] ?? 0;
        $sessionTimeout = 24 * 60 * 60; // 24 hours
        
        if (time() - $loginTime > $sessionTimeout) {
            // Session expired
            session_destroy();
            session_start();
            $isLoggedIn = false;
        }
    }
    
    echo json_encode([
        'success' => true,
        'logged_in' => $isLoggedIn,
        'user' => $isLoggedIn ? [
            'id' => $_SESSION['admin_user_id'] ?? null,
            'username' => $_SESSION['admin_username'] ?? null
        ] : null
    ]);
}
?>
