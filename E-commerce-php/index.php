<?php 
	$p = "index";
	if (isset($_GET['p']))
	{
		$p = $_GET['p'];
		switch($p)
		{
			case "about": $page ="about.php";
                break;
            case "product": $page ="product.php";
                break;
            case "blog": $page ="blog.php";
                break;
            case "contect": $page ="contect.php";
                break;
            case "page": $page ="pages.php";
                break;
            case "add-to-cart": $page ="add_to_cart.php";
                break;
		}
	}

    if (!isset($_COOKIE['user_id']) || $_COOKIE['user_type'] !== 'user') {
        header("Location: login.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">

    <?php include "include/head.php" ?>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <?php include "include/navber.php" ?>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <?php include "include/sidebar.php" ?>
    <!-- Carousel End -->


    <!-- About Start -->
    <?php include "include/about.php" ?>
    <!-- About End -->


    <!-- Feature Start -->
    <?php include "include/feature.php" ?>
    <!-- Feature End -->


    <!-- Product Start -->
    <?php include "include/product.php" ?>
    <!-- Product End -->


    <!-- Firm Visit Start -->
    <?php include "include/firm.php" ?>
    <!-- Firm Visit End -->


    <!-- Testimonial Start -->
    <?php include "include/tesimonial.php" ?>
    <!-- Testimonial End -->


    <!-- Blog Start -->
    <?php include "include/blog.php" ?>
    <!-- Blog End -->


    <!-- Footer Start -->
    <?php include "include/footer.php" ?>
    <!-- Footer End -->
</body>
</html>