<?php
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://unpkg.com/html5-qrcode"></script> <!-- QR Scanner Library -->
</head>
<body>

    <h2>Scan Your QR Code</h2>
    
    <!-- QR Scanner -->
    <div id="reader" style="width: 300px;"></div>
    <p id="qr-result"></p>

    <script>
        function onScanSuccess(decodedText) {
            // Extract codevalue (QR data format: "QR-Based-Parking|ABC123XYZ")
            let parts = decodedText.split("|");
            if (parts.length === 2 && parts[0] === "QR-Based-Parking") {
                let codeval = parts[1];

                // Redirect to PHP script with the extracted codevalue
                window.location.href = "scan_qr.php?codeval=" + encodeURIComponent(codeval);
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
            { facingMode: "environment" }, // Use back camera on phones
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanFailure
        );
    </script>

</body>
</html>

<?php
if (!isset($_GET['codeval'])) {
    exit(); // No QR code scanned yet
}

$codeval = $_GET['codeval'];

try {
    $conn = SQLConnection::getConnection();

    // Fetch user details based on codevalue
    $stmt = $conn->prepare("SELECT * FROM slot_booking WHERE codeval = ?");
    $stmt->execute([$codeval]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "<p>❌ No matching user found.</p>";
        exit();
    }

    echo "<h3>✅ User Details:</h3>";
    echo "Name: " . htmlspecialchars($result['uname']) . "<br>";
    echo "Slot: " . htmlspecialchars($result['slot_name']) . "<br>";
    echo "Date: " . htmlspecialchars($result['pdate']) . "<br>";
    echo "Time: " . htmlspecialchars($result['stime']) . " - " . htmlspecialchars($result['endtime']) . "<br>";
    echo "Cost: $" . htmlspecialchars($result['pcost']) . "<br>";

} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
