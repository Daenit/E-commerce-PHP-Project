<?php
require_once "include/head.php";
session_start();
require_once "admin/db.php";
require_once "admin/Product.php";
require_once "admin/Cart.php";
require_once "admin/Order.php"; // Include the Order class

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$cart = new Cart($db);
$order = new Order($db);

$userId = $_SESSION['user_id'] ?? 0;
$cartItems = $cart->getCartItems($userId);

// Calculate total for the order
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.1; // For example, 10% tax
$total = $subtotal + $tax;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data for billing details
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $companyName = $_POST['company_name'];
    $address = $_POST['address'];
    $stateCountry = $_POST['state_country'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $shipToDifferent = isset($_POST['ship_to_different_address']) ? 1 : 0;
    $shipAddress = $_POST['ship_address'] ?? null;
    $shipStateCountry = $_POST['ship_state_country'] ?? null;
    $orderNotes = $_POST['order_notes'];

    // Create the order
    $orderId = $order->createOrder($userId, $total);
    if ($orderId) {
        // Insert billing details
        $order->createBillingDetails($orderId, $firstName, $lastName, $companyName, $address, $stateCountry, $email, $phone, $shipToDifferent, $shipAddress, $shipStateCountry, $orderNotes);
        
        // Redirect or show a success message
        header("Location: order_confirmation.php?order_id=$orderId");
        exit;
    }
}
?>

<!-- Checkout Form -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4 text-primary">Billing Details</h2>
            <form method="POST" action="checkout.php">
                <div class="form-group">
                    <label for="first_name" class="text-info">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="last_name" class="text-info">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="company_name" class="text-info">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="address" class="text-info">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="state_country" class="text-info">State/Country</label>
                    <input type="text" class="form-control" id="state_country" name="state_country" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="email" class="text-info">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="phone" class="text-info">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="ship_to_different_address" class="text-info">Ship to a different address?</label>
                    <input type="checkbox" id="ship_to_different_address" name="ship_to_different_address">
                </div>
                <div class="form-group">
                    <label for="ship_address" class="text-info">Shipping Address</label>
                    <input type="text" class="form-control" id="ship_address" name="ship_address" style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="ship_state_country" class="text-info">Shipping State/Country</label>
                    <input type="text" class="form-control" id="ship_state_country" name="ship_state_country" style="border-color: #17a2b8;">
                </div>
                <div class="form-group">
                    <label for="order_notes" class="text-warning">Order Notes</label>
                    <textarea class="form-control" id="order_notes" name="order_notes" style="border-color: #ffc107;"></textarea>
                </div>

                <h4 class="mt-4 text-success">Order Summary</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name'] ?? 'Unknown Product') ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h5 class="text-success">Subtotal: $<?= number_format($subtotal, 2) ?></h5>
                <h5 class="text-success">Tax (10%): $<?= number_format($tax, 2) ?></h5>
                <h5 class="text-success">Total: $<?= number_format($total, 2) ?></h5>

                <button type="submit" class="btn btn-danger btn-block mt-3">Place Order</button>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f0f8ff; /* Light Blue Background */
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        background-color: #ffffff; /* White Background */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h2, h4, h5 {
        color:rgb(27, 207, 60); /* Blue Text Color */
    }

    .table {
        background-color: #f9f9f9; /* Light Gray Table Background */
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .text-info {
        color:rgb(12, 13, 12) !important; /* Info Color for Labels */
    }

    .text-warning {
        color: #ffc107 !important; /* Yellow Color for Order Notes */
    }

    .text-success {
        color:rgb(53, 24, 96) !important; /* Green Color for Order Summary */
    }

    .form-control {
        margin-bottom: 10px;
        transition: border-color 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color:rgb(44, 15, 148); /* Blue Border on Focus */
    }

    .form-group label {
        font-weight: bold;
    }

    /* Hover effect on input fields */
    .form-control:hover {
        border-color:rgb(66, 199, 59);
    }

    .table-bordered th, .table-bordered td {
        border-color: #ddd; /* Light gray border for table */
    }

    .table th, .table td {
        text-align: center;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }
</style>
