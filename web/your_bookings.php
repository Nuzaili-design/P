<?php
session_start();


// Ensure the connection file exists before including it
if (!file_exists("db_connect.php")) {
    die("Error: Database connection file not found.");
}

include("db_connect.php"); // Include the connection file

// Get the connection from the class
$conn = SQLConnection::getConnection(); // Assign connection to $conn

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please login first.");
}
$uid = $_SESSION['uid'];

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']); // Sanitize input

    try {
        $delStmt = $conn->prepare("DELETE FROM slot_booking WHERE id = :id AND uid = :uid");
        $delStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $delStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $delStmt->execute();

        // Redirect with success flag
        header("Location: your_bookings.php?deleted=1");
        exit();
    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Error', 'Error deleting booking: " . addslashes($e->getMessage()) . "', 'error');
            });
        </script>";
    }
}




try {
    // Prepare and execute query safely
    $query = "SELECT * FROM slot_booking WHERE uid = :uid AND ustatus='No' ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

function formatTimeAMPM($time) {
    return date("h:i A", strtotime($time));
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Modernized Table Style for Project */
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


    </style>

</head>

<body>

    
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
                <a href="UserHome.php" class="nav-item nav-link"><i class="fas fa-home me-1"></i>Home</a>
                <a href="your_bookings.php" class="nav-item nav-link active"><i class="fas fa-clipboard-list me-2"></i>Your Bookings</a>

             <!--   <div class="dropdown nav-item nav-link">
    <a class="dropdown-toggle" href="#" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        🌐 <?= strtoupper($_SESSION['lang'] ?? 'EN') ?>
    </a>
    <ul class="dropdown-menu" aria-labelledby="langDropdown">
        <li><a class="dropdown-item" href="?lang=en">English</a></li>
        <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
        <li><a class="dropdown-item" href="?lang=tr">Türkçe</a></li>
    </ul>
</div> -->

                <a href="logout.php?type=user" class="nav-item nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
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
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h3 class="text-secondary">Your Bookings</h3>
                <br>
                    <p class="mb-0"></p>
                </div>
                <div class="col-lg-12">
                <table id="naresh" class="table table-bordered">
                <thead class="table-dark">
            <tr>
        <th>Parking Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Slot Name</th>
        <th>Booking Time</th>
        <th>Parking Cost</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row): ?>
        <tr>
            <td data-label="Parking Date"><?= htmlspecialchars($row["pdate"]) ?></td>
            <td data-label="Start Time"><?= formatTimeAMPM($row["stime"]) ?></td>
            <td data-label="End Time"><?= formatTimeAMPM($row["endtime"]) ?></td>
            <td data-label="Slot Name"><?= htmlspecialchars($row["slot_name"]) ?></td>
            <td data-label="Booking Time"><?= htmlspecialchars($row["time"]) ?></td>
            <td data-label="Parking Cost"><?= htmlspecialchars($row["pcost"]) ?></td>
            <td data-label="Actions">
                <div class="d-flex gap-2">
                    <a href="DownloadTicket.php?reqid=<?= $row["id"] ?>" class="btn btn-secondary btn-sm text-white">
                        View
                    </a>
                    <a href="your_bookings.php?delete=<?= $row["id"] ?>" 
                       class="btn btn-danger btn-sm delete-btn" 
                       data-id="<?= $row["id"] ?>">
                        Delete
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

                </div>
            </div>
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
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Booking deleted',
            text: 'Your booking has been successfully removed.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            // 👇 Refresh page WITHOUT query string
            window.location.href = "your_bookings.php";
        });
    });
</script>
<?php endif; ?>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".delete-btn");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // 🔥 Stops the # from appearing

                const bookingId = this.getAttribute("data-id");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to trigger PHP delete
                        window.location.href = `your_bookings.php?delete=${bookingId}`;
                    }
                });
            });
        });
    });
</script>

</body>
</html>

<?php
$stmt = null; // Close the statement
$conn = null; // Close the connection

?>
 