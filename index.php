<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>

<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<a href="logout.php">Logout</a> | <a href="create_post.php">Write a New Post</a>
<hr>

<h3>All Blog Posts</h3>
<?php
// Ask database for all posts, newest first
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    // Loop through each post and display it
    while($post = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h4>" . $post['title'] . "</h4>";
        echo "<p>" . $post['content'] . "</p>";
        echo "<small>Posted on: " . $post['created_at'] . "</small><br>";
        
        // Edit and Delete links
        echo "<a href='edit_post.php?id=" . $post['id'] . "'>Edit</a> | ";
        echo "<a href='delete_post.php?id=" . $post['id'] . "'>Delete</a>";
        echo "<hr></div>";
    }
} else {
    echo "<p>No posts yet. Be the first to write one!</p>";
}
?>