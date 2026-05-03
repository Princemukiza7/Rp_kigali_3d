-- Database: 3dgeoportal
-- Users table for RP Kigali Indoor Geoportal

CREATE DATABASE IF NOT EXISTS 3dgeoportal;
USE 3dgeoportal;

-- Users table
CREATE TABLE IF NOT EXISTS users (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
-- Password is hashed using bcrypt
INSERT INTO users (username, email, password, user_type, full_name) VALUES 
('admin', 'admin@rpkigali.geoportal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator'),
('john_doe', 'john.doe@rpkigali.geoportal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'regular', 'John Doe'),
('jane_smith', 'jane.smith@rpkigali.geoportal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'regular', 'Jane Smith');

-- Note: The password hash above is for 'password' - for production, use proper hashing
-- Example to create a new hashed password in PHP: password_hash('yourpassword', PASSWORD_DEFAULT)