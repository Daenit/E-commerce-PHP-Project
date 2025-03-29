<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $image;
    public $type;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Category
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, image, type) VALUES (:name, :image, :type)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":type", $this->type);
        return $stmt->execute();
    }

    // Read All Categories
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Single Category
    public function getCategoryById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Category
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, image = :image, type = :type WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":type", $this->type);
        return $stmt->execute();
    }

    // Delete Category
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
