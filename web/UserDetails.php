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
</head>

<body>
    <?php if (isset($_GET['approved'])) echo "<script>alert('User approved successfully');</script>"; ?>
    <?php if (isset($_GET['error'])) echo "<script>alert('Error approving user');</script>"; ?>

    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
        <div class="collapse navbar-collapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="AdminHome.php" class="nav-item nav-link">Home</a>
                <a href="UserDetails.php" class="nav-item nav-link active">User Details</a>
                <a href="ParkingCost.php" class="nav-item nav-link">Parking Cost</a>
                <a href="Bookings.php" class="nav-item nav-link">Bookings</a>
                <a href="ParkingLogs.php" class="nav-item nav-link">Parking Logs</a>
                <a href="logout.php" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center">
                <h3 class="text-primary text-uppercase">// User Details //</h3>
            </div>
            <div class="row g-4">
                <table id="userTable">
                    <tr>
                       <!-- <th>User ID</th> -->
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

    <footer class="container-fluid bg-dark text-light pt-5 mt-5">
        <div class="container text-center">
            <p class="border-bottom">QR Code-based Smart Vehicle Parking Management System</p>
        </div>
    </footer>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
