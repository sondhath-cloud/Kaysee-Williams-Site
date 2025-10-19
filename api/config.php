<?php
// Database Configuration for SiteWorks Hosting
// Update these values with your actual SiteWorks database credentials

$host = 'localhost'; // Usually 'localhost' on SiteWorks
$dbname = 'sondraha_kaysee-williams-site'; // Your database name from cPanel
$username = 'sondraha_kaysee-williams-site'; // Your database username
$password = 'WcwCyQcjfFSD8VSs25pL'; // Your database password

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

