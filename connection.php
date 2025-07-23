<?php
$host = "localhost";      // or 127.0.0.1
$user = "root";           // your DB username
$password = "";           // your DB password ("" for localhost default)
$database = "carpool_db"; // database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
