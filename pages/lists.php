<?php
session_start();
include '../includes/db_connect.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle deletion of selected contacts
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['contact_ids'])) {
        $contact_ids = implode(",", array_map('intval', $_POST['contact_ids']));
        $sql = "DELETE FROM contacts WHERE id IN ($contact_ids) AND user_id = $user_id";
        if ($conn->query($sql) === TRUE) {
            $message = "Selected contacts deleted successfully.";
        } else {
            $error = "Error deleting contacts: " . $conn->error;
        }
    } else {
        $error = "No contacts selected for deletion.";
    }
}

// Handle edit action
if (isset($_POST['edit_contact_id'])) {
    $edit_id = intval($_POST['edit_contact_id']);
    header("Location: edit_contact.php?id=$edit_id"); // Redirect to edit page
    exit();
}

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT COUNT(*) as total FROM contacts WHERE user_id = $user_id";
$result = $conn->query($sql);
$total_records = $result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

$sql = "SELECT * FROM contacts WHERE user_id = $user_id LIMIT $limit OFFSET $offset";
$contacts = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contacts List</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <style>
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .actions { margin: 20px 0; }
        .actions button { padding: 10px 20px; margin: 0 5px; }
        .pagination { text-align: center; margin-top: 20px; }
        .pagination a { margin: 0 5px; text-decoration: none; color: #333; }
        .pagination a.active { font-weight: bold; }
        .pagination span { margin: 0 5px; }
    </style>
</head>
<body>
    <div class="container" style="padding: 10px;height: 100%;">
    <div class="d-flex">    
        <h2>Your Contacts</h2>
        <a href="add-list.php" class="btn-green">Add List</a>
    </div>

        <!-- Display messages -->
        <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <!-- Form for multiple actions -->
        <form method="post" action="lists.php">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contacts->num_rows > 0): ?>
                        <?php while ($row = $contacts->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="contact_ids[]" value="<?php echo $row['id']; ?>"></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td>
                                    <form method="post" action="lists.php" style="display: inline;">
                                        <input type="hidden" name="edit_contact_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit">Edit</button>
                                    </form>
                                    <form method="post" action="delete_contact.php" style="display: inline;">
                                        <input type="hidden" name="delete_contact_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this contact?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No contacts found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit" name="delete_selected">Delete Selected</button>
            </div>
        </form>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="lists.php?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php
            // Display pagination links with a limited range
            $range = 2; // Number of pages to show around the current page
            for ($i = max(1, $page - $range); $i <= min($total_pages, $page + $range); $i++): ?>
                <a href="lists.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="lists.php?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script>
        document.getElementById('select_all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[name="contact_ids[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
    </script>
</body>
</html>
