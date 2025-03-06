<?php
session_start();
include '../includes/db_connect.php';

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['name']) && isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $sql = "SELECT * FROM users WHERE phone='$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['id']; // Set user ID in session

            if ($remember) {
                setcookie("phone", $phone, time() + (86400 * 30), "/"); // 30 days
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid phone or password.";
        }
    } else {
        $error = "Invalid phone or password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <form method="post" action="login.php">
            <input type="text" name="phone" placeholder="Phone" required value="<?php echo isset($_COOKIE['phone']) ? htmlspecialchars($_COOKIE['phone']) : ''; ?>"><br>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()">Show</span>
            </div>
            <input type="checkbox" name="remember" <?php echo isset($_COOKIE['phone']) ? 'checked' : ''; ?>> Remember Me<br>
            <button type="submit">Login</button>
        </form>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
