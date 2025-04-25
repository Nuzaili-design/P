<?php
session_start();
ini_set('display_errors', 0); // Hide errors from users
ini_set('log_errors', 1); // Log errors instead
error_reporting(E_ALL); // Still report all errors

require_once "db_connect.php"; // Ensure database connection

try {
    $conn = SQLConnection::getConnection(); // Get the PDO connection

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['pass']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: Users.php?error=invalid_email");
            exit();
        }

        // Fetch user details
        $stmt = $conn->prepare("SELECT id, name, password FROM user_reg WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user not found
        if (!$user) {
            header("Location: Users.php?error=invalid_credentials");
            exit();
        }

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['uid'] = $user['id'];
            $_SESSION['user_name'] = htmlspecialchars($user['name']); // Prevent XSS

            // Redirect to UserHome.php
            header("Location: UserHome.php?success=1");

            exit();
        } else {
            header("Location: Users.php?error=invalid_credentials");
            exit();
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log errors for security
    header("Location: Users.php?error=server_error");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Login | Vehicle Parking</title>
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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

body {
            background-color: #f8f9fa;
        }
        .col-md-6 {
            max-width: 500px;
            margin: 10px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control, .btn {
            border-radius: 8px;
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
        // Show SweetAlert based on URL parameters
        const params = new URLSearchParams(window.location.search);
        if (params.has('error')) {
            let message = "";
            let icon = "error";

            switch (params.get('error')) {
                case 'invalid_email':
                    message = "Invalid email format!";
                    break;
                case 'invalid_credentials':
                    message = "Incorrect email or password. Please try again.";
                    break;
                case 'server_error':
                    message = "Server error. Please try again later.";
                    break;
            }

            if (message) {
                Swal.fire({
                    icon: icon,
                    title: "Login Failed",
                    text: message,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "Users.php"; // Remove the error parameter from URL
                });
            }
        }
    </script>

    <!-- Spinner Start -->
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
            <a href="index.php" class="nav-item nav-link"><i class="fas fa-home me-1"></i>Home</a>
            <a href="Administrator.php" class="nav-item nav-link"><i class="fas fa-user-shield me-1"></i>Administrator</a>
            <a href="TicketChecker.php" class="nav-item nav-link"><i class="fas fa-qrcode me-1"></i>Ticket Checker</a>
            <a href="Users.php" class="nav-item nav-link active"><i class="fas fa-users me-1"></i>Users</a>
        </div>
    </div>
</nav>
<!-- Navbar End -->
    
    <!-- Login Form Start -->
<div class="container-xxl py-5">
    
    <div class="container">
        
        <div class="row g-4">
            <center>
                <div class="col-md-6">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            
            <h1 class="mb-3">Sign In</h1>
        </div>
                    <div class="wow fadeInUp" data-wow-delay="0.3s">
                        <form action="" method="post">
                            <div class="row g-3">
                                <div class="col-12">
                                
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" required name="email" placeholder="Email">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="pass" required name="pass" placeholder="Password">
                                        <label for="pass">Password</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Login</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <a href="URegister.php" class="link-gradient">New User? Register Here</a><br>
                        <a href="forgot_password.php" class="link-gradient">Forgot Password?</a>
                    </div>
                </div>
            </center>
        </div>
       
    </div>
</div>
<!-- Login Form End -->






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
</body>
</html>
