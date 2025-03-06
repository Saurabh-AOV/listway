<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico"> <!-- Favicon Link -->
</head>
<body>
    <div class="dashboard-content">
        <h1>Welcome to Listway</h1>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>! This is your dashboard.</p>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
