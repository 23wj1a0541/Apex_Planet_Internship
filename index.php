<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// --- 1. SEARCH & PAGINATION SETUP ---
$limit = 3; // We will show 3 posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit; // Calculate where to start pulling posts

// Get the search word if the user typed one
$search = isset($_GET['search']) ? $_GET['search'] : '';
$safe_search = $conn->real_escape_string($search);

// --- 2. COUNT TOTAL POSTS (To figure out how many pages we need) ---
$count_sql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE '%$safe_search%' OR content LIKE '%$safe_search%'";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit); // Round up (e.g., 7 posts / 3 = 2.33 = 3 pages)

// --- 3. FETCH THE ACTUAL POSTS ---
$sql = "SELECT * FROM posts WHERE title LIKE '%$safe_search%' OR content LIKE '%$safe_search%' ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Supercharged Blog</title>
    <!-- Bring in Bootstrap for beautiful UI styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <div>
            <a href="create_post.php" class="btn btn-success">Write a New Post</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- Search Form Section -->
    <form method="GET" class="mb-4">
        <div class="input-group shadow-sm">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="index.php" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <!-- Blog Posts Section -->
    <?php if ($result->num_rows > 0): ?>
        <?php while($post = $result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary"><?php echo htmlspecialchars($post['title']); ?></h4>
                    <p class="card-text"><?php echo htmlspecialchars($post['content']); ?></p>
                    <p class="text-muted small">Posted on: <?php echo $post['created_at']; ?></p>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning">No posts found. Try a different search!</div>
    <?php endif; ?>

    <!-- Pagination Section (Page Numbers at the bottom) -->
    <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

</body>
</html>