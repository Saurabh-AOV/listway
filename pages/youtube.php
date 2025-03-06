<?php
session_start();
include '../includes/db_connect.php';

// Fetch YouTube videos from the database
$sql = "SELECT * FROM youtube";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>YouTube Videos</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .video {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align:center;
        }

        .video img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .video h3 {
            margin: 15px 0 10px;
        }

        .video p {
            margin: 10px 0;
        }

        .video a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .video a:hover {
            background-color: #2980b9;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>YouTube Videos</h2>
        <div class="grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="video">
                    <img src="../admin/uploads/<?php echo htmlspecialchars($row['banner']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($row['video_url']); ?>" target="_blank">Watch Now</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
