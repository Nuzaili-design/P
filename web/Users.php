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
            header("Location: UserHome.php");
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <script>
        // Show alerts based on URL parameters
        const params = new URLSearchParams(window.location.search);
        if (params.has('error')) {
            let message = "";
            switch (params.get('error')) {
                case 'invalid_email': message = "Invalid email format!"; break;
                case 'invalid_credentials': message = "Incorrect email or password. Please try again."; break;
                case 'server_error': message = "Server error. Please try again later."; break;
            }
            if (message) alert(message);
        }
    </script>

    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
    </nav>
    
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center">
                <h6 class="text-primary text-uppercase">// Users //</h6>
                <h1 class="mb-5">User Login</h1>
            </div>
            <div class="row g-4">
                <center>
                    <div class="col-md-6">
                        <form action="" method="post">  <!-- Fixed action to work dynamically -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                                <label>Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="pass" placeholder="Password" required>
                                <label>Password</label>
                            </div>
                            <button class="btn btn-primary w-100 py-3" type="submit">Login</button>
                        </form>
                        <a href="URegister.php" class="btn btn-link">New User? Register Here</a>
                        <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
                    </div>
                </center>
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
</body>
</html>
