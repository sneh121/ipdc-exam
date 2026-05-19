<?php
$host = 'localhost';                    // Usually 'localhost' on GoogieHost
$user = 'vhrfafzi_ipdc_test';
$pass = 'hcUpVEmCnSJ7aZ8UEL6K';
$dbname = 'vhrfafzi_ipdc_test';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
