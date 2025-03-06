<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}

include '../../includes/db_connect.php';

// Pagination
$limit = 10; // PDFs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : "";
$search_query = "";
if (!empty($search)) {
    $search_query = " WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

// Get total records
$count_sql = "SELECT COUNT(*) AS total FROM pdf $search_query";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Fetch records
$sql = "SELECT id, title, description, pdf_location, created_at FROM pdf $search_query ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);  // To prevent XSS vulnerabilities
    echo "<script>alert('$msg');</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/admin-styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body>
    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/header.php'; ?>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between;">
            <h2>Admin PDF List</h2>
            <a href="./add.php" style="text-decoration: none;">Add PDF</a>
        </div>

        <form method="GET">
            <input type="text" name="search" placeholder="Search PDFs..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>PDF</th>
                    <th>Uploaded Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td><a href='../../../assets/pdf/{$row['pdf_location']}' target='_blank'>View PDF</a></td>
                            <td>{$row['created_at']}</td>
                            <td><a href='edit.php?id={$row['id']}'>Edit</a> <a href='delete.php?id={$row['id']}'>Delete</a></td>
                          </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No PDFs found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">« Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">Next »</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>