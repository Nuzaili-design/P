<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $pass = trim(htmlspecialchars($_POST['pass'] ?? ''));

    // Debugging log
    error_log("Login attempt for user: " . $name);

    // Hardcoded credentials (Should be stored in a database securely)
    $validUsername = "Ticket";
    $validPassword = "Ticket"; // Consider using hashed passwords in a database

    if ($name === $validUsername && $pass === $validPassword) {
        $_SESSION['tc_logged_in'] = true;
        header("Location: TCHome.php?Success");
        exit();
    } else {
        error_log("Failed login attempt for user: " . $name);
        header("Location: TicketChecker.php?Failed");
        exit();
    }
}
?>

