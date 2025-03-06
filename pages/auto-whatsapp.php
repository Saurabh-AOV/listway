<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user contacts
$user_id = $_SESSION['user_id'];
$sql_contacts = "SELECT id, name, phone FROM contacts WHERE user_id='$user_id'";
$result_contacts = $conn->query($sql_contacts);

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Auto WhatsApp</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body>
    <div class="container">
        <div class="back-button-container">
            <a href="sales.php" class="back-button">&#8592; Back</a>
            <a href="add-sell.php" class="add-button">+ Add Sell</a>
        </div>
        <div class="form-container">
            <h2>Auto WhatsApp</h2>

            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

            <form method="post" action="start-whatsapp.php" enctype="multipart/form-data">
                <select name="contacts[]" multiple required>
                    <option value="">Select Contacts</option>
                    <?php
                    if ($result_contacts->num_rows > 0) {
                        while ($row = $result_contacts->fetch_assoc()) {
                            echo "<option value='" . $row['phone'] . "'>" . $row['name'] . " (" . $row['phone'] . ")</option>";
                        }
                    } else {
                        echo "<option value=''>No contacts found</option>";
                    }
                    ?>
                </select><br>
                <textarea name="message" placeholder="Enter your message" required></textarea><br>
                <input type="file" name="image"><br>
                <input type="number" name="timer" placeholder="Timer in seconds" value="8" min="5" max="100" required><br>
                <button type="submit">Send Messages</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>