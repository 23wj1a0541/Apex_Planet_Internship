<?php
require 'db.php';
$message = ""; $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SERVER-SIDE VALIDATION: Trim invisible spaces
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // SERVER-SIDE VALIDATION: Check lengths
    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // PREPARED STATEMENT: Lock down the SQL
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        $stmt->bind_param("ss", $username, $hashed_password); // 'ss' means two strings
        
        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php' class='alert-link'>Login here</a>";
        } else {
            $error = "Error: Username might already exist.";
        }
        $stmt->close(); // Close the secure connection
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Register</h2>
                    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
                    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <!-- CLIENT-SIDE VALIDATION: minlength and required -->
                            <input type="text" name="username" class="form-control" required minlength="3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                    <div class="text-center mt-3"><small>Already have an account? <a href="login.php">Login here</a></small></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>