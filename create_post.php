<?php
session_start();
require 'db.php';

// THE BOUNCER: Kick them out if they aren't logged in OR if they aren't an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit; 
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SERVER-SIDE VALIDATION
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Title and Content cannot be empty.";
    } else {
        // PREPARED STATEMENT: Secure the database insertion
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content); // 'ss' = two strings

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error saving post.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Create a New Post</h2>
                    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="content" class="form-control" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Save Post</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>