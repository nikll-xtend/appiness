<?php
include_once __DIR__ . '/../config.php';

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // User Registration
    public function register($email, $password)
    {
        try {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL); 

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }

            $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $usertype = ($row['count'] == 0) ? 'admin' : 'user'; // First user is admin

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (email, password, usertype) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashedPassword, $usertype);

            if (!$stmt->execute()) {
                throw new Exception("Error registering user: " . $stmt->error);
            }

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // User Login
    public function login($email, $password)
    {
        try {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            $stmt = $this->conn->prepare("SELECT id, password, usertype FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user['id'];
                $_SESSION['usertype'] = $user['usertype'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['photo'] = $user['photo'];
                return true;
            }

            throw new Exception("Invalid email or password.");
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Update Profile (Name & Photo)
    public function updateProfile($userId, $name, $photo = null)
    {
        try {
            $name = filter_var($name, FILTER_SANITIZE_STRING);

            if (!empty($photo)) {
                $stmt = $this->conn->prepare("UPDATE users SET name = ?, photo = ? WHERE id = ?");
                $stmt->bind_param("ssi", $name, $photo, $userId);
                $_SESSION['name'] = $name;
                $_SESSION['photo'] = $photo;
            } else {
                $stmt = $this->conn->prepare("UPDATE users SET name = ? WHERE id = ?");
                $stmt->bind_param("si", $name, $userId);
                $_SESSION['name'] = $name;
            }

            if (!$stmt->execute()) {
                throw new Exception("Error updating profile: " . $stmt->error);
            }

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getUserById($userId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT name, photo FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    public function getUsersByType($type)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, photo FROM users WHERE usertype = ?");
            $stmt->bind_param("s", $type);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }



    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: ../views/login.php");
        exit;
    }

    public function getUserCategories($userId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT category_id, subcategory_id FROM user_categories WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getUserSubcategories($userId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT category_id, subcategory_id FROM user_categories WHERE user_id = ? AND subcategory_id IS NOT NULL");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function getAllUsers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, email FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function updateUserCategoriesAndSubcategories($userId, $categories, $subcategories)
    {
        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare("DELETE FROM user_categories WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            $stmt = $this->conn->prepare("INSERT INTO user_categories (user_id, category_id, subcategory_id) VALUES (?, ?, ?)");

            foreach ($categories as $category) {
                if (isset($subcategories[$category])) {
                    foreach ($subcategories[$category] as $subcategory) {
                        $stmt->bind_param("iii", $userId, $category, $subcategory);
                        $stmt->execute();
                    }
                } else {
                    $subcategory = null;
                    $stmt->bind_param("iii", $userId, $category, $subcategory);
                    $stmt->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            return false;
        }
    }
}
