<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$post_id = isset($_GET['id']) ? intval($_GET['id']) : null;

?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Post Deleted</h2>
    <?php if ($post_id): ?>
        <p class="text-center">The post with ID <?php echo $post_id; ?> has been successfully deleted.</p>
    <?php else: ?>
        <p class="text-center">The post has been successfully deleted.</p>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="/public/admin-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
