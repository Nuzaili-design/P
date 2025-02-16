<?php
session_start();
require 'SQLconnection.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $con = SQLconnection::getConnection();

        $name = $_POST["username"];
        $mail = $_POST["email"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $pass = password_hash($_POST["pass"], PASSWORD_BCRYPT); // Secure password hashing
        $ustatus = "No"; // Default status

        // Debugging log (optional)
        error_log("Registering: $name, $mail, $dob, $gender, $phone");

        // Check if the email is already registered
        $stmt = $con->prepare("SELECT * FROM user_reg WHERE email = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: Users.php?mailid"); // Email already exists
            exit();
        }

        // Insert new user
        $stmt = $con->prepare("INSERT INTO user_reg (name, email, dob, gender, phone, address, password, ustatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $mail, $dob, $gender, $phone, $address, $pass, $ustatus);

        if ($stmt->execute()) {
            header("Location: Users.php?Success"); // Successful registration
        } else {
            header("Location: Users.php?Failed"); // Registration failed
        }
        exit();

    } catch (Exception $e) {
        error_log("User Registration Error: " . $e->getMessage());
        header("Location: Users.php?Error");
        exit();
    }
}
?>

