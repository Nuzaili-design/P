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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Vehicle Parking</a>
    </nav>
    <div class="container mt-5">
        <h2>Slot Booking</h2>
        
        <!-- Slot selection form -->
        <form action="Book_parking.php" method="post">
            <div class="form-group">
                <label>Select Date:</label>
                <input type="date" class="form-control" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Select Parking Time:</label>
                <select name="stime" class="form-control" required>
                    <option value="">--Select Time--</option>
                    <?php 
                    for ($i = 000; $i <= 23; $i++) { 
                        $selected = (isset($_SESSION['selected_time']) && $_SESSION['selected_time'] == "{$i}:00") ? "selected" : "";
                        echo "<option value='{$i}:00' $selected>{$i}:00</option>"; 
                    } 
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Enter Parking Hours:</label>
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
            <button type="submit" class="btn btn-primary">SELECT SLOT</button>
        </form>
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

