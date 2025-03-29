<?php
class Slideshow {
    private $conn;
    private $table = "slideshows";

    public $id;
    public $name;
    public $image;
    public $text;
    public $button;
    public $services;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new slideshow entry
    public function create() {
        $sql = "INSERT INTO " . $this->table . " (name, image, text, button, services) 
                VALUES (:name, :image, :text, :button, :services)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":button", $this->button);
        $stmt->bindParam(":services", $this->services);

        return $stmt->execute();
    }

    // Read all slideshows
    public function readAll() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        return $this->conn->query($sql);
    }

    // Get a single slideshow by ID
    public function getSlideshowById() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a slideshow
    public function update() {
        $sql = "UPDATE " . $this->table . " 
                SET name = :name, image = :image, text = :text, button = :button, services = :services 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":button", $this->button);
        $stmt->bindParam(":services", $this->services);

        return $stmt->execute();
    }

    // Delete a slideshow
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
