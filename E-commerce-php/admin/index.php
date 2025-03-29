<?php 
	$page = "dashboard.php";
	$p = "dashboard";
	if (isset($_GET['p']))
	{
		$p = $_GET['p'];
		switch($p)
		{
      case "slideshow": $page ="add_slideshow.php";
        break;
      case "products": $page ="add_product.php";
        break;
      case "categories": $page ="add_category.php";
        break;
      case "page": $page ="pages.php";
        break;
      case "setting": $page ="settings.php";
        break;
		}

	}

?>


<!DOCTYPE html>
<html lang="en">
  <?php include "include/head.php" ?>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <?php include "include/logo.php" ?>
          <!-- End Logo Header -->
        </div>
          <?php include "include/header.php" ?>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
            <?php include "include/navbar.php" ?>
          <!-- End Navbar -->
        </div>
          <!-- dashboard start -->
        <?php include "$page" ?>
        <!-- end dashboard -->

        <?php include "include/footer.php" ?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include "include/setting.php" ?>
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <?php include "include/linkjavascript.php" ?>
  </body>
</html>
