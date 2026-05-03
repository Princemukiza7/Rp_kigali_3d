<?php
/**
 * Database Setup Script
 * Run this file to create the database and tables
 */

// Database configuration
$db_host = 'localhost';
$db_name = '3dgeoportal';
$db_user = 'root';
$db_pass = '';

echo "<h2>RP Kigali Geoportal - Database Setup</h2>";

try {
    // Connect without database first
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'>✓ Connected to MySQL server</p>";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    echo "<p style='color:green'>✓ Database '$db_name' created/verified</p>";
    
    // Use database
    $pdo->exec("USE $db_name");
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        user_type ENUM('admin', 'regular') NOT NULL DEFAULT 'regular',
        full_name VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        is_active BOOLEAN DEFAULT TRUE,
        INDEX idx_username (username),
        INDEX idx_email (email),
        INDEX idx_user_type (user_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "<p style='color:green'>✓ Users table created/verified</p>";
    
    // Check if admin exists
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users WHERE username = 'admin'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['cnt'] == 0) {
        // Insert default admin user (password: password)
        $hashed = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, user_type, full_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@rpkigali.geoportal', $hashed, 'admin', 'System Administrator']);
        echo "<p style='color:green'>✓ Default admin user created</p>";
    } else {
        echo "<p style='color:blue'>ℹ Admin user already exists</p>";
    }
    
    echo "<h3 style='color:green'>Setup Complete!</h3>";
    echo "<p><b>Default Login:</b></p>";
    echo "<ul>";
    echo "<li>Username: <b>admin</b></li>";
    echo "<li>Password: <b>password</b></li>";
    echo "</ul>";
    echo "<p><a href='rp-kigali-geoportal.php'>Go to Geoportal</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running.</p>";
}
?>
<style>
body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
h2 { color: #1a2a50; }
a { color: #1a2a50; font-weight: bold; }
</style>