<?php

    require_once "db.php";
    class Cart 
    {
        private $conn;
        private $table = 'cart';

        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Add or update product in the cart
        public function addToCart($userId, $productId, $quantity, $price, $image)
        {
            // Check if the product is already in the cart
            $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Update quantity if product is already in the cart
                $query = "UPDATE " . $this->table . " SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':product_id', $productId);
                $stmt->bindParam(':quantity', $quantity);
                return $stmt->execute();
            } else {
                // Add new product to cart
                $query = "INSERT INTO " . $this->table . " (user_id, product_id, quantity, price, image) VALUES (:user_id, :product_id, :quantity, :price, :image)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':product_id', $productId);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image', $image);
                return $stmt->execute();
            }
        }

        // Update product quantity
        public function updateQuantity($userId, $productId, $quantity)
        {
            $query = "UPDATE " . $this->table . " SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }

        // Remove product from cart
        public function removeFromCart($userId, $productId)
        {
            $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            return $stmt->execute();
        }

        public function getCartItems($userId) {
            $query = "SELECT cart.*, products.name 
                      FROM cart 
                      JOIN products ON cart.product_id = products.id 
                      WHERE cart.user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }
?>
