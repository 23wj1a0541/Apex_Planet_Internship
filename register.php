<?php
require 'db.php'; // Bring in our database bridge

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    // Scramble the password securely
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Send the data to the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Register</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
</form>