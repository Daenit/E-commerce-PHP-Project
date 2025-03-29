<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Foody - Organic Food Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Lora:wght@600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

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
    <div class="container-fluid page-header wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <h1 class="display-3 mb-3 animated slideInDown">Contact Us</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-body" href="#">Pages</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Contact Us</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Start -->
    <?php include "include/contact.php" ?>
    <!-- Contact End -->


    <!-- Google Map Start -->
    <div class="container-xxl px-0 wow fadeIn" data-wow-delay="0.1s" style="margin-bottom: -6px;">
        <iframe class="w-100" style="height: 450px;"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
            frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    <!-- Google Map End -->


    <!-- Footer Start -->
    <?php include "include/footer.php" ?>
    <!-- Footer End -->
</body>

</html>