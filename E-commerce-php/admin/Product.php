<?php
    require_once "db.php";

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $image;
    public $price;
    public $qty;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, image, price, qty, category_id) 
                  VALUES (:name, :image, :price, :qty, :category_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":qty", $this->qty);
        $stmt->bindParam(":category_id", $this->category_id);

        return $stmt->execute();
    }

    public function readByCategory($category_id) {
        $query = "SELECT p.id, p.name, p.image, p.price, c.name as category_name 
                  FROM " . $this->table_name . " p 
                  JOIN categories c ON p.category_id = c.id 
                  WHERE p.category_id = :category_id 
                  ORDER BY p.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function readAll() {
        $query = "SELECT p.*, c.name AS category_name 
                  FROM " . $this->table_name . " p 
                  INNER JOIN categories c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }



    public function readOne($id) {
        $query = "SELECT p.*, c.name AS category_name 
                  FROM " . $this->table_name . " p 
                  INNER JOIN categories c ON p.category_id = c.id 
                  WHERE p.id = :id 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, image = :image, price = :price, qty = :qty, category_id = :category_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":qty", $this->qty);
        $stmt->bindParam(":category_id", $this->category_id);
    
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
    
    public function getRelatedProducts($category_id, $exclude_id) {
        $query = "SELECT id, name, image, price FROM " . $this->table_name . " 
                WHERE category_id = :category_id AND id != :exclude_id 
                ORDER BY RAND() LIMIT 4";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->bindParam(":exclude_id", $exclude_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    
        public function getProductColors($productId) {
            $query = "SELECT * FROM product_colors WHERE product_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $productId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

}
?>
