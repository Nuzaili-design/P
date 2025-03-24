<?php
session_start();
require_once "db_connect.php"; // Ensure the database connection file is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $address = trim($_POST["address"]);
    $password = password_hash($_POST["pass"], PASSWORD_BCRYPT); // Secure password hashing

    try {
        $conn = SQLConnection::getConnection(); // Get PDO connection

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT id FROM user_reg WHERE email = :email");
        $checkEmail->bindParam(":email", $email);
        $checkEmail->execute();

        if ($checkEmail->rowCount() > 0) {
            $_SESSION['error_message'] = "Email already registered! Please use another email.";
            header("Location: URegister.php");
            exit();
        }

        // Insert user data
        $sql = "INSERT INTO user_reg (name, email, dob, gender, phone, address, password, ustatus) 
                VALUES (:name, :email, :dob, :gender, :phone, :address, :password, 'No')"; // Default status: 'No'

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":dob", $dob);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":password", $password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful!";
            header("Location: URegister.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: Could not register user.";
            header("Location: URegister.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: URegister.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
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
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="Administrator.php" class="nav-item nav-link">Administrator</a>
            <a href="TicketChecker.php" class="nav-item nav-link">Ticket Checker</a>
            <a href="Users.php" class="nav-item nav-link">Users</a>
        </div>
    </div>
</nav>
<!-- Navbar End -->

<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h3 class="mb-5">We’re Excited to Have You! Register in Just a Few Steps</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="wow fadeInUp" data-wow-delay="0.2s">
                <form action="URegister.php" method="post">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" required name="name" placeholder="Name">
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email"  name="email" placeholder="Email"required 
                                  pattern="^[^@]+@[^@]+\.[^@]+$"
                                title="Please enter a valid email address (e.g. example@domain.com)">
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="dob" required name="dob" placeholder="Date of Birth">
                                <label for="dob">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="phone" required name="phone" placeholder="Phone">
                                <label for="phone">Phone</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="address" required name="address" placeholder="Address">
                                <label for="address">Address</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="pass" required name="pass" placeholder="Password">
                                <label for="pass">Password</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-3">Register</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="Users.php" class="btn btn-link">Already have an account? Login here</a>
                </div>
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
    <script src="js/main.js"></script>

    <!-- SweetAlert2 for success and error messages -->
    <script>
        <?php if (isset($_SESSION['success_message'])): ?>
            Swal.fire({
                title: "Success!",
                text: "<?php echo $_SESSION['success_message']; ?>",
                icon: "success"
            }).then(() => {
                window.location.href = 'Users.php'; // Redirect after success
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            Swal.fire({
                title: "Error!",
                text: "<?php echo $_SESSION['error_message']; ?>",
                icon: "error",
                 confirmButtonColor: 'red'
            });
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>