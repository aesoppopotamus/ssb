<?php
require __DIR__ . '/../config/db.php';  // Database connection

// Fetch the post based on ID
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);  // Post ID from URL
    $sql = "SELECT * FROM posts WHERE id = $post_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "<p class='text-center'>Post not found.</p>";
        exit;
    }

    // Fetch the comments for this post
    $comments_sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC";
    $comments_stmt = $conn->prepare($comments_sql);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();
} else {
    header("Location: index.php");
    exit;
}

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_POST['author'];
    $content = $_POST['content'];

    // Insert comment into database
    $insert_sql = "INSERT INTO comments (post_id, author, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iss", $post_id, $author, $content);
    
    if ($stmt->execute()) {
        header("Location: post.php?id=$post_id");  // Refresh the page to display the new comment
        exit;
    } else {
        echo "<p class='text-danger'>There was an error submitting your comment. Please try again.</p>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> | My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center"><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-center text-muted">
            Posted on <?php echo date("F j, Y", strtotime($post['created_at'])); ?>
        </p>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3>Comments</h3>

                    <?php if ($comments_result->num_rows > 0): ?>
                        <?php while ($comment = $comments_result->fetch_assoc()): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                                <div class="card-footer text-muted">
                                    <span>Posted by <?php echo htmlspecialchars($comment['author']); ?> on <?php echo date("F j, Y", strtotime($comment['created_at'])); ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Add Comment Form -->
                <div class="add-comment mt-4">
                    <h4>Leave a Comment</h4>
                    <form action="post.php?id=<?php echo $post_id; ?>" method="POST">
                        <div class="mb-3">
                            <label for="author" class="form-label">Name</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Comment</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
