<?php
session_start();
require 'db_connect.php'; // Ensure database connection

$conn = SQLConnection::getConnection();

// ✅ Ensure the Ticket Checker is logged in
if (!isset($_SESSION['ticket_checker_logged_in']) || $_SESSION['ticket_checker_logged_in'] !== true) {
    header("Location: TicketChecker.php?AccessDenied");
    exit();
}

// ✅ Show alert messages
if (isset($_GET['Failed'])) echo "<script>alert('❌ Incorrect ID or Password');</script>";
if (isset($_GET['AlreadyUsed'])) echo "<script>alert('⚠️ Ticket Already Used Or Expired');</script>";
if (isset($_GET['Invalid'])) echo "<script>alert('❌ Invalid Ticket');</script>";
if (isset($_GET['Success'])) echo "<script>alert('✅ Login Successful');</script>";
if (isset($_GET['LogAdded'])) echo "<script>alert('📝 Log Details Added');</script>";

// ✅ Handle form submission from verifyTicket.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['codeval'], $_POST['uid'], $_POST['uname'], $_POST['slot_name'], $_POST['vnumber'])) {
    
    $codeval   = htmlspecialchars(trim($_POST['codeval']));
    $uid       = htmlspecialchars(trim($_POST['uid']));
    $uname     = htmlspecialchars(trim($_POST['uname']));
    $slot_name = htmlspecialchars(trim($_POST['slot_name']));
    $vnumber   = htmlspecialchars(trim($_POST['vnumber']));

    try {
        // 🔍 Check if ticket is already used
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM parking_details WHERE codeval = ?");
        $checkStmt->execute([$codeval]);
        $alreadyUsed = $checkStmt->fetchColumn();

        if ($alreadyUsed > 0) {
            header("Location: TCHome.php?AlreadyUsed");
            exit();
        }

        // 📝 Insert new parking log
        $stmt = $conn->prepare("INSERT INTO parking_details (vnumber, codeval, uid, uname, slot_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$vnumber, $codeval, $uid, $uname, $slot_name]);

        // Mark ticket as used AFTER vehicle number is submitted
$stmtUpdate = $conn->prepare("UPDATE slot_booking SET ustatus = 'Yes' WHERE codeval = ?");
$stmtUpdate->execute([$codeval]);


        header("Location: TCHome.php?LogAdded");
        exit();
    } catch (Exception $ex) {
        error_log($ex->getMessage());
        header("Location: TCHome.php?Error");
        exit();
    }
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
                <a href="TCHome.php" class="nav-item nav-link active"><i class="fas fa-home me-1"></i>Home</a>
                
                <a href="logout.php?type=ticket" class="nav-item nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
    </nav>

    <!-- Page Header Start 
    <div class="container-fluid page-header mb-5 p-0">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <nav aria-label="breadcrumb">
                </nav>
            </div>
        </div>
    </div>
     -->

   <!-- Ticket Checker Home Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp mb-5" data-wow-delay="0.1s">
            <h3 class="text-primary text-uppercase">Ticket Checker Dashboard</h3>
            <p class="mt-3 fs-5 text-muted">Efficiently scan and verify tickets, and keep track of all parking activity logs.</p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Check Parking Ticket -->
            <div class="col-lg-5 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                <div class="bg-light rounded p-4 text-center h-100 shadow-sm hover-shadow">
                    <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                    <h5 class="mb-3">Check Parking Ticket</h5>
                    <p>Scan QR codes to validate parking tickets quickly and securely.</p>
                    <a href="CheckParking.php" class="btn btn-outline-gradient mt-2">Start Scanning</a>
                </div>
            </div>

            <!-- Parking Logs -->
            <div class="col-lg-5 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-light rounded p-4 text-center h-100 shadow-sm hover-shadow">
                    <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                    <h5 class="mb-3">Parking Logs</h5>
                    <p>Access detailed logs of all recent parking entries and exits.</p>
                    <a href="ParkingLogs.php" class="btn btn-outline-gradient mt-2">View Logs</a>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Ticket Checker Home End -->



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
