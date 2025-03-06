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
$sql_contacts = "SELECT id, name FROM contacts WHERE user_id='$user_id'";
$result_contacts = $conn->query($sql_contacts);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $profit = floatval($_POST['profit']);
    $date = $conn->real_escape_string($_POST['date']);
    $contact_id = intval($_POST['contact_id']);
    $total_price = $quantity * $price;

    $sql = "INSERT INTO sales (user_id, contact_id, item_name, quantity, price, total_price, date, profit) 
            VALUES ('$user_id', '$contact_id', '$item_name', '$quantity', '$price', '$total_price', '$date', '$profit')";

    if ($conn->query($sql) === TRUE) {
        header("Location: sales.php"); // Redirect to a sales listing page
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
    <title>Add Sell</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script>
        function calculateTotal() {
            var quantity = document.getElementById('quantity').value;
            var price = document.getElementById('price').value;
            var total = quantity * price;
            document.getElementById('total_price').innerText = total.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="sales.php" class="back-button">&#8592; Back</a>
        </div>
        <div class="form-container">
            <h2>Add Sell</h2>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post" action="add-sell.php">
                <input type="text" name="item_name" placeholder="Item Name" required><br>
                <input type="number" id="quantity" name="quantity" placeholder="Quantity" required oninput="calculateTotal()"><br>
                <input type="number" step="0.01" id="price" name="price" placeholder="Price per item" required oninput="calculateTotal()"><br>
                <p>Total Price: ₹<span id="total_price">0.00</span></p>
                <input type="number" step="0.01" id="profit" name="profit" placeholder="Profit" required><br>
                <input type="date" name="date" required><br>
                <select name="contact_id" required>
                    <option value="">Select Contact</option>
                    <?php
                    if ($result_contacts->num_rows > 0) {
                        while ($row = $result_contacts->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No contacts found</option>";
                    }
                    ?>
                </select><br>
                <button type="submit">Add Sell</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
