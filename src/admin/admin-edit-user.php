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

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }

    // Update user details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the new password

        $update_stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $username, $password, $user_id);

        if ($update_stmt->execute()) {
            echo "User updated successfully!";
        } else {
            echo "Error updating user.";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit User</h1>

        <form action="admin-edit-user.php
