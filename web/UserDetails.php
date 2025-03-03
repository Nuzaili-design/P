<?php
session_start();
require_once "db_connect.php"; // Ensure the correct database connection file is included

$conn = SQLConnection::getConnection(); // Get the database connection

// Approve user functionality
if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];

    $stmt = $conn->prepare("UPDATE user_reg SET ustatus = 'Yes' WHERE id = :id");
    $stmt->bindParam(':id', $approve_id);

    if ($stmt->execute()) {
        header("Location: UserDetails.php?approved=true");
        exit();
    } else {
        header("Location: UserDetails.php?error=true");
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
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        <?php if (isset($_GET['approved'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'User Approved Successfully',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'UserDetails.php';
            });
        <?php } elseif (isset($_GET['error'])) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Error Approving User',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'UserDetails.php';
            });
        <?php } ?>
    </script>

 <!-- Spinner Start  -->
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

    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="text-primary text-uppercase">// User Details //</h3>
            </div>
            <div class="row g-4">
                <table id="naresh" class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Phone No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    try {
                        $stmt = $conn->query("SELECT * FROM user_reg");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['dob']}</td>
                                    <td>{$row['address']}</td>
                                    <td>{$row['gender']}</td>
                                    <td>{$row['phone']}</td>
                                    <td>{$row['ustatus']}</td>
                                    <td>";
                            if ($row['ustatus'] != 'Yes') {
                                echo "<a href='UserDetails.php?approve_id={$row['id']}' class='btn btn-success btn-sm'>Approve</a>";
                            } else {
                                echo "<span class='text-success'>Approved</span>";
                            }
                            echo "</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='9'>Error fetching user details: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

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

