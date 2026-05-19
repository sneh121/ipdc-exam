<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$dbname = 'ipdc_exam';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    score INT DEFAULT 0,
    total INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Check if columns exist (for pre-existing tables)
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'score'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN score INT DEFAULT 0, ADD COLUMN total INT DEFAULT 0");
}

// Create questions table
$sql = "CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option CHAR(1) NOT NULL,
    section VARCHAR(10) DEFAULT 'A'
)";
$conn->query($sql);

// Check if section column exists (for pre-existing tables)
$result = $conn->query("SHOW COLUMNS FROM questions LIKE 'section'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE questions ADD COLUMN section VARCHAR(10) DEFAULT 'A'");
}

$conn->set_charset("utf8");
?>
