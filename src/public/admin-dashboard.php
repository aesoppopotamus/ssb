<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet"> <!-- Link to custom styles if needed -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Admin Dashboard</h1>
        <p class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="list-group">
                    <a href="/admin/admin-posts.php" class="list-group-item list-group-item-action">Manage Posts</a>
                    <a href="/admin/admin-comments.php" class="list-group-item list-group-item-action">Manage Comments</a>
                    <!-- Optional: User management link -->
                    <a href="/admin/admin-users.php" class="list-group-item list-group-item-action">Manage Users</a>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include __DIR__ . '/../includes/footer.php'; ?>

