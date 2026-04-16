<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function getCategories()
    {
        $query = "SELECT id, name FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $query = "SELECT id, name 
                  FROM " . $this->table_name . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Thêm danh mục
    public function createCategory($name, $useSecurity = true)
    {
        if ($useSecurity) {
            $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
        } else {
            $query = "INSERT INTO " . $this->table_name . " (name) VALUES ('" . $name . "')";
            $stmt = $this->conn->prepare($query);
        }

        return $stmt->execute();
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name, $useSecurity = true)
    {
        if ($useSecurity) {
            $query = "UPDATE " . $this->table_name . " 
                  SET name = :name 
                  WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
        } else {
            $query = "UPDATE " . $this->table_name . " SET name = '" . $name . "' WHERE id = '" . $id . "'";
            $stmt = $this->conn->prepare($query);
        }

        return $stmt->execute();
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>