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
    if (!isset($_POST['contacts']) || empty($_POST['contacts'])) {
        $error = "No contacts selected.";
    } else {
        $delay = $_POST['timer'];
        $contacts = $_POST['contacts'];

            $message = $_POST['message'];


        // Ensure the uploads directory exists
        $upload_dir = './python/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Set CSV file path
        $csv_file = $upload_dir . 'contacts.csv';

        // Check if CSV file exists, then delete it
        if (file_exists($csv_file)) {
            unlink($csv_file);
        }

        // Create a new CSV file
        $file = fopen($csv_file, 'w');

        // Add CSV headers
        fputcsv($file, ['phone', 'msg']);

        // Loop through contacts and write to CSV
        foreach ($contacts as $phone) {
            fputcsv($file, [$phone, "$message"]);
        }

        // Close the file
        fclose($file);

        $delay = (int) $delay; // Convert to integer

        $python_script = escapeshellcmd("\"C:\\Users\\Lenovo\\AppData\\Local\\Programs\\Python\\Python313\\python.exe\" \"C:\\xampp\\htdocs\\listway-file\\pages\\python\\whatsapp.py\" $delay");
        $output = shell_exec($python_script . ' 2>&1'); // Capture output

        $success = "CSV file created successfully: <a href='$csv_file' download>Download CSV</a><br><br>";
        $success .= "Python script executed. Output:<br><pre>$output</pre>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Message Sent</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="auto-whatsapp.php" class="back-button">&#8592; Back</a>
        </div>
        <div class="form-container">
            <h2>Message Sending Report</h2>
            <div>
                <?php echo nl2br($success); ?>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
