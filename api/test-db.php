<?php
// Database Connection Test
// Upload this file to test your database connection

header('Content-Type: application/json');

// Database Configuration
$host = 'localhost';
$dbname = 'sondraha_kaysee-williams-site';
$username = 'sondraha_kaysee-williams-site';
$password = 'vynqym-fybRa4-pefmok';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if tables exist
    $tables = ['site_content', 'site_images', 'admin_users'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "<p style='color: green;'>✅ Table '$table' exists with $count records</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Table '$table' does not exist</p>";
        }
    }
    
    // Test admin user
    try {
        $stmt = $pdo->query("SELECT username FROM admin_users WHERE username = 'admin'");
        $user = $stmt->fetch();
        if ($user) {
            echo "<p style='color: green;'>✅ Admin user exists</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Admin user not found</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Cannot check admin user: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check:</p>";
    echo "<ul>";
    echo "<li>Database credentials are correct</li>";
    echo "<li>Database exists</li>";
    echo "<li>User has proper permissions</li>";
    echo "<li>MySQL service is running</li>";
    echo "</ul>";
}

// Test PHP extensions
echo "<h3>PHP Extensions Test</h3>";
$required_extensions = ['pdo', 'pdo_mysql', 'session'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ $ext extension loaded</p>";
    } else {
        echo "<p style='color: red;'>❌ $ext extension not loaded</p>";
    }
}

// Test session functionality
echo "<h3>Session Test</h3>";
session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p style='color: green;'>✅ Sessions working</p>";
} else {
    echo "<p style='color: red;'>❌ Sessions not working</p>";
}
?>
