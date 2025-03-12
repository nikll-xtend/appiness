<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include_once "../config.php";
include_once "../classes/Category.php";

$category = new Category($conn);
$categories = $category->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div id="header"></div>
    <div id="navbar"></div>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Categories</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form action="../actions/category_actions.php" method="POST">
                    <input type="hidden" name="action" value="add_category">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>

        <h3 class="mb-4">Existing Categories</h3>
        <ul class="list-group">
            <?php foreach ($categories as $cat): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="../actions/category_actions.php" method="POST" class="d-inline-flex align-items-center">
                            <input type="hidden" name="action" value="update_category">
                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                            <div class="input-group">
                                <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($cat['name']); ?>" required>
                                <button type="submit" class="btn btn-primary btn-sm ms-2">Update</button>
                            </div>
                        </form>
                        <div>
                            <form action="../actions/category_actions.php" method="POST" class="d-inline">
                                <input type="hidden" name="action" value="delete_category">
                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <button class="btn btn-secondary btn-sm ms-2" onclick="showSubcategories(<?php echo $cat['id']; ?>)">Manage Subcategories</button>
                        </div>
                    </div>
                    <ul class="list-group mt-2" id="subcategories-<?php echo $cat['id']; ?>" style="display: none;">
                        <!-- Subcategories will be loaded here dynamically -->
                    </ul>
                    <form action="../actions/category_actions.php" method="POST" class="mt-2">
                        <input type="hidden" name="action" value="add_subcategory">
                        <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                        <div class="input-group">
                            <input type="text" class="form-control" name="subcategory" placeholder="New Subcategory" required>
                            <button type="submit" class="btn btn-primary ms-2">Add</button>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
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
        
        async function showSubcategories(categoryId) {
            const subcategoriesList = document.getElementById(`subcategories-${categoryId}`);
            if (subcategoriesList.style.display === "none") {
                const response = await fetch(`../actions/category_actions.php?action=get_subcategories&category_id=${categoryId}`);
                const subcategories = await response.json();
                subcategoriesList.innerHTML = subcategories.map(subcat => `
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="../actions/category_actions.php" method="POST" class="d-inline-flex align-items-center">
                                <input type="hidden" name="action" value="update_subcategory">
                                <input type="hidden" name="id" value="${subcat.id}">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="subcategory" value="${subcat.name}" required>
                                    <button type="submit" class="btn btn-primary btn-sm ms-2">Update</button>
                                </div>
                            </form>
                            <form action="../actions/category_actions.php" method="POST" class="d-inline">
                                <input type="hidden" name="action" value="delete_subcategory">
                                <input type="hidden" name="id" value="${subcat.id}">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </li>
                `).join('');
                subcategoriesList.style.display = "block";
            } else {
                subcategoriesList.style.display = "none";
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>