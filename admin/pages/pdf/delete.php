<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch PDF file name from the database
    $sql = "SELECT pdf_location FROM pdf WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = "../../../assets/pdf/" . $row['pdf_location'];

        // Delete record from database
        $delete_sql = "DELETE FROM pdf WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);

        if ($delete_stmt->execute()) {
            // Remove the file from the server
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            header("Location: list.php?msg=PDF deleted successfully!");
            exit();
        } else {
            header("Location: list.php?msg=Error deleting PDF.");
            exit();
        }
    } else {
        header("Location: list.php?msg=PDF not found.");
        exit();
    }
} else {
    header("Location: list.php?msg=Invalid request.");
    exit();
}
?>
