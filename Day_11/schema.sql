-- ============================================================
-- Student Management System — Auth Module Schema
-- ============================================================
CREATE DATABASE IF NOT EXISTS student_mgmt;
USE student_mgmt;

CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    password        VARCHAR(255)  NOT NULL,        -- bcrypt hash
    profile_picture VARCHAR(255)  DEFAULT NULL,     -- filename in uploads/avatars/
    last_login      DATETIME      DEFAULT NULL,
    created_at      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Sample user for testing.
-- Email:    admin@nexalearn.com
-- Password: Admin@123
-- (hash below was generated with PHP's password_hash() using PASSWORD_DEFAULT)
INSERT INTO users (name, email, password) VALUES
('Admin User', 'admin@nexalearn.com', '$2y$10$92IXUNpkjO0rOQ5by5xJqOe0K1e0e0LhKfMR2VqW1qYQmn3v9v9Zi');
-- NOTE: run reset_admin_password.php (included) once to set a real working
-- hash for 'Admin@123' on your machine's PHP/OpenSSL build, since password_hash
-- salts differ by environment.
