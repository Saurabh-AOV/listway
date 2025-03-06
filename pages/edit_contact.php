<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get contact ID from query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lists.php");
    exit();
}

$contact_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch contact details
$sql = "SELECT * FROM contacts WHERE id = $contact_id AND user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    header("Location: lists.php");
    exit();
}

$contact = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $city_village = $conn->real_escape_string($_POST['city_village']);
    $education = $conn->real_escape_string($_POST['education']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $sql = "UPDATE contacts 
            SET name = '$name', city_village = '$city_village', education = '$education', occupation = '$occupation', phone = '$phone'
            WHERE id = $contact_id AND user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: lists.php");
        exit();
    } else {
        $error = "Error updating contact: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Contact</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        .container { padding: 20px; }
        .form-container { max-width: 600px; margin: auto; }
        input[type="text"] { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; }
        button { padding: 10px 20px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container" style="height:100%;">
        <a href="lists.php" class="back-button">&#8592; Back</a>
        <div class="form-container">
            <h2>Edit Contact</h2>

            <!-- Display error message -->
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

            <form method="post" action="edit_contact.php?id=<?php echo $contact_id; ?>">
                <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($contact['name']); ?>" required><br>
                <input type="text" name="city_village" placeholder="City/Village" value="<?php echo htmlspecialchars($contact['city_village']); ?>" required><br>
                <input type="text" name="education" placeholder="Education" value="<?php echo htmlspecialchars($contact['education']); ?>" required><br>
                <input type="text" name="occupation" placeholder="Occupation" value="<?php echo htmlspecialchars($contact['occupation']); ?>" required><br>
                <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required><br>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
