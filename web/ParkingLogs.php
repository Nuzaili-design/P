<?php
session_start();
// Include the connection file
include('db_connect.php');

// ✅ Ensure the Ticket Checker is logged in
if (!isset($_SESSION['ticket_checker_logged_in']) || $_SESSION['ticket_checker_logged_in'] !== true) {
    header("Location: TicketChecker.php?AccessDenied"); // Redirect to login page if not logged in
    exit();
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
    <style>
          
          #naresh {
  font-family: 'Poppins', sans-serif;
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  overflow: hidden;
  border-radius: 16px;
  background-color: #ffffff;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

/* Header Styling */
#naresh th {
  background: linear-gradient(135deg, #007bff, #4a00e0);
  color: #ffffff;
  font-weight: 600;
  text-transform: uppercase;
  padding: 16px;
  font-size: 15px;
  letter-spacing: 0.5px;
  border-right: 1px solid rgba(255, 255, 255, 0.15);
  position: relative;
  text-align: left;
}

/* Header subtle shine effect */
#naresh th::after {
  content: "";
  position: absolute;
  top: 0;
  left: 50%;
  width: 70%;
  height: 100%;
  background: radial-gradient(rgba(255, 255, 255, 0.08), transparent);
  transform: translateX(-50%);
}

/* Data Cell Styling */
#naresh td {
  padding: 14px 16px;
  font-size: 15px;
  color: #333;
  border-bottom: 1px solid #eaeaea;
  background-color: #fff;
  transition: background-color 0.3s ease;
}

/* Zebra Striping */
#naresh tr:nth-child(even) td {
  background-color: #f9f9f9;
}

/* Hover Effect */
#naresh tr:hover td {
  background-color: #f0f8ff;
  cursor: pointer;
  box-shadow: inset 0 0 0 9999px rgba(0, 123, 255, 0.03);
}

/* Responsive Tweaks */
@media screen and (max-width: 768px) {
  #naresh th,
  #naresh td {
    padding: 10px;
    font-size: 14px;
  }
}

@media screen and (max-width: 768px) {
  #naresh thead {
    display: none;
  }

  #naresh, #naresh tbody, #naresh tr, #naresh td {
    display: block;
    width: 100%;
  }

  #naresh tr {
    margin-bottom: 1rem;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0, 123, 255, 0.05);
    background: #ffffff;
    padding: 12px;
  }

  #naresh td {
    padding: 12px 16px 12px 50%;
    position: relative;
    font-size: 14px;
    border: none;
    border-bottom: 1px solid #f0f0f0;
    background: transparent;
  }

  #naresh td::before {
    content: attr(data-label);
    position: absolute;
    left: 16px;
    top: 12px;
    font-weight: 600;
    color: #007bff;
    text-transform: uppercase;
    font-size: 12px;
  }
}
          
          
          .form-control:focus {
    border-color: #4a00e0;
    box-shadow: 0 0 0 0.15rem rgba(74, 0, 224, 0.25); /* subtle gradient glow */
    outline: none;
}
    </style>
</head>

<body>
    <!-- Spinner Start  -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
   

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
                <a href="TCHome.php" class="nav-item nav-link"><i class="fas fa-home me-1"></i>Home</a>
                
                <a href="ParkingLogs.php" class="nav-item nav-link active"><i class="fas fa-clipboard-list me-2"></i>Parking Logs</a>
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

    <!-- Parking Logs Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="text-primary text-uppercase">Parking Logs</h3>
                <br>
                
                <div class="text-center mb-4">
    <input type="text" id="searchInput" class="form-control w-50 mx-auto" placeholder="Search parking logs...">
</div>
            </div>
            <div class="col-lg-12">
            <table id="naresh" class="table table-bordered">
            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket ID</th>
                        <th>Car Plate</th>
                        <th>Username</th>
                        <th>Slot Name</th>
                        <th>Timestamp</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Get database connection using SQLConnection class
                    $connection = SQLConnection::getConnection();

                    // Query the database for parking details
                    $sql = "SELECT * FROM parking_details";
                    $stmt = $connection->query($sql);

                    // Loop through the result set and display each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td data-label='ID'>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td data-label='Ticket ID'>" . htmlspecialchars($row['codeval']) . "</td>";
                        echo "<td data-label='Car Plate'>" . htmlspecialchars($row['vnumber']) . "</td>";
                        echo "<td data-label='Username'>" . htmlspecialchars($row['uname']) . "</td>";
                        echo "<td data-label='Slot Name'>" . htmlspecialchars($row['slot_name']) . "</td>";
                        echo "<td data-label='Timestamp'>" . htmlspecialchars($row['timestamp']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                     </tbody>
                </table>
            </div>
            
        </div>
    </div>
    <!-- Parking Logs End -->

        <!-- Back to Top -->
   <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->

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

    
    <script>
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#naresh tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
</body>

</html>

