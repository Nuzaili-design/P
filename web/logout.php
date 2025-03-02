<?php
session_start();
session_destroy(); // Invalidate the session so no values will be present in session
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
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect the user to index.php after they click "Okay"
                window.location.href = 'index.php';
            }
        });
    </script>

</body>
</html>


