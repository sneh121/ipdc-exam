<?php
$host = 'sql303.hstn.me';
$user = 'mseet_41963470';
$pass = 'Snehsankalia1';
$dbname = 'mseet_41963470_ipdc_test';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
