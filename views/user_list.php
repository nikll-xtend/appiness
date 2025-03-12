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
$users = $user->getAllUsers();
$categories = $category->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div id="header"></div>
    <div id="navbar"></div>

    <div class="container mt-5">
        <h2 class="mb-4">User List</h2>
        <ul class="list-group">
            <?php foreach ($users as $usr): ?>
                <?php
                $userCategories = $user->getUserCategories($usr['id']);
                $userSubcategories = $user->getUserSubcategories($usr['id']);
                $userCategoriesArray = array_column($userCategories, 'category_id');
                $userSubcategoriesArray = [];
                foreach ($userSubcategories as $subcat) {
                    $userSubcategoriesArray[$subcat['category_id']][] = $subcat['subcategory_id'];
                }
                ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($usr['name']); ?> (<?php echo htmlspecialchars($usr['email']); ?>)</span>
                        <button class="btn btn-secondary btn-sm" onclick="showAssignCategories(<?php echo $usr['id']; ?>)">Assign Categories</button>
                    </div>
                    <div class="mt-2" id="assign-categories-<?php echo $usr['id']; ?>" style="display: none;">
                        <form action="../actions/category_actions.php" method="POST">
                            <input type="hidden" name="action" value="assign_categories">
                            <input type="hidden" name="user_id" value="<?php echo $usr['id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Categories</label>
                                        <div id="categories-<?php echo $usr['id']; ?>">
                                            <?php foreach ($categories as $cat): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $cat['id']; ?>" id="category-<?php echo $usr['id']; ?>-<?php echo $cat['id']; ?>" <?php echo in_array($cat['id'], $userCategoriesArray) ? 'checked' : ''; ?> onclick="loadSubcategories(<?php echo $usr['id']; ?>, <?php echo $cat['id']; ?>)">
                                                    <label class="form-check-label" for="category-<?php echo $usr['id']; ?>-<?php echo $cat['id']; ?>">
                                                        <?php echo htmlspecialchars($cat['name']); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subcategories</label>
                                        <div id="subcategories-<?php echo $usr['id']; ?>">
                                            <!-- Subcategories will be loaded here dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        async function loadComponent(id, file) {
            const response = await fetch(file);
            document.getElementById(id).innerHTML = await response.text();
        }
        loadComponent("header", "components/header.php");
        loadComponent("navbar", "components/navbar.php");

        loadComponent("footer", "components/footer.php");

        async function showAssignCategories(userId) {
            const assignCategoriesDiv = document.getElementById(`assign-categories-${userId}`);
            if (assignCategoriesDiv.style.display === "none") {
                assignCategoriesDiv.style.display = "block";
            } else {
                assignCategoriesDiv.style.display = "none";
            }
        }

        async function loadSubcategories(userId, categoryId) {
            const subcategoriesDiv = document.getElementById(`subcategories-${userId}`);
            const response = await fetch(`../actions/category_actions.php?action=get_subcategories&category_id=${categoryId}`);
            const subcategories = await response.json();
            const userSubcategories = <?php echo json_encode($userSubcategoriesArray); ?>;
            subcategoriesDiv.innerHTML = subcategories.map(subcat => `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subcategories[${categoryId}][]" value="${subcat.id}" id="subcategory-${userId}-${subcat.id}" ${userSubcategories[categoryId] && userSubcategories[categoryId].includes(subcat.id) ? 'checked' : ''}>
                    <label class="form-check-label" for="subcategory-${userId}-${subcat.id}">
                        ${subcat.name}
                    </label>
                </div>
            `).join('');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>