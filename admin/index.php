<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: pages/admin-login.php");
    exit();
} else {
    header("Location: pages/dashboard.php");
    exit();
}
?>
