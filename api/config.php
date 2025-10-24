<?php
// Database Configuration for SiteWorks Hosting
// Updated with actual SiteWorks database credentials

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost'; // Usually 'localhost' on SiteWorks
$dbname = 'sondraha_kaysee-williams-site'; // Your database name from cPanel
$username = 'sondraha_kaysee-williams-site'; // Your database username
$password = 'vynqym-fybRa4-pefmok'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    
    // If this is being called from a web request, return JSON error
    if (isset($_SERVER['HTTP_HOST'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed: ' . $e->getMessage(),
            'details' => [
                'host' => $host,
                'dbname' => $dbname,
                'username' => $username,
                'error_code' => $e->getCode()
            ]
        ]);
        exit;
    } else {
        // If called from command line or include, throw exception
        throw $e;
    }
}
?>

