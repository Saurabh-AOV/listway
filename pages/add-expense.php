<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item = $conn->real_escape_string($_POST['item']);
    $price = floatval($_POST['price']);
    $date = $conn->real_escape_string($_POST['date']);
    $reason = $conn->real_escape_string($_POST['reason']);

    $sql = "INSERT INTO expenses (user_id, item, price, date, reason) 
            VALUES ('$user_id', '$item', '$price', '$date', '$reason')";

    if ($conn->query($sql) === TRUE) {
        header("Location: expenses.php"); // Redirect to an expenses listing page
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
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
            <h2>Add Expense</h2>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post" action="add-expense.php">
                <input type="text" name="item" placeholder="Item" required><br>
                <input type="number" step="0.01" name="price" placeholder="Price" required><br>
                <input type="date" name="date" required><br>
                <textarea name="reason" placeholder="Reason"></textarea><br>
                <button type="submit">Add Expense</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
