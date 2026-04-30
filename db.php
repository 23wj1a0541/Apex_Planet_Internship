<?php
$servername = "localhost";
$username = "root"; // The default username for XAMPP
$password = "";     // The default password for XAMPP is empty
$dbname = "blog";   // The database we just created

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if it worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>