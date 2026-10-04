<?php
// Database configuration
$host = 'mysql:host=localhost;dbname=cst499;charset=utf8';
$user = 'root';
$pass = '';

// Project base URL
define('BASE_URL', '/CST499');

try {
	$conn = new PDO($host, $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}
?>