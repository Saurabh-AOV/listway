<?php
include '../../includes/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: admin_list.php?msg=Invalid request.");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing PDF details
$sql = "SELECT * FROM pdf WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: admin_list.php?msg=PDF not found.");
    exit();
}

$row = $result->fetch_assoc();
$existing_title = $row['title'];
$existing_description = $row['description'];
$existing_pdf = $row['pdf_location'];

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $upload_dir = "../../../assets/pdf/";
    $new_pdf_file = $_FILES['pdf_file']['name'];

    if (!empty($new_pdf_file)) {
        $file_name = basename($new_pdf_file);
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

        // Only allow PDF files
        if ($file_type != "pdf") {
            $msg = "Only PDF files are allowed!";
        } else {
            // Check if the new PDF file already exists
            $check_sql = "SELECT id FROM pdf WHERE pdf_location = ? AND id != ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("si", $file_name, $id);
            $stmt->execute();
            $check_result = $stmt->get_result();

            if ($check_result->num_rows > 0) {
                $msg = "This PDF file already exists!";
            } else {
                $file_path = $upload_dir . $file_name;

                // Move the new file
                if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $file_path)) {
                    // Delete the old PDF file
                    $old_file_path = $upload_dir . $existing_pdf;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                    }

                    // Update database with new PDF file
                    $update_sql = "UPDATE pdf SET title = ?, description = ?, pdf_location = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("sssi", $title, $description, $file_name, $id);
                } else {
                    $msg = "File upload failed!";
                }
            }
        }
    } else {
        // Update database without changing the PDF file
        $update_sql = "UPDATE pdf SET title = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $title, $description, $id);
    }

    // Execute update query
    if (isset($update_sql) && $stmt->execute()) {
        header("Location: list.php?msg=PDF updated successfully!");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PDF</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/admin-styles.css">
</head>

<body>
<?php include '../../includes/sidebar.php'; ?>
<?php include '../../includes/header.php'; ?>
<div class="main-content">
    <h2>Edit PDF</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($existing_title); ?>" required>

        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($existing_description); ?></textarea>

        <label>Current PDF: <a href="../../../assets/pdf/<?php echo $existing_pdf; ?>" target="_blank"><?php echo $existing_pdf; ?></a></label>

        <label>Change PDF (optional):</label>
        <input type="file" name="pdf_file" accept=".pdf">

        <button type="submit">Update PDF</button>
    </form>

    <p><?php echo $msg; ?></p>
</div>
</body>

</html>