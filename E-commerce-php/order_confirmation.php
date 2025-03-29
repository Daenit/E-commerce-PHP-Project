<?php
$orderId = $_GET['order_id'] ?? null;
if ($orderId) {
    // Fetch order details and display confirmation
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Your order has been placed successfully. Order ID: " . htmlspecialchars($orderId) . "</p>";
}
?>
