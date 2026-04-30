<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protect against apostrophes breaking the code
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Save to database
    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Go back to homepage after saving
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Create a New Post</h2>
<form method="POST">
    Title: <input type="text" name="title" required><br><br>
    Content:<br> <textarea name="content" rows="5" cols="40" required></textarea><br><br>
    <button type="submit">Save Post</button>
</form>
<br>
<a href="index.php">Cancel and go back</a>