-- NexaLearn Final Project schema
-- Run once with: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS nexalearn;
USE nexalearn;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    branch VARCHAR(100) NOT NULL,
    cgpa DECIMAL(4,2) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    address TEXT,
    course VARCHAR(100),
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Default login: username "admin", password "admin123"
-- Hash generated with PHP: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$BPrme/oBfWVErSic2kiPmO4bwdYjTtbT5DmWxpKZPJLCkEDbirJ7W')
ON DUPLICATE KEY UPDATE username = username;
