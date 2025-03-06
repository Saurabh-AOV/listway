<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $city_village = $conn->real_escape_string($_POST['city_village']);
    $education = $conn->real_escape_string($_POST['education']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $phone = $conn->real_escape_string($_POST['dropdown-country-code'] . $_POST['phone']);
    $user_id = $_SESSION['user_id']; // Fetch user_id from session

    if (empty($user_id)) {
        $error = "User ID is not set.";
    } else {
        $sql = "INSERT INTO contacts (user_id, name, city_village, education, occupation, phone)
                VALUES ('$user_id', '$name', '$city_village', '$education', '$occupation', '$phone')";

        if ($conn->query($sql) === TRUE) {
            header("Location: lists.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add List</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        .back-button {
            display: inline-block;
            margin: 10px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-container input,
        .form-container select {
            width: calc(100% - 22px);
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="container" style="height: 100%;">
        <a href="lists.php" class="back-button">&#8592; Back</a>
        <div class="form-container">
            <h2>Add Contact</h2>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post" action="add-list.php">
                <input type="text" name="name" placeholder="Name" required><br>
                <input type="text" name="city_village" placeholder="City/Village" required><br>
                <input type="text" name="education" placeholder="Education" required><br>
                <input type="text" name="occupation" placeholder="Occupation" required><br>
                <div class="d-flex g-2">
                <select name="dropdown-country-code" id="dropdown" class="col-md-2" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="91">+91</option>
                    <option value="91">+91</option>
                    <option value="91">+91</option>
                </select><br>
                <input type="text" name="phone" placeholder="Phone" required><br>
                </div>
                <button type="submit">Add Contact</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>