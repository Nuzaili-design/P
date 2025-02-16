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
            echo "<script>alert('Email already registered! Please use another email.'); window.location.href='URegister.php';</script>";
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
            echo "<script>alert('Registration successful!.'); window.location.href='Users.php';</script>";
        } else {
            echo "<script>alert('Error: Could not register user.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
    </nav>

    <div class="container py-5">
        <div class="text-center">
            <h1 class="mb-5">User Registration</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="URegister.php" method="post"> <!-- Corrected form action -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-control" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="number" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="Users.php">Already have an account? Login here</a>
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
</body>
</html>
