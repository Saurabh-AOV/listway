<?php
session_start();
include '../includes/db_connect.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['name'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $city_village = $conn->real_escape_string($_POST['city_village']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, phone, city_village, occupation, password) VALUES ('$name', '$phone', '$city_village', '$occupation', '$password')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['name'] = $name;
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <div class="register-form">
        <h2>Register</h2>
        <form method="post" action="register.php">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="text" name="phone" placeholder="Phone" required><br>
            <input type="text" name="city_village" placeholder="City/Village" required><br>
            <input type="text" name="occupation" placeholder="Occupation" required><br>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()">Show</span>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
