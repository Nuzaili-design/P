<?php
session_start();
include 'db_connect.php'; // Include your database connection file

$conn = SQLConnection::getConnection();

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please login first.");
}

$uid = $_SESSION['uid']; 

// Display messages based on GET parameters
if (isset($_GET['Slot_booked'])) {
    echo "<script>alert('Slot Booked');</script>";
}
if (isset($_GET['Already'])) {
    echo "<script>alert('Slot Already Booked');</script>";
}

// Fetch parking cost details from the database
$query = "SELECT * FROM parking_cost";
$result = $conn->query($query);
$parking_cost = $result->fetch(PDO::FETCH_ASSOC);

// Handle form submission and store values in session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['selected_date'] = $_POST['date'];
    $_SESSION['selected_time'] = $_POST['stime'];
    $_SESSION['selected_hours'] = $_POST['phrs'];

    // Redirect to Bookparking.php for slot selection
    header("Location: Bookparking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QR Code-based Smart Vehicle Parking Management System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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

    
    
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .booking-form {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control, .btn {
            border-radius: 8px;
        }
        select.form-control {
            height: 40px;
            font-size: 14px;
        }

    </style>
</head>
<body>

<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    
 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="UserHome.php" class="nav-item nav-link">Home</a>
                <a href="parking_cost1.php" class="nav-item nav-link">Parking Cost</a>
                <a href="Book_parking.php" class="nav-item nav-link active">Book Parking Slot</a>
                <a href="your_bookings.php" class="nav-item nav-link">Your Bookings</a>
                <a href="logout.php" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    
    <!-- Booking Form -->
     
    <div class="container">
        <div class="booking-form">
            <h3 class="text-center text-primary mb-4">Book Your Parking Slot</h3>
            <form action="Book_parking.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Select Date:</label>
                    <input type="date" class="form-control" name="date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Parking Time:</label>
                    <select name="stime" class="form-control" required>
                        <option value="">--Select Time--</option>
                        <?php 
                        for ($i = 0; $i <= 23; $i++) { 
                            $selected = (isset($_SESSION['selected_time']) && $_SESSION['selected_time'] == "{$i}:00") ? "selected" : "";
                            echo "<option value='{$i}:00' $selected>{$i}:00</option>"; 
                        } 
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Enter Parking Hours:</label>
                    <select name="phrs" class="form-control" required>
                        <option value="">--Select Hours--</option>
                        <?php 
                        for ($i = 1; $i <= 6; $i++) { 
                            $selected = (isset($_SESSION['selected_hours']) && $_SESSION['selected_hours'] == $i) ? "selected" : "";
                            echo "<option value='{$i}' $selected>{$i} hrs</option>"; 
                        } 
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">SELECT SLOT</button>
            </form>
        </div>
    </div>
    

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

