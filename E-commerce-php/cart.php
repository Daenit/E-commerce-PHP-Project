<?php
    require_once "include/head.php";
    session_start();
    require_once "admin/db.php";
    require_once "admin/Product.php";
    require_once "admin/Cart.php"; 

    $database = new Database();
    $db = $database->getConnection();
    $product = new Product($db);
    $cart = new Cart($db);

    $userId = $_SESSION['user_id'] ?? 0;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
        $id = $_POST['product_id'];
        $productDetails = $product->readOne($id);

        if (!$productDetails) {
            die("<div class='container py-5'><h3 class='text-danger'>Invalid product selected.</h3></div>");
        }

        $cart->addToCart($userId, $id, 1, $productDetails['price'], $productDetails['image']);
    }

    if (isset($_POST['update_quantity']) && isset($_POST['product_id'])) {
        $id = $_POST['product_id'];
        $action = $_POST['update_quantity']; 

        $cartItems = $cart->getCartItems($userId);
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $id) {
                $newQuantity = ($action === 'increase') ? $item['quantity'] + 1 : $item['quantity'] - 1;
                
                if ($newQuantity > 0) {
                    $cart->updateQuantity($userId, $id, $newQuantity);
                } else {
                    $cart->removeFromCart($userId, $id);
                }
            }
        }
    }

    if (isset($_POST['remove_product']) && isset($_POST['product_id'])) {
        $id = $_POST['product_id'];
        $cart->removeFromCart($userId, $id);
    }

    $cartItems = $cart->getCartItems($userId);

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $tax = $subtotal * 0.1; // For example, 10% tax
    $total = $subtotal + $tax;
?>


<!DOCTYPE html>
<html lang="en">
    <?php include "include/head.php" ?>

<body>

    <style>
        .input-group {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .input-group .btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    </style>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <?php include "include/navber.php" ?>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <h1 class="display-3 mb-3 animated slideInDown">Cart</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-body" href="#">Pages</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Product Start -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if (!empty($cartItems)): ?>
                    <form method="post">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="admin/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" width="50" class="img-fluid" alt="Image">
                                        </td>
                                        <td class="fw-bold"><?= htmlspecialchars($item['name'] ?? 'Unknown Product') ?></td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>
                                            <div class="input-group mx-auto" style="max-width: 150px;">
                                                <button type="submit" class="btn btn-outline-danger" name="update_quantity" value="decrease">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                                <button type="submit" class="btn btn-outline-success" name="update_quantity" value="increase">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        </td>
                                        <td class="fw-bold">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                        <td>
                                            <button type="submit" name="remove_product" value="remove" class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Cart Totals -->
                    <div class="text-end mt-4">
                        <h4>Subtotal: <span class="fw-bold">$<?= number_format($subtotal, 2) ?></span></h4>
                        <h4>Tax (10%): <span class="fw-bold">$<?= number_format($tax, 2) ?></span></h4>
                        <h4>Total: <span class="fw-bold">$<?= number_format($total, 2) ?></span></h4>
                        <a href="checkout.php" class="btn btn-success mt-3">🛍 Proceed to Checkout</a>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <h4 class="text-muted">Your cart is empty <i class="fas fa-cart-plus"></i></h4>
                        <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Product End -->

    <!-- Footer Start -->
    <?php include "include/footer.php" ?>
    <!-- Footer End -->
</body>

</html>
