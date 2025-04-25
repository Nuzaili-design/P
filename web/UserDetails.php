<?php
session_start();
require_once "db_connect.php"; // Ensure the correct database connection file is included

// ✅ Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: Administrator.php?AccessDenied");
    exit();
}

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
            <h2 class="m-0 text-primary"><i class="fas fa-parking me-3"></i>Car Reservation System</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="AdminHome.php" class="nav-item nav-link"><i class="fas fa-home me-1"></i>Home</a>
                <a href="UserDetails.php" class="nav-item nav-link active"><i class="fas fa-user me-2"></i>User Details</a>
                
                <a href="logout.php?type=admin" class="nav-item nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="text-secondary text-uppercase">User Details</h3>
                <br>
                <div class="text-center mb-4">
    <input type="text" id="searchInput" class="form-control w-50 mx-auto" placeholder="Search parking logs...">
</div>
            </div>
            <div class="col-lg-12">
                <table id="naresh" class="table table-bordered">
                <thead>  
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
                    </thead>
                    <tbody>
                    <?php
                    try {
                        $stmt = $conn->query("SELECT * FROM user_reg");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td data-label='Name'>{$row['name']}</td>
                                    <td data-label='Email'>{$row['email']}</td>
                                    <td data-label='DOB'>{$row['dob']}</td>
                                    <td data-label='Address'>{$row['address']}</td>
                                    <td data-label='Gender'>{$row['gender']}</td>
                                    <td data-label='Phone No'>{$row['phone']}</td>
                                    <td data-label='Status'>{$row['ustatus']}</td>
                                    <td data-label='Action'>";
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

     

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

