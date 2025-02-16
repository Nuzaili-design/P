<?php
session_start();
include 'db_connect.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    // Debugging log
    error_log("Check User email and Password: " . $email);

    try {
        // Create a database connection
        $conn = SQLConnection::getConnection();

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, email FROM user_reg WHERE email = :email AND password = :pass");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Store user data in session
            $_SESSION['uid'] = $row['id'];
            $_SESSION['uname'] = $row['name'];
            $_SESSION['umail'] = $row['email'];

            header("Location: UserHome.php?Success");
            exit();
        } else {
            header("Location: Users.php?Failed");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: Users.php?Failed");
        exit();
    }
}
?>
