<?php

$host = "localhost"; // Or your database host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "ecommerce_db"; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Or handle more gracefully
}

// Optional: Set character set (recommended)
$conn->set_charset("utf8");  // Or another appropriate character set

// To close:  $conn->close();  (But typically you close at the end of the script that uses it)

?>