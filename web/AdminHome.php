<?php
session_start();

// ✅ Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: Administrator.php?AccessDenied");
    exit();
}
// Checking for request parameters and triggering appropriate JavaScript alerts
if (isset($_GET['Failed'])) {
    echo "<script>alert('Incorrect ID and password');</script>";
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
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="AdminHome.php" class="nav-item nav-link active">Home</a>
                <a href="UserDetails.php" class="nav-item nav-link">User Details</a>
                <a href="ParkingCost.php" class="nav-item nav-link">Parking Cost</a>
                <a href="Bookings.php" class="nav-item nav-link">Bookings</a>
                <a href="ParkingLogs1.php" class="nav-item nav-link">Parking Logs</a>
                <a href="logout.php" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Page Header Start 
    <div class="container-fluid page-header mb-5 p-0">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <nav aria-label="breadcrumb"></nav>
            </div>
        </div>
    </div>
     -->

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="text-primary text-uppercase">// Administrator Home //</h3>
                <br><br>
            </div>
            <div class="row g-4">
                <img src="img/pexels-jose-espinal-1000633.jpg" class="img-fluid">
            </div>
            <br><br><br><br><br>
        </div>
    </div>
    <!-- Contact End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6"></div>
                <div class="col-lg-3 col-md-6"></div>
                <div class="col-lg-3 col-md-6"></div>
                <div class="col-lg-3 col-md-6"></div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <a class="border-bottom" href="#">QR Code-based Smart Vehicle Parking Management System</a>
                    </div>
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
