
<?php
require 'db_connect.php'; // Ensure database connection

$conn = SQLConnection::getConnection();

if (!isset($_GET['qr'])) {
    header("Location: TCHome.php?Invalid");
    exit();
}

$qrValue = $_GET['qr'];

try {
    // Check if the QR code exists in the database
    $stmt1 = $conn->prepare("SELECT * FROM slot_booking WHERE codeval = ?");
    $stmt1->execute([$qrValue]);

    if ($stmt1->rowCount() > 0) {
        // Check if the QR code is unused
        $stmt = $conn->prepare("SELECT * FROM slot_booking WHERE codeval = ? AND ustatus = 'No'");
        $stmt->execute([$qrValue]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Mark ticket as used
            $stmt2 = $conn->prepare("UPDATE slot_booking SET ustatus = 'Yes' WHERE codeval = ?");
            $stmt2->execute([$qrValue]);

            $codeval = htmlspecialchars($row['codeval']);
            $uid = htmlspecialchars($row['uid']);
            $uname = htmlspecialchars($row['uname']);
            $slot_name = htmlspecialchars($row['slot_name']);
?>
<script>alert('Ticket Verified');</script>

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
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="TCHome.php" class="nav-item nav-link">Home</a>
                <a href="CheckParking.php" class="nav-item nav-link active">Check Parking Ticket</a>
                <a href="ParkingLogs.php" class="nav-item nav-link">Parking Logs</a>
                <a href="logout.php" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-xxl py-5">
        <br><br><br>
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="text-primary text-uppercase">// Parking Details //</h6>
                <h1 class="mb-5">Please Fill the Vehicle Number</h1>
            </div>
            <div class="row g-4">
                <center> 
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                            <form action="ParkingDetails.php" method="post">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subject" required name="vnumber" placeholder="Vehicle Number">
                                            <label for="subject">Vehicle Number</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="codeval" value="<?= $codeval ?>">
                                    <input type="hidden" name="uid" value="<?= $uid ?>">
                                    <input type="hidden" name="uname" value="<?= $uname ?>">
                                    <input type="hidden" name="slot_name" value="<?= $slot_name ?>">
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </center>
            </div>
            <br><br><br><br><br>
        </div>
    </div>

    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
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

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

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
    <script src="js/main.js"></script>
</body>
</html>

<?php
        } else {
            header("Location: TCHome.php?AlreadyUsed");
            exit();
        }
    } else {
        header("Location: TCHome.php?Invalid");
        exit();
    }
} catch (Exception $ex) {
    error_log($ex->getMessage());
    header("Location: TCHome.php?Error");
    exit();
}
?>
