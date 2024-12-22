<?php
// db_connection.php

$host = 'localhost';
$dbname = 'pet_adoptions';
$username = 'root';
$password = '';

// Create a new MySQLi instance
$conn= new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>