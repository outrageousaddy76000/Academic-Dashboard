Academic Dashboard for RGIPT

Welcome to the Academic Dashboard for Rajiv Gandhi Institute of Petroleum Technology (RGIPT). This dashboard provides a comprehensive view of academic details for students, courses, and more. Below is a guide to set up and utilize the dashboard effectively.

Setup
Configurations:

Create a config.php file with the following structure:
php
Copy code
<?php
// Database configuration
$dbConfig = [
    'host' => '',
    'username' => '',
    'password' => '',
    'database' => ''
];
$mailConfig = [
    'host' => 'smtp.gmail.com',
    'username' => '',
    'password' => ''
];
?>
Database Setup:

Create a MySQL database and execute the following SQL queries to create necessary tables:
sql
Copy code
-- Courses Table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20),
    course_name VARCHAR(100),
    semester ENUM('Odd', 'Even'),
    dept_name VARCHAR(50),
    course_type VARCHAR(20),
    credit INT,
    credit_structure VARCHAR(100),
    year INT,
    elective BOOLEAN
);

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255)
);

-- Students Table
CREATE TABLE students (
    roll VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100),
    phone_number VARCHAR(20),
    spi_sem1 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem2 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem3 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem4 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem5 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem6 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem7 DECIMAL(4,2) DEFAULT 0.00,
    spi_sem8 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem1 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem2 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem3 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem4 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem5 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem6 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem7 DECIMAL(4,2) DEFAULT 0.00,
    cpi_sem8 DECIMAL(4,2) DEFAULT 0.00,
    positions VARCHAR(255),
    backlog_courses TEXT,
    all_backlog_courses TEXT,
    minor_courses TEXT
);
Usage
Login:

Access the dashboard by logging in with your credentials.
Student Details:

View academic performance, SPI, CPI, positions, and more for each semester.
Check for backlog courses, minor courses, and overall academic progress.
Course Details:

Explore available courses categorized by department, semester, and type.
Check course codes, names, credits, and other relevant information.
Administration:

Admins can manage user access and course details.
Add, edit, or delete users and courses as necessary.

