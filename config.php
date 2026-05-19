<?php
// Render.com Environment Variables Support + Fallback
$host = $_ENV['DB_HOST'] ?? $_ENV['DATABASE_HOST'] ?? 'localhost';
$user = $_ENV['DB_USERNAME'] ?? $_ENV['DATABASE_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? $_ENV['DATABASE_PASSWORD'] ?? '';
$dbname = $_ENV['DB_DATABASE'] ?? $_ENV['DATABASE_NAME'] ?? 'ipdc_exam';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists (for safety)
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
$conn->query($sql);
$conn->select_db($dbname);

// Create tables if not exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    score INT DEFAULT 0,
    total INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

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

$conn->set_charset("utf8");

?>
