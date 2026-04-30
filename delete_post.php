<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Grab the ID from the web address
$id = $_GET['id'];

// Tell the database to delete this specific post
$conn->query("DELETE FROM posts WHERE id=$id");

// Immediately send the user back to the homepage
header("Location: index.php");
?>