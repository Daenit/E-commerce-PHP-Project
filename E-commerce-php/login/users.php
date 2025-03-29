<?php
require_once '../admin/db.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($name, $email, $password, $user_type, $image) {
        if ($this->emailExists($email)) {
            return "Email already exists.";
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $image_path = "uploads/" . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $image_path);

        $query = "INSERT INTO " . $this->table . " (name, email, password, user_type, image) VALUES (:name, :email, :password, :user_type, :image)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":user_type", $user_type);
        $stmt->bindParam(":image", $image_path);

        return $stmt->execute() ? "Registration successful." : "Registration failed.";
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                setcookie("user_id", $user['id'], time() + (86400 * 30), "/");
                setcookie("user_type", $user['user_type'], time() + (86400 * 30), "/");

                return $user['user_type'] === 'admin' ? 'admin' : 'user';
            } else {
                return "Incorrect password.";
            }
        } else {
            return "User not found.";
        }
    }

    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
