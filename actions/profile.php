<?php
include_once "../config.php";
include_once "../classes/User.php";
include_once "../classes/Category.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/login.php");
    exit;
}

$user = new User($conn);
$category = new Category($conn);
$userId = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $photo = "";
    $categories = $_POST['categories'];

    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../uploads/";
        $photo = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $photo;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
        } else {
            $error = $_FILES["photo"]["error"];
            switch ($error) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = "The uploaded file was only partially uploaded.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = "No file was uploaded.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = "Missing a temporary folder.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = "Failed to write file to disk.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = "A PHP extension stopped the file upload.";
                    break;
                default:
                    $message = "Unknown upload error.";
                    break;
            }
            echo "Sorry, there was an error uploading your file: $message";
            exit;
        }
    }

    if ($user->updateProfile($userId, $name, $photo) ) {
        header("Location: ../views/dashboard.php");
    } else {
        echo "Profile update failed!";
    }
} else {
    $userInfo = $user->getUserById($userId);
    $userCategories = $user->getUserCategories($userId);
    $allCategories = $category->getAllCategories();
}
?>