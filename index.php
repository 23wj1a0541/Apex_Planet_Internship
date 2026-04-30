<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$limit = 3; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit; 

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = "%" . $search . "%"; // Add wildcards for the secure prepared statement

// --- PREPARED STATEMENT FOR COUNTING POSTS ---
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?");
$count_stmt->bind_param("ss", $search_param, $search_param);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// --- PREPARED STATEMENT FOR FETCHING POSTS ---
$stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset); // 'ssii' = string, string, integer, integer
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Secure Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <span class="badge bg-secondary"><?php echo htmlspecialchars($_SESSION['role']); ?></span></h2>
        <div>
            <!-- ROLE CHECK: Only Admins can see the Write Post button -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="create_post.php" class="btn btn-success">Write a New Post</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <form method="GET" class="mb-4">
        <div class="input-group shadow-sm">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="index.php" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <?php while($post = $result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary"><?php echo htmlspecialchars($post['title']); ?></h4>
                    <p class="card-text"><?php echo htmlspecialchars($post['content']); ?></p>
                    <p class="text-muted small">Posted on: <?php echo $post['created_at']; ?></p>
                    
                    <!-- ROLE CHECK: Only Admins can see the Edit/Delete buttons -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this?');">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning">No posts found.</div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav><ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul></nav>
    <?php endif; ?>
</div>
</body>
</html>