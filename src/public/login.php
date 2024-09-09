<?php
session_start();
require __DIR__ . '/../config/db.php';;  // Database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Try password_verify first
            if (password_verify($password, $user['password'])) {
                // Password is bcrypt-hashed and verified
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                header("Location: admin-dashboard.php");
                exit;
            } else {
                // Try MySQL PASSWORD() method (for legacy users)
                $mysql_hash = $conn->query("SELECT PASSWORD('$password') AS hash")->fetch_assoc()['hash'];

                if ($mysql_hash === $user['password']) {
                    // Rehash password using PHP's password_hash() for future logins
                    $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $update_stmt->bind_param("ss", $new_hashed_password, $username);
                    $update_stmt->execute();
                    $update_stmt->close();

                    // Login successful, update session
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $user['username'];
                    header("Location: admin-dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            }
        } else {
            $error = "Invalid username.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
