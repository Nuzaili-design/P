<?php
// Include the database connection file using an absolute path
include_once __DIR__ . '/db_connect.php'; // Adjust the path if the file is in a different directory
session_start();


// ✅ Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: Administrator.php?AccessDenied");
    exit();
}

// Debugging: Check if connection is established
try {
    $conn = SQLConnection::getConnection();
} catch (Exception $e) {
    die('Error connecting to the database: ' . $e->getMessage());
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
    <link href="css/table.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
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
                <a href="AdminHome.php" class="nav-item nav-link">Home</a>
                <a href="UserDetails.php" class="nav-item nav-link">User Details</a>
                <a href="ParkingCost.php" class="nav-item nav-link">Parking Cost</a>
                <a href="Bookings.php" class="nav-item nav-link active">Bookings</a>
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
                <nav aria-label="breadcrumb">
                </nav>
            </div>
        </div>
    </div>
     -->

    <!-- Booking Details Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="text-primary text-uppercase">// Booking Details //</h3>
                <br><br>
            </div>
            <div class="row g-4">
                <table id="naresh" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Parking Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Slot Name</th>
                            <th>Booking Time</th>
                            <th>Parking Cost</th>
                            <th>Parking Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch data and display in the table
                        try {
                            $stmt = $conn->query("SELECT * FROM slot_booking ORDER BY id DESC");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $stime = date('h:i A', strtotime($row['stime']));
                                $endtime = date('h:i A', strtotime($row['endtime']));
                                echo "<tr>
                                        <td>{$row['uname']}</td>
                                        <td>{$row['pdate']}</td>
                                        <td>{$stime}</td>
                                        <td>{$endtime}</td>
                                        <td>{$row['slot_name']}</td>
                                        <td>{$row['time']}</td>
                                        <td>{$row['pcost']}</td>
                                        <td>{$row['ustatus']}</td>
                                    </tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Booking Details End -->

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
