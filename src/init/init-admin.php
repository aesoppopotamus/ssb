<?php
require __DIR__ . '/../config/db.php';;  // Include the database connection

// Add the admin user
$admin_username = getenv('ADMIN_USERNAME') ?: 'admin';
$admin_password = getenv('ADMIN_PASSWORD') ?: 'admin_password';

echo "Checking for existing admin user.\n";

// Check if the admin user already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // Show the actual error if prepare fails
}

$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Admin user not found. Creating admin user.\n";
    
    // If the admin doesn't exist, create the admin user
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $admin_username, $hashed_password);

    if ($insert_stmt->execute()) {
        echo "Admin user created successfully!\n";
    } else {
        echo "Error creating admin user: " . $insert_stmt->error . "\n";
    }

    $insert_stmt->close();
} else {
    echo "Admin user already exists, skipping creation.\n";
}

$stmt->close();
$conn->close();

echo "Admin user setup complete.\n";
