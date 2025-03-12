<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel - Manage Categories</h2>
    <form action="../actions/admin.php" method="POST">
        <input type="text" name="category" required placeholder="Category Name">
        <button type="submit">Add</button>
    </form>
</body>
</html>
