<?php
session_start();
include 'db_connect.php'; // Ensure you have a separate file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $pass = $_POST['pass'] ?? '';

    // Debugging Log
    error_log("Login Attempt: Username - $name");

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT password FROM admin WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($pass, $hashed_password)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $name;
            header("Location: AdminHome.php?Success");
            exit();
        }
    }

    // If login fails
    header("Location: Administrator.php?Failed");
    exit();
}
?>
