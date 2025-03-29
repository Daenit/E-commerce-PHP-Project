<?php
session_start();
require_once "admin/db.php";
require_once "admin/Product.php";

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();

    // Initialize Product object
    $product = new Product($db);

    // Validate product ID
    $productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$productId || $productId < 1) {
        throw new Exception("Invalid product ID");
    }

    // Get product details
    $productDetails = $product->readOne($productId);
    
    if (!$productDetails) {
        throw new Exception("Product not found");
    }

    // Get related products
    $relatedProducts = $product->getRelatedProducts(
        $productDetails['category_id'],
        $productId
    );

} catch (Exception $e) {
    die("<div class='container py-5'><h3 class='text-danger'>Error: " . $e->getMessage() . "</h3></div>");
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "include/head.php" ?>

    <style>
        .product-image { max-width: 100%; height: auto; }
        .btn-custom { padding: 12px 24px; width: 100%; }
        .stock-status { font-weight: 500; }
        .stock-available { color: #28a745; }
        .stock-out { color: #dc3545; }
    </style>

<body>
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
            <h1 class="display-3 mb-3 animated slideInDown">Products Details</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="category.php?id=<?= $productDetails['category_id'] ?>">
                        <?= htmlspecialchars($productDetails['category_name'] ?? 'Category') ?>
                    </a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($productDetails['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Product Start -->
    <div class="container py-5">

        <!-- Product Details -->
        <div class="row g-5">
            <div class="col-md-6">
                <img src="admin/<?= htmlspecialchars($productDetails['image']) ?>" 
                    alt="<?= htmlspecialchars($productDetails['name']) ?>" 
                    class="product-image rounded shadow">
            </div>
            
            <div class="col-md-6">
                <h1 class="mb-3"><?= htmlspecialchars($productDetails['name']) ?></h1>
                <h3 class="text-primary mb-4">$<?= number_format($productDetails['price'], 2) ?></h3>
                
                <div class="mb-4">
                    <span class="stock-status <?= $productDetails['qty'] > 0 ? 'stock-available' : 'stock-out' ?>">
                        <?= $productDetails['qty'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                    </span>
                </div>

                <p class="lead"><?= htmlspecialchars($productDetails['description'] ?? 'Product description not available') ?></p>

                <form class="mt-4" action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                    <button type="submit" class="btn btn-primary btn-custom" 
                        <?= $productDetails['qty'] > 0 ? '' : 'disabled' ?>>
                        <?= $productDetails['qty'] > 0 ? 'Add to Cart' : 'Temporarily Unavailable' ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Related Products -->
        <?php if ($relatedProducts->rowCount() > 0): ?>
        <section class="mt-5">
            <h3 class="mb-4">Related Products</h3>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php while ($related = $relatedProducts->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="admin/<?= htmlspecialchars($related['image']) ?>" 
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($related['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($related['name']) ?></h5>
                            <p class="card-text text-primary">$<?= number_format($related['price'], 2) ?></p>
                            <a href="product_detail.php?id=<?= $related['id'] ?>" 
                            class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
    <!-- Product End -->


    <!-- Firm Visit Start -->
    <?php include "include/firm.php" ?>
    <!-- Firm Visit End -->


    <!-- Testimonial Start -->
    <?php include "include/tesimonial.php" ?>
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <?php include "include/footer.php" ?>
    <!-- Footer End -->
</body>
</html>

