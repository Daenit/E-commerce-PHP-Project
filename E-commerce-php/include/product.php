<?php
    require_once "admin/db.php";
    require_once "admin/Product.php";
    require_once "admin/Category.php";
    require_once "admin/Cart.php";

    $database = new Database();
    $db = $database->getConnection();
    $product = new Product($db);
    $category = new Category($db);
    $cart = new Cart($db);

    // Fetch all categories
    $categories = $category->readAll();

    if (isset($_GET['add_to_cart'])) {
        if (!isset($_SESSION['user_id'])) {
            echo "Please log in to add items to the cart.";
            exit;
        }

        $userId = $_SESSION['user_id']; 
        $productId = $_GET['product_id'];
        $quantity = 1; // Default quantity

        $cart->addToCart($productId, $userId, $quantity);
    }
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                    <h1 class="display-5 mb-3">Our Products</h1>
                    <p>Tempor ut dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed rebum vero dolor duo.</p>
                </div>
            </div>
            <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                    <?php 
                        $first = true; while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-primary border-2 <?= $first ? 'active' : '' ?>" data-bs-toggle="pill" href="#tab-<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['name']) ?>
                            </a>
                        </li>
                    <?php $first = false; endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <?php
                $categories->execute(); // Reset cursor for second loop
                $first = true;
                while ($categoryRow = $categories->fetch(PDO::FETCH_ASSOC)): 
                    // Fetch products by category
                    $products = $product->readAll($categoryRow['id']);
            ?>
            <div id="tab-<?= $categoryRow['id'] ?>" class="tab-pane fade show p-0 <?= $first ? 'active' : '' ?>">
                <div class="row g-4">
                    <?php while ($productRow = $products->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="product-item">
                                <div class="position-relative bg-light overflow-hidden">
                                    <img class="img-fluid w-100" src="admin/<?= htmlspecialchars($productRow['image']) ?>" alt="<?= htmlspecialchars($productRow['name']) ?>">
                                    <div class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">New</div>
                                </div>
                                <div class="text-center p-4">
                                    <a class="d-block h5 mb-2" href=""><?= htmlspecialchars($productRow['name']) ?></a>
                                    <span class="text-primary me-1"><?= htmlspecialchars($productRow['price']) ?> USD</span>
                                    <span class="text-body text-decoration-line-through"><?= htmlspecialchars($productRow['price']) ?> USD</span>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="w-50 text-center border-end py-2">
                                        <a class="text-body" href="product_detail.php?id=<?= $productRow['id'] ?>">
                                            <i class="fa fa-eye text-primary me-2"></i>View detail
                                        </a>
                                    </small>
                                    <small class="w-50 text-center py-2">
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="product_id" value="<?= $productRow['id'] ?>">
                                            <button type="submit" class="btn text-body"><i class="fa fa-shopping-bag text-primary me-2"></i>Add to cart</button>
                                        </form>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="">Browse More Products</a>
                    </div>
                </div>
            </div>
            <?php $first = false; endwhile; ?>
        </div>
    </div>
</div>
