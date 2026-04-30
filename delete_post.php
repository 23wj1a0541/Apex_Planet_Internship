<?php
session_start();
require 'db.php';

// THE BOUNCER: Admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit; 
}

// Check if ID exists and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // PREPARED STATEMENT: Secure the Delete
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $id); // 'i' = integer
    $stmt->execute();
    $stmt->close();
}

// Send the user back to the homepage
header("Location: index.php");
exit;
?>