<?php
// Database Configuration for SiteWorks Hosting
// Update these values with your actual SiteWorks database credentials

$host = 'localhost'; // Usually 'localhost' on SiteWorks
$dbname = 'your_database_name'; // Your database name from cPanel
$username = 'your_database_user'; // Your database username
$password = 'your_database_password'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please check your configuration.'
    ]);
    exit;
}
?>

