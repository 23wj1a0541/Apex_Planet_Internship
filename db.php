<?php
// Hide detailed database errors from hackers
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "blog";   

try {
    // Create the connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Lock in the character set to prevent encoding hacks
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // If connection fails, show a generic message instead of a detailed error
    error_log($e->getMessage()); // Logs the real error privately for the admin
    die("A database error occurred. Please try again later.");
}
?>