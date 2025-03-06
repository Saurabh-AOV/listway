<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle search and date filters
$search_contact = isset($_GET['search_contact']) ? $conn->real_escape_string($_GET['search_contact']) : '';
$start_date = isset($_GET['start_date']) ? $conn->real_escape_string($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? $conn->real_escape_string($_GET['end_date']) : '';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Construct the SQL query with filters
$sql = "SELECT sales.*, contacts.name as contact_name 
        FROM sales 
        JOIN contacts ON sales.contact_id = contacts.id 
        WHERE sales.user_id='$user_id'";

if ($search_contact) {
    $sql .= " AND contacts.name LIKE '%$search_contact%'";
}

if ($start_date && $end_date) {
    $sql .= " AND sales.date BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $sql .= " AND sales.date >= '$start_date'";
} elseif ($end_date) {
    $sql .= " AND sales.date <= '$end_date'";
}

$sql .= " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Count total records for pagination
$sql_count = "SELECT COUNT(*) as total FROM sales WHERE user_id='$user_id'";
$count_result = $conn->query($sql_count);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="date"], button {
            padding: 10px;
            margin-right: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .pagination {
            text-align: center;
        }

        .pagination a {
            color: #3498db;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination strong {
            padding: 8px 16px;
            margin: 0 4px;
            background-color: #3498db;
            color: white;
            border: 1px solid #ddd;
        }

        @media (max-width: 600px) {
            .header {
                flex-direction: column;
            }

            form {
                flex-direction: column;
            }

            input[type="text"], input[type="date"], button {
                margin-bottom: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="daily-work.php" class="button">Back</a>
            <h2>Sales</h2>
            <a href="add-sell.php" class="button">Add Sell</a>
        </div>
        <form method="get" action="sales.php">
            <input type="text" name="search_contact" placeholder="Search by contact" value="<?php echo htmlspecialchars($search_contact); ?>">
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <button type="submit">Filter</button>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Profit</th>
                        <th>Date</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['profit']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_name']); ?></td>
                        <td>
                            <a href="edit-sell.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete-sell.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="sales.php?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $page): ?>
                    <strong><?php echo $i; ?></strong>
                <?php else: ?>
                    <a href="sales.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="sales.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
