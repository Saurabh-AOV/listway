<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get sell ID from URL parameter
$sell_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Delete sell record
$sql_delete = "DELETE FROM sales WHERE id='$sell_id' AND user_id='$user_id'";
if ($conn->query($sql_delete) === TRUE) {
    header("Location: sales.php"); // Redirect to sales listing page
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
