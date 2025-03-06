<div class="header">
    <span class="menu-toggler" onclick="toggleSidebar()">☰</span>
    <h1>Admin Panel</h1>
</div>

<script>
function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    if (sidebar.style.display === "block") {
        sidebar.style.display = "none";
    } else {
        sidebar.style.display = "block";
    }
}
</script>

<style>
.header {
    background-color: #4CAF50;
    color: white;
    padding: 15px;
    text-align: center;
}

.menu-toggler {
    font-size: 30px;
    cursor: pointer;
}

@media (max-width: 600px) {
    .menu-toggler {
        display: inline;
    }
}
</style>
