<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "appiness";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//db structure for table creation -- users, categories, subcategories, user_categories

$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    usertype ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createUsersTable)) {
    die("Error creating users table: " . $conn->error);
}

$createCategoriesTable = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)";

if (!$conn->query($createCategoriesTable)) {
    die("Error creating categories table: " . $conn->error);
}

$createSubcategoriesTable = "CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
)";

if (!$conn->query($createSubcategoriesTable)) {
    die("Error creating subcategories table: " . $conn->error);
}

$createUserCategoriesTable = "CREATE TABLE IF NOT EXISTS user_categories (
    user_id INT,
    category_id INT,
    subcategory_id INT DEFAULT NULL,
    UNIQUE (user_id, category_id, subcategory_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
)";

if (!$conn->query($createUserCategoriesTable)) {
    die("Error creating user_categories table: " . $conn->error);
}
?>