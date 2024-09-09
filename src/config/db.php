<?php
$servername = getenv('DB_HOST') ?: 'db';  // Use 'db' if environment variable not set
$username = getenv('DB_USER') ?: 'blog_user';
$password = getenv('DB_PASSWORD') ?: 'your_password';
$dbname = getenv('DB_NAME') ?: 'your_database';

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
