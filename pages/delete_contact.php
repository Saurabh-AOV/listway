<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if contact ID is provided and valid
if (!isset($_POST['delete_contact_id']) || !is_numeric($_POST['delete_contact_id'])) {
    header("Location: lists.php");
    exit();
}

$contact_id = intval($_POST['delete_contact_id']);
$user_id = $_SESSION['user_id'];

// Delete contact from database
$sql = "DELETE FROM contacts WHERE id = $contact_id AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    header("Location: lists.php");
    exit();
} else {
    $error = "Error deleting contact: " . $conn->error;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Contact</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
    <div class="container">
        <h2>Delete Contact</h2>
        <!-- Display error message -->
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    </div>
</body>
</html>
