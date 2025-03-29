<?php
session_start();
require_once "admin/db.php";
require_once "admin/Cart.php";

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

$userId = $_SESSION['user_id'] ?? 0;
$productId = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if ($userId && $productId && $action) {
    switch ($action) {
        case 'increase':
            $cart->updateQuantity($userId, $productId, 'increase');
            break;
        case 'decrease':
            $cart->updateQuantity($userId, $productId, 'decrease');
            break;
        case 'update':
            $newQuantity = $_POST['quantity'] ?? 1;
            $cart->updateQuantity($userId, $productId, $newQuantity);
            break;
    }

    // Fetch updated cart items and recalculate totals
    $cartItems = $cart->getCartItems($userId);
    $grandTotal = 0;
    foreach ($cartItems as $item) {
        $item['total'] = $item['price'] * $item['quantity'];
        $grandTotal += $item['total'];
    }

    $response = [
        'success' => true,
        'quantity' => $cart->getQuantity($userId, $productId),
        'total' => $cart->getTotal($userId, $productId),
        'grandTotal' => $grandTotal,
    ];

    echo json_encode($response);
} else {
    echo json_encode(['success' => false]);
}
?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateCart(productId, action, quantity = null) {
            let data = `product_id=${productId}&action=${action}`;
            if (quantity) {
                data += `&quantity=${quantity}`;
            }

            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the quantity input and total price for the item
                    document.querySelector(`.quantity-input[data-id="${productId}"]`).value = data.quantity;
                    document.getElementById(`total-${productId}`).innerText = `$${data.total.toFixed(2)}`;
                    document.getElementById("grand-total").innerText = `$${data.grandTotal.toFixed(2)}`;
                }
            });
        }

        // Event listener for the plus and minus buttons
        document.querySelectorAll(".js-btn-minus, .js-btn-plus").forEach(button => {
            button.addEventListener("click", function() {
                let productId = this.getAttribute("data-id");
                let action = this.classList.contains("js-btn-plus") ? "increase" : "decrease";
                updateCart(productId, action);
            });
        });

        // Event listener for manual quantity changes
        document.querySelectorAll(".quantity-input").forEach(input => {
            input.addEventListener("change", function() {
                let productId = this.getAttribute("data-id");
                let newQuantity = parseInt(this.value);
                if (newQuantity > 0) {
                    updateCart(productId, "update", newQuantity);
                }
            });
        });
    });
</script>