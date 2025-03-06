<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: pages/admin-login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $video_url = $conn->real_escape_string($_POST['video_url']);

    // Handle file upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["banner"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check file size
        if ($_FILES["banner"]["size"] > 500000) {
            $error = "Sorry, your file is too large.";
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        // Check if $error is set to an empty string
        if (empty($error)) {
            if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
                $banner = basename($_FILES["banner"]["name"]);
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $error = "Banner image is required.";
    }

    if (empty($error)) {
        $sql = "INSERT INTO youtube (banner, title, description, video_url) VALUES ('$banner', '$title', '$description', '$video_url')";

        if ($conn->query($sql) === TRUE) {
            $success = "YouTube video added successfully!";
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
    <title>Add YouTube Video</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admin-styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/header.php'; ?>

    <div class="main-content">
        <h2>Add YouTube Video</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post" action="add-youtube.php" enctype="multipart/form-data">
            <label>Banner (600x400 pixels recommended):</label>
            <input type="file" name="banner" required><br>
            <input type="text" name="title" placeholder="Title" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <input type="text" name="video_url" placeholder="YouTube Video ID" required><br>
            <button type="submit">Add Video</button>
        </form>
    </div>

    <style>
    .main-content {
        margin-left: 250px;
        padding: 20px;
    }

    .main-content h2 {
        margin-bottom: 20px;
    }

    .main-content form input, .main-content form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }

    .main-content form button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .main-content form button:hover {
        background-color: #45a049;
    }

    .main-content .error {
        color: red;
    }

    .main-content .success {
        color: green;
    }

    @media (max-width: 600px) {
        .main-content {
            margin-left: 0;
        }
    }
    </style>
</body>
</html>
