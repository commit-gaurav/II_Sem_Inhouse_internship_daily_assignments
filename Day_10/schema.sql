-- ================================================
-- Student Management Portal - Database Schema
-- ================================================

CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    branch VARCHAR(50) NOT NULL,
    cgpa DECIMAL(3,2) NOT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data (optional - remove if you want to start empty)
INSERT INTO students (name, email, branch, cgpa, status) VALUES
('Aarav Sharma', 'aarav.sharma@example.com', 'CSE', 8.75, 'Active'),
('Priya Verma', 'priya.verma@example.com', 'ECE', 9.10, 'Active'),
('Rohan Gupta', 'rohan.gupta@example.com', 'ME', 7.40, 'Inactive'),
('Sneha Iyer', 'sneha.iyer@example.com', 'CSE', 8.20, 'Active'),
('Karan Malhotra', 'karan.malhotra@example.com', 'CE', 6.90, 'Active'),
('Isha Nair', 'isha.nair@example.com', 'ECE', 9.45, 'Inactive');
