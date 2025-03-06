<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to calculate total sales, profit, and expenses for a given date range
function calculate_totals($conn, $user_id, $start_date, $end_date) {
    $totals = [
        'sell' => 0,
        'profit' => 0,
        'expense' => 0
    ];

    // Calculate total sales and profit
    $sql_sales = "SELECT SUM(total_price) as total_sell, SUM(profit) as total_profit FROM sales 
                  WHERE user_id='$user_id' AND date BETWEEN '$start_date' AND '$end_date'";
    $result_sales = $conn->query($sql_sales);
    if ($result_sales->num_rows > 0) {
        $row_sales = $result_sales->fetch_assoc();
        $totals['sell'] = $row_sales['total_sell'] ?? 0;
        $totals['profit'] = $row_sales['total_profit'] ?? 0;
    }

    // Calculate total expenses
    $sql_expenses = "SELECT SUM(price) as total_expense FROM expenses 
                     WHERE user_id='$user_id' AND date BETWEEN '$start_date' AND '$end_date'";
    $result_expenses = $conn->query($sql_expenses);
    if ($result_expenses->num_rows > 0) {
        $row_expenses = $result_expenses->fetch_assoc();
        $totals['expense'] = $row_expenses['total_expense'] ?? 0;
    }

    return $totals;
}

// Dates for today, yesterday, and this month
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$start_of_month = date('Y-m-01');

// Calculate totals for today, yesterday, this month, and all time
$totals_today = calculate_totals($conn, $user_id, $today, $today);
$totals_yesterday = calculate_totals($conn, $user_id, $yesterday, $yesterday);
$totals_month = calculate_totals($conn, $user_id, $start_of_month, $today);
$totals_all_time = calculate_totals($conn, $user_id, '1970-01-01', $today);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Work</title>
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .box {
            background-color: #f2f2f2;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .box h3 {
            margin-bottom: 10px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-bottom:60px;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daily Work Summary</h2>
        <div class="grid">
            <div class="box">
                <h3>Today</h3>
                <p>Sell: ₹<?php echo number_format($totals_today['sell'], 2); ?></p>
                <p>Profit: ₹<?php echo number_format($totals_today['profit'], 2); ?></p>
                <p>Expense: ₹<?php echo number_format($totals_today['expense'], 2); ?></p>
            </div>
            <div class="box">
                <h3>Yesterday</h3>
                <p>Sell: ₹<?php echo number_format($totals_yesterday['sell'], 2); ?></p>
                <p>Profit: ₹<?php echo number_format($totals_yesterday['profit'], 2); ?></p>
                <p>Expense: ₹<?php echo number_format($totals_yesterday['expense'], 2); ?></p>
            </div>
            <div class="box">
                <h3>This Month</h3>
                <p>Sell: ₹<?php echo number_format($totals_month['sell'], 2); ?></p>
                <p>Profit: ₹<?php echo number_format($totals_month['profit'], 2); ?></p>
                <p>Expense: ₹<?php echo number_format($totals_month['expense'], 2); ?></p>
            </div>
            <div class="box">
                <h3>Total</h3>
                <p>Sell: ₹<?php echo number_format($totals_all_time['sell'], 2); ?></p>
                <p>Profit: ₹<?php echo number_format($totals_all_time['profit'], 2); ?></p>
                <p>Expense: ₹<?php echo number_format($totals_all_time['expense'], 2); ?></p>
            </div>
        </div>
        <div class="buttons">
            <a href="sales.php" class="button">Manage Sell</a>
            <a href="expenses.php" class="button">Manage Expense</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
