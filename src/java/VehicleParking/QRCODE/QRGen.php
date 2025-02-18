<?php
require 'C:/xampp/htdocs/QRBasedVehicleParking/phpqrcode/phpqrcode.php';
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php'; // Database connection

class QRGen {
    // Function to create the QR code
    public static function createQR($data, $path, $size = 5) {
        // Ensure the directory exists
        $dir = dirname($path);
        if (!file_exists($dir) && !mkdir($dir, 0777, true)) {
            die("❌ Error: Failed to create QR code directory!");
        }

        // Generate and save the QR Code
        QRcode::png($data, $path, QR_ECLEVEL_L, $size);
    }

    // Function to store QR Code path in Database
    public static function storeQRInDB($uid, $qrPath) {
        try {
            $con = SQLConnection::getConnection();

            // Ensure QR file exists
            if (!file_exists($qrPath)) {
                return "❌ QR Code file not found at: " . htmlspecialchars($qrPath);
            }

            // Store the QR Code path in the database
            $stmt = $con->prepare("UPDATE slot_booking SET qr_image_path = ? WHERE uid = ?");
            $stmt->execute([$qrPath, $uid]);

            return ($stmt->rowCount() > 0) ? "✅ QR Code stored in database!" : "❌ No rows updated!";
        } catch (PDOException $e) {
            return "❌ Database Error: " . $e->getMessage();
        }
    }
}

// Automatically generate QR code for the user
if (isset($_GET['uid'])) {
    $uid = intval($_GET['uid']); // Ensure ID is an integer

    try {
        $conn = SQLConnection::getConnection();

        // Fetch booking details
        $stmt = $conn->prepare("SELECT uid, uname, slot_name, date, time, cost FROM slot_booking WHERE uid = ?");
        $stmt->execute([$uid]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("❌ No booking found for UID: " . htmlspecialchars($uid));
        }

        // QR code data (booking details)
        $qrData = json_encode([
            'UID' => $booking['uid'],
            'Name' => $booking['uname'],
            'Slot' => $booking['slot_name'],
            'Date' => $booking['date'],
            'Time' => $booking['time'],
            'Cost' => $booking['cost']
        ]);

        // Ensure QR code directory exists
        $qrDirectory = $_SERVER['DOCUMENT_ROOT'] . "/QRBasedVehicleParking/qrcodes/";
        if (!is_dir($qrDirectory) && !mkdir($qrDirectory, 0777, true)) {
            die("❌ Error: Failed to create QR codes directory!");
        }

        $fileName = $qrDirectory . "qr_" . $uid . ".png"; // Unique filename for each UID

        // Generate QR Code
        QRGen::createQR($qrData, $fileName);

        // Store QR path in DB
        $storeResult = QRGen::storeQRInDB($uid, "/QRBasedVehicleParking/qrcodes/qr_$uid.png");

        // Output the QR code for preview
        echo "<h3>✅ QR Code Generated for " . htmlspecialchars($booking['uname']) . "</h3>";
        echo "<img src='/QRBasedVehicleParking/qrcodes/qr_$uid.png' width='200'>";
        echo "<br>" . $storeResult;
    } catch (PDOException $ex) {
        die("❌ Database Error: " . $ex->getMessage());
    }
} else {
    die("❌ Error: UID is required to generate QR code.");
}
?>
