<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php';

// ✅ Ensure the Ticket Checker is logged in
if (!isset($_SESSION['ticket_checker_logged_in']) || $_SESSION['ticket_checker_logged_in'] !== true) {
    header("Location: TicketChecker.php?AccessDenied"); // Redirect to login page if not logged in
    exit();
}
if (isset($_GET['Invalid'])) echo "<script>alert('❌ Invalid Ticket');</script>";
// Check if codeval is received from the QR scan
if (isset($_GET['codeval'])) {
    $codeval = $_GET['codeval'];

    try {
        $conn = SQLConnection::getConnection();

        // Fetch booking details
        $stmt = $conn->prepare("SELECT * FROM slot_booking WHERE codeval = ?");
        $stmt->execute([$codeval]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            header("Location: CheckParking.php?Invalid=1"); // Redirect with error message
            exit();
        }

        // Redirect to verifyTicket.php with codeval
        header("Location: verifyTicket.php?codeval=" . urlencode($codeval));
        exit();

    } catch (PDOException $e) {
        die("❌ Database Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Check Parking Ticket</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Styles & Libraries -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
<!-- Favicon -->
<link href="img/favicon.ico" rel="icon">

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet"> 
  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">


    <script src="https://unpkg.com/html5-qrcode"></script>
    
    
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fas fa-parking me-3"></i>Car Reservation System</h2>
        </a>
    </nav>

   <!-- QR Scanner Section -->
<div class="container-xxl py-3">
    <div class="container">
        <div class="text-center mb-3">
          
            <p class="text-muted">Hold the ticket QR code in front of the camera</p>
        </div>
        <div class="d-flex justify-content-center">
            <div class="card shadow-lg border-0 p-4" style="max-width: 500px; width: 100%; border-radius: 1rem;">
                <div id="reader" style="width: 100%; height: 300px;  border-radius: 0.5rem; overflow: hidden;"></div>
                <p id="qr-result" class="text-danger mt-3 text-center fw-semibold"></p>
                <a href="TCHome.php" class="btn btn-primary shadow download-btn">Go Back</a>
            </div>
            
        </div>
        
    </div>
</div>


    <!-- QR Scanner Script -->
    <script>
        let scanLocked = false;
        const html5QrCode = new Html5Qrcode("reader");

        function onScanSuccess(decodedText) {
            if (scanLocked) return;

            scanLocked = true;

            html5QrCode.stop().then(() => {
                let parts = decodedText.split("|");

                if (parts.length === 2 && parts[0] === "QR-Based-Parking") {
                    let codeval = parts[1];
                    window.location.href = "CheckParking.php?codeval=" + encodeURIComponent(codeval);
                } else {
                    document.getElementById("qr-result").innerText = "❌ Invalid QR Code!,It might be an external QR Code.";
                    scanLocked = false; // Allow retry if format is invalid
                }
            }).catch(err => {
                console.error("❌ Error stopping scanner:", err);
                scanLocked = false; // Retry on error
            });
        }

        function onScanFailure(error) {
            // Optional: Handle scan failure feedback
            // console.warn(`QR Scan Error: ${error}`);
        }

        // Start the QR Scanner
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 350, height: 350 }},
            onScanSuccess,
            onScanFailure
        );
    </script>

</body>
</html>
