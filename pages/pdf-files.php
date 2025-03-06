<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch PDFs from the database
$sql = "SELECT * FROM pdf ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>PDF Files</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>

<div class="container">
    <h2>PDF Files</h2>

    <div class="pdf-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="pdf-card">
                <!-- PDF Preview -->
                <embed src="../assets/pdf/<?php echo $row['pdf_location']; ?>#toolbar=0" type="application/pdf" class="pdf-preview">

                <!-- PDF Details -->
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>

                <!-- Actions -->
                <div class="pdf-actions">
                    <a href="../assets/pdf/<?php echo $row['pdf_location']; ?>" target="_blank" class="view-btn">View</a>
                    <a href="../assets/pdf/<?php echo $row['pdf_location']; ?>" download class="download-btn">Download</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
    }

    .pdf-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        justify-content: center;
    }

    .pdf-card {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: relative;
    }

    .pdf-preview {
        width: 100%;
        height: 200px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .pdf-card h3 {
        margin: 10px 0;
        font-size: 18px;
    }

    .pdf-card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
    }

    .pdf-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .view-btn, .download-btn {
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: white;
        font-size: 14px;
        transition: 0.3s;
    }

    .view-btn { background-color: #007BFF; }
    .download-btn { background-color: #28A745; }

    .view-btn:hover { background-color: #0056b3; }
    .download-btn:hover { background-color: #1e7e34; }
</style>

</body>
</html>
