<?php
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php';

// Check if codevalue is received from the QR scan
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

        // Redirect to verifyTicket.php with codevalue
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
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
    </nav>

    <!-- QR Scanner Section -->
    <div class="container-xxl py-5">
        <div class="container text-center">
            <h3 class="text-primary text-uppercase">// Scan Parking Ticket //</h3>
            <br><br>
            <div id="reader" style="width: 300px; margin: auto;"></div>
            <p id="qr-result"></p>
        </div>
    </div>

    <!-- QR Scanner Script -->
    <script>
        function onScanSuccess(decodedText) {
            let parts = decodedText.split("|");

            // Ensure QR format is correct: "QR-Based-Parking|CODEVALUE"
            if (parts.length === 2 && parts[0] === "QR-Based-Parking") {
                let codeval = parts[1];

                // Redirect to CheckParking.php with codevalue
                window.location.href = "CheckParking.php?codeval=" + encodeURIComponent(codeval);
            } else {
                document.getElementById("qr-result").innerText = "❌ Invalid QR Code!";
            }
        }

        function onScanFailure(error) {
            console.warn(`QR Scan Error: ${error}`);
        }

        // Start the QR Scanner
        let html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanFailure
        );
    </script>

</body>
</html>
