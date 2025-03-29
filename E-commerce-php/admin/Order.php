<?php
class Order {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    // Insert an order
    public function createOrder($userId, $total) {
        $query = "INSERT INTO orders (user_id, total, status) VALUES (:user_id, :total, 'pending')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':total', $total);
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Return the inserted order ID
        }
        return false;
    }

    // Insert billing details
    public function createBillingDetails($orderId, $firstName, $lastName, $companyName, $address, $stateCountry, $email, $phone, $shipToDifferent, $shipAddress, $shipStateCountry, $orderNotes) {
        $query = "INSERT INTO billing_details (order_id, first_name, last_name, company_name, address, state_country, email_address, phone, ship_to_different_address, ship_address, ship_state_country, order_notes)
                  VALUES (:order_id, :first_name, :last_name, :company_name, :address, :state_country, :email_address, :phone, :ship_to_different_address, :ship_address, :ship_state_country, :order_notes)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':state_country', $stateCountry);
        $stmt->bindParam(':email_address', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':ship_to_different_address', $shipToDifferent, PDO::PARAM_BOOL);
        $stmt->bindParam(':ship_address', $shipAddress);
        $stmt->bindParam(':ship_state_country', $shipStateCountry);
        $stmt->bindParam(':order_notes', $orderNotes);
        
        return $stmt->execute();
    }
}

?>