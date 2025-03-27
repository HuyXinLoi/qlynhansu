<?php
// Database configuration
$host = 'localhost';
$username = 'arownj8wvh6_admin';  // Change to your cPanel database username
$password = 'dinhhuy0810';      // Change to your cPanel database password
$database = 'arownj8wvh6_product';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

