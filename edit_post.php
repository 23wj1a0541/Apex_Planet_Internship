<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Grab the ID from the web address
$id = $_GET['id'];

// If the form is submitted, update the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    }
}

// Fetch the current post data so we can put it in the text boxes
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();
?>

<h2>Edit Post</h2>
<form method="POST">
    Title: <input type="text" name="title" value="<?php echo $post['title']; ?>" required><br><br>
    Content:<br> <textarea name="content" rows="5" cols="40" required><?php echo $post['content']; ?></textarea><br><br>
    <button type="submit">Update Post</button>
</form>
<br>
<a href="index.php">Cancel</a>