<?php
// Password Hash Generator
// Run this to generate a proper password hash

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: $password\n";
echo "Hash: $hash\n";

// Test the hash
if (password_verify($password, $hash)) {
    echo "✅ Hash verification successful!\n";
} else {
    echo "❌ Hash verification failed!\n";
}

// Generate SQL
echo "\nSQL to update admin user:\n";
echo "UPDATE admin_users SET password_hash = '$hash' WHERE username = 'admin';\n";
?>
