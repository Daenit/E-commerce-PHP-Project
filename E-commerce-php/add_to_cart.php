<?php
require_once "admin/db.php";
require_once "admin/Product.php";
require_once "admin/Category.php";

// Assuming user is logged in, and their ID is available in a session or cookie
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

if (isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
    $product_id = $_POST['product_id'];
    $quantity = 1; // Default to 1, but you could add a quantity field in the form if needed
    
    $added = $product->addToCart($user_id, $product_id, $quantity);
    if ($added) {
        header("Location: cart.php"); // Redirect to cart page after adding to cart
    } else {
        echo "Error adding to cart.";
    }
}
?>
