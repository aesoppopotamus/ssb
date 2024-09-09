<?php
session_start();
require __DIR__ . '/../config/db.php';  // Database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);  // Trim input to remove extra spaces
    $password = $_POST['password'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Fetch the user from the database using a prepared statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Use password_verify to check bcrypt-hashed password
            if (password_verify($password, $user['password'])) {
                // Password is verified, regenerate session ID for security
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                header("Location: admin-dashboard.php");
                exit;
            } else {
                // Try MySQL PASSWORD() method (for legacy users)
                $stmt_legacy = $conn->prepare("SELECT PASSWORD(?) AS hash");
                $stmt_legacy->bind_param("s", $password);
                $stmt_legacy->execute();
                $legacy_result = $stmt_legacy->get_result();
                $mysql_hash = $legacy_result->fetch_assoc()['hash'];
                
                // If the legacy hash matches, rehash the password with bcrypt
                if ($mysql_hash === $user['password']) {
                    // Rehash the password using PHP's password_hash()
                    $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Update the user's password in the database
                    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $update_stmt->bind_param("ss", $new_hashed_password, $username);
                    $update_stmt->execute();
                    $update_stmt->close();

                    // Regenerate session and login
                    session_regenerate_id(true);
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
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <h2 class="text-center">Admin Login</h2>

                <!-- Error message -->
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Login form -->
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
