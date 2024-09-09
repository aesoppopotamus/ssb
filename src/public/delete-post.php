<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../config/db.php';;  // Your database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php");
    } else {
        echo "Error deleting post.";
    }

    $stmt->close();
} else {
    echo "Invalid post ID.";
}
