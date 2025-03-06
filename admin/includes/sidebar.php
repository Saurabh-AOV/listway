<div class="sidebar" id="sidebar">
    <a href="dashboard.php">Dashboard</a>
    <a href="manage-users.php">Manage Users</a>
    <a href="manage-content.php">Manage Content</a>
    <a href="/listway-file/admin/pages/pdf/list.php">PDF</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<style>
.sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #111;
    padding-top: 20px;
    display: flex;
    flex-direction: column;
}

.sidebar a {
    padding: 15px;
    text-decoration: none;
    font-size: 18px;
    color: white;
    display: block;
}

.sidebar a:hover {
    background-color: #575757;
}

@media (max-width: 600px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .sidebar a {
        float: left;
    }
}
</style>
