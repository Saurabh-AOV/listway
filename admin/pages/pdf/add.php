<?php
$msg = "";

include '../../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $upload_dir = "../../../assets/pdf/";

    if (!empty($_FILES['pdf_file']['name'])) {
        $file_name = basename($_FILES['pdf_file']['name']);
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

        // Only allow PDF files
        if ($file_type != "pdf") {
            $msg = "Only PDF files are allowed!";
        } else {
            // Check if file already exists in the database
            $check_sql = "SELECT * FROM pdf WHERE pdf_location = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("s", $file_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $msg = "This PDF already exists!";
            } else {
                $file_path = $upload_dir . $file_name;

                // Move uploaded file
                if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $file_path)) {
                    $sql = "INSERT INTO pdf (title, description, pdf_location) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $title, $description, $file_name);

                    if ($stmt->execute()) {
                        $msg = "PDF uploaded successfully!";
                    } else {
                        $msg = "Error: " . $conn->error;
                    }
                } else {
                    $msg = "File upload failed!";
                }
            }
        }
    } else {
        $msg = "Please select a PDF file!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add PDF</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/admin-styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input,
        textarea {
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
<?php include '../../includes/sidebar.php'; ?>
<?php include '../../includes/header.php'; ?>
<div class="main-content">
    <h2>Upload PDF</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <label>Select PDF File:</label>
        <input type="file" name="pdf_file" accept=".pdf" required>

        <button type="submit">Upload PDF</button>
    </form>

    <p><?php echo $msg; ?></p>
</div>
</body>

</html>