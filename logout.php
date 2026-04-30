<?php
session_start();
session_destroy(); // Tear off the wristband
header("Location: login.php"); // Send back to login
?>