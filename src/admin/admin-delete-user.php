<?php
session_start();
require __DIR__ . '/../config/db.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the user ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Delete the user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: admin-users.php?success=User+deleted+successfully");
        exit;
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}
?>
