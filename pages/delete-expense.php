<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle expense deletion
if (isset($_GET['id'])) {
    $expense_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM expenses WHERE id='$expense_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: expenses.php"); // Redirect after deletion
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
} else {
    header("Location: expenses.php"); // Redirect if no ID is provided
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Expense</title>
    <style>
        /* Reset some default styles */
body, h1, h2, p, form, input, select, textarea, button {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Basic body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Container for centering and spacing */
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* Header and back button styling */
.back-button-container {
    margin-bottom: 20px;
}

.back-button {
    text-decoration: none;
    color: #007bff;
    font-size: 16px;
}

.back-button:hover {
    text-decoration: underline;
}

/* Form container styling */
.form-container {
    padding: 20px;
}

h2 {
    margin-bottom: 20px;
}

/* Form elements styling */
form input[type="text"],
form input[type="number"],
form input[type="date"],
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form textarea {
    height: 100px;
    resize: vertical;
}

form button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #0056b3;
}

/* Error message styling */
p.error {
    color: red;
    font-size: 14px;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="expenses.php" class="back-button">&#8592; Back</a>
        </div>
        <div class="form-container">
            <h2>Delete Expense</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <p>Expense has been successfully deleted.</p>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
