<?php
include_once "../config.php";
include_once "../classes/Category.php";
include_once "../classes/User.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/login.php");
    exit;
}

$stmt = $conn->prepare("SELECT usertype FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['usertype'] !== 'admin') {
    header("Location: ../views/dashboard.php");
    exit;
}

$category = new Category($conn);
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'add_category':
                $name = $_POST['category'];
                if ($category->addCategory($name)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to add category!";
                }
                break;

            case 'update_category':
                $id = $_POST['id'];
                $name = $_POST['category'];
                if ($category->updateCategory($id, $name)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to update category!";
                }
                break;

            case 'delete_category':
                $id = $_POST['id'];
                if ($category->deleteCategory($id)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to delete category!";
                }
                break;

            case 'add_subcategory':
                $categoryId = $_POST['category_id'];
                $name = $_POST['subcategory'];
                if ($category->addSubcategory($categoryId, $name)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to add subcategory!";
                }
                break;

            case 'update_subcategory':
                $id = $_POST['id'];
                $name = $_POST['subcategory'];
                if ($category->updateSubcategory($id, $name)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to update subcategory!";
                }
                break;

            case 'delete_subcategory':
                $id = $_POST['id'];
                if ($category->deleteSubcategory($id)) {
                    header("Location: ../views/categories.php");
                } else {
                    echo "Failed to delete subcategory!";
                }
                break;

            case 'assign_categories':
                $userId = $_POST['user_id'];
                $categories = $_POST['categories'] ?? [];
                $subcategories = $_POST['subcategories'] ?? [];
                if ($user->updateUserCategoriesAndSubcategories($userId, $categories, $subcategories)) {
                    header("Location: ../views/user_list.php");
                } else {
                    echo "Failed to assign categories and subcategories!";
                }
                break;
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
            case 'get_subcategories':
                $categoryId = $_GET['category_id'];
                $subcategories = $category->getSubcategories($categoryId);
                echo json_encode($subcategories);
                break;
        }
    }
}
