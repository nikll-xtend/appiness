<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include_once "../config.php";
include_once "../classes/User.php";
include_once "../classes/Category.php";

$user = new User($conn);
$category = new Category($conn);
$userId = $_SESSION['user'];
$userData = $user->getUserById($userId);
$categories = $category->getAllCategories();
$userCategories = $user->getUserCategories($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div id="header"></div>
    <div id="navbar"></div>

    <div class="container mt-5">
        <h2>Profile</h2>
        <form action="../actions/profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <?php if (!empty($userData['photo'])): ?>
                    <div class="mb-3">
                        <img src="../uploads/images/<?php echo htmlspecialchars($userData['photo']); ?>" alt="Profile Photo" class="img-thumbnail" width="150">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script>
        // Load components dynamically
        async function loadComponent(id, file) {
            const response = await fetch(file);
            document.getElementById(id).innerHTML = await response.text();
        }
        loadComponent("header", "components/header.php");
        loadComponent("navbar", "components/navbar.php");
        loadComponent("footer", "components/footer.php");
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>