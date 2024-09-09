<?php
require __DIR__ . '/../config/db.php';  // Database connection

// Fetch all posts from the database
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapscallion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet"> <!-- Link to custom styles if needed -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Rapscallion</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo $row['title']; ?></h2>
                                <p class="card-text"><?php echo substr($row['content'], 0, 150); ?>...</p>
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Read More</a>
                            </div>
                            <div class="card-footer text-muted">
                                Posted on <?php echo date("F j, Y", strtotime($row['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No posts available.</p>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include __DIR__ . '/../includes/footer.php'; ?>
