<?php
session_start();

// If the user doesn't have a session wristband, kick them back to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>This is the secret homepage. Only logged-in users can see this.</p>
<a href="logout.php">Logout</a>