<?php
class Category
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllCategories()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categories");
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function getAllSubcategories()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM subcategories");
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addCategory($name)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $name)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getSubcategories($categoryId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM subcategories WHERE category_id = ?");
            $stmt->bind_param("i", $categoryId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addSubcategory($categoryId, $name)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO subcategories (category_id, name) VALUES (?, ?)");
            $stmt->bind_param("is", $categoryId, $name);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateSubcategory($id, $name)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE subcategories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteSubcategory($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM subcategories WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>