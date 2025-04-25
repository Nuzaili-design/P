<?php
session_start();

// Check if logout type is specified
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    switch ($type) {
        case 'admin':
            unset($_SESSION['admin_logged_in']);
            setcookie("admin_logged_in", "", time() - 3600, "/"); // Remove cookie too
            break;

        case 'ticket':
            unset($_SESSION['ticket_checker_logged_in']);
            setcookie("ticket_checker_logged_in", "", time() - 3600, "/");
            break;

        case 'user':
            unset($_SESSION['uid']);
            break;

        default:
            // Unknown type, destroy session as fallback
            session_destroy();
            break;
    }

    // Optional: If session is empty after unsetting, destroy it
    if (empty($_SESSION)) {
        session_destroy();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>

    <!-- Include SweetAlert2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        title: "Goodbye!",
        text: "You have successfully logged out.",
        icon: "success",
        confirmButtonText: "Okay"
    }).then(() => {
        // Redirect to index or login page depending on the type
        const params = new URLSearchParams(window.location.search);
        const type = params.get("type");

        let redirectURL = "index.php"; // default
        if (type === "admin") redirectURL = "Administrator.php";
        else if (type === "ticket") redirectURL = "TicketChecker.php";
        else if (type === "user") redirectURL = "Users.php";

        window.location.href = redirectURL;
    });
</script>

</body>
</html>


