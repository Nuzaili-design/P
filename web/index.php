<?php
// Check for URL parameters and handle corresponding alerts
if (isset($_GET['Failed'])) {
    echo "<script>alert('Incorrect id and password');</script>";
}
if (isset($_GET['AlreadyUsed'])) {
    echo "<script>alert('Ticket Already Used');</script>";
}
if (isset($_GET['Invalid'])) {
    echo "<script>alert('Invalid Ticket');</script>";
}
if (isset($_GET['Success'])) {
    echo "<script>alert('Login Success');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>QR Code-based Smart Vehicle Parking Management System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
   
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

   <!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fas fa-parking me-3"></i>Car Reservation System</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.php" class="nav-item nav-link active "><i class="fas fa-home me-1"></i>Home</a>
            <a href="Administrator.php" class="nav-item nav-link"><i class="fas fa-user-shield me-1"></i>Administrator</a>
            <a href="TicketChecker.php" class="nav-item nav-link"><i class="fas fa-qrcode me-1"></i>Ticket Checker</a>
            <a href="Users.php" class="nav-item nav-link"><i class="fas fa-users me-1 "></i>Users</a>
        </div>
    </div>
</nav>
<!-- Navbar End -->


    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- First Image -->
                <div class="carousel-item active">
                    <img class="w-100" src="img/parkk.jpg" alt="Image">
                    <div class="carousel-caption d-flex align-items-center">
                        <div class="container">
                            <div class="row align-items-center justify-content-center justify-content-lg-start">
                                <div class="col-10 col-lg-7 text-center text-lg-start">
                                    <h2 class="text-white text-uppercase mb-3 animated slideInDown">fast and reliable way of car parking</h2>
                                    <p class="text-white mb-4 animated slideInDown">Our smart parking system ensures a smooth experience, reducing wait times and maximizing efficiency for drivers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second Image -->
                <div class="carousel-item">
                    <img class="w-100" src="img/pexels-k-howard-2220292.jpg" alt="Image">
                    <div class="carousel-caption d-flex align-items-center">
                        <div class="container">
                            <div class="row align-items-center justify-content-center justify-content-lg-start">
                                <div class="col-10 col-lg-7 text-center text-lg-start">
                                    <h2 class="text-white text-uppercase mb-3 animated slideInDown">Parking Made Simple, Just for You</h2>
                                    <p class="text-white mb-4 animated slideInDown">Say goodbye to stressful parking! Our reliable service helps you find and secure a spot in no time.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Third Image  -->
                <div class="carousel-item">
                    <img class="w-100" src="img/egoh.jpg" alt="Image">
                    <div class="carousel-caption d-flex align-items-center">
                        <div class="container">
                            <div class="row align-items-center justify-content-center justify-content-lg-start">
                                <div class="col-10 col-lg-7 text-center text-lg-start">
                                    <h2 class="text-white text-uppercase mb-3 animated slideInDown">Simplifying Your Parking Experience</h2>
                                    <p class="text-white mb-4 animated slideInDown">With our intuitive platform, you'll enjoy easy access to parking spaces, reducing waiting times and offering a seamless service.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">

            <!-- Easy Reservation -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="d-flex py-5 px-4">
                    <i class="fa fa-calendar-check fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Quick Slot Booking</h5>
                        <p>Reserve your parking spot in just a few clicks — fast, easy, and secure.</p>
                    </div>
                </div>
            </div>

            <!-- Ticket Scanner -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="d-flex bg-light py-5 px-4">
                    <i class="fa fa-qrcode fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Smart Ticket Scanning</h5>
                        <p>Check your ticket via QR code on arrival for quick and contactless entry.</p>
                    </div>
                </div>
            </div>

            <!-- Affordable Pricing -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="d-flex py-5 px-4">
                    <i class="fa fa-tags fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Affordable Pricing</h5>
                        <p>Enjoy competitive rates with transparent pricing — no hidden fees.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Service End -->


<!-- Footer Start -->
<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">

            <!-- About -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-light mb-4">About Us</h5>
                <p>Our smart vehicle parking system lets you book slots easily, scan tickets on arrival, and save time and money. Simple, secure, and stress-free parking.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-light mb-4">Quick Links</h5>
                <a class="btn btn-link text-light" href="index.php">Home</a><br>
                
                <a class="btn btn-link text-light" href="Users.php">User Login</a>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-light mb-4">Contact Us</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i> Istanbul, Turkey</p>
                <p><i class="fa fa-envelope me-3"></i> support@smartparking.com</p>
                <p><i class="fa fa-phone-alt me-3"></i> +90 538 081 40 62</p>
            </div>

            <!-- Newsletter / Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-light mb-4">Stay Updated</h5>
                <p>Sign up to get the latest updates on available slots, promotions, and system upgrades.</p>
                <!-- You can add a newsletter form here if needed -->
            </div>

        </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="container">
    <div class="copyright">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="text-light">
                    &copy; Car Reservation System. All rights reserved.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-light">
                    Developed by: <strong>Abdulaziz Alnuzaili</strong> and <strong>Mohammed Fateh</strong>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>