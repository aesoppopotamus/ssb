<?php
session_start();
require __DIR__ . '/../config/db.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch all posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet"> <!-- Link to custom styles if needed -->
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Posts</h1>
        <a href="/public/create-post.php" class="btn btn-primary mb-3">Add New Post</a>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo date("F j, Y", strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="/admin/edit-post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="/admin/delete-post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
