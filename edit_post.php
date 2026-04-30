<?php
session_start();
require 'db.php';

// THE BOUNCER: Admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit; 
}

// Check if ID is a valid number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Title and Content cannot be empty.";
    } else {
        // PREPARED STATEMENT: Secure the Update
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $id); // 'ssi' = string, string, integer

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error updating post.";
        }
        $stmt->close();
    }
}

// PREPARED STATEMENT: Secure the Select
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id); // 'i' = integer
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// If the post doesn't exist, send them away
if (!$post) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Edit Post</h2>
                    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Post</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>