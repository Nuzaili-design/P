<?php
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/phpqrcode/phpqrcode.php';
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php'; // Database connection

class QRGen {
    // Function to create the QR code
    public static function createQR($data, $path, $size = 5) {
        $dir = dirname($path);

        // Ensure the directory exists
        if (!file_exists($dir) && !mkdir($dir, 0777, true)) {
            die("❌ Error: Failed to create QR code directory!");
        }

        // Generate and save the QR Code
        QRcode::png($data, $path, QR_ECLEVEL_L, $size);
    }

    // Function to store QR Code path in Database
    public static function storeQRInDB($uid, $qrPath) {
        try {
            $conn = SQLConnection::getConnection();

            // Ensure QR file exists
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $qrPath)) {
                return "❌ QR Code file not found at: " . htmlspecialchars($qrPath);
            }

            // Update database with QR image (stored as binary data)
            $stmt = $conn->prepare("UPDATE slot_booking SET image_data = ? WHERE uid = ?");
            $stmt->bindParam(1, file_get_contents($_SERVER['DOCUMENT_ROOT'] . $qrPath), PDO::PARAM_LOB);
            $stmt->bindParam(2, $uid, PDO::PARAM_INT);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? "✅ QR Code stored in database!" : "❌ No rows updated!";
        } catch (PDOException $e) {
            return "❌ Database Error: " . $e->getMessage();
        }
    }
}

// Generate QR Code if UID is provided
if (isset($_GET['uid']) && ctype_digit($_GET['uid'])) {
    $uid = intval($_GET['uid']); // Ensure UID is an integer

    try {
        $conn = SQLConnection::getConnection();

        // Fetch booking + parking details using JOIN
        $stmt = $conn->prepare("
            SELECT sb.uid, sb.uname, sb.slot_name, sb.pdate, sb.time, sb.pcost, 
                   pd.vnumber, pd.codeval, pd.timestamp 
            FROM slot_booking sb
            LEFT JOIN parking_details pd ON sb.uid = pd.uid
            WHERE sb.uid = ?
        ");
        $stmt->bindParam(1, $uid, PDO::PARAM_INT);
        $stmt->execute();
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("❌ No booking found for UID: " . htmlspecialchars($uid));
        }

        // QR code data (includes parking details)
        $qrData = json_encode([
            'UID'       => $booking['uid'],
            'Name'      => $booking['uname'],
            'Slot'      => $booking['slot_name'],
            'Date'      => $booking['pdate'],
            'Time'      => $booking['time'],
            'Cost'      => $booking['pcost'],
            'Vehicle'   => $booking['vnumber'] ?? 'N/A', // Handle null values
            'Code'      => $booking['codeval'] ?? 'N/A',
            'Timestamp' => $booking['timestamp'] ?? 'N/A'
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // QR code file path
        $qrDirectory = $_SERVER['DOCUMENT_ROOT'] . "/QRBasedVehicleParking/qrcodes/";
        if (!is_dir($qrDirectory) && !mkdir($qrDirectory, 0777, true)) {
            die("❌ Error: Failed to create QR code directory!");
        }

        $fileName = $qrDirectory . "qr_" . $uid . ".png";
        $qrPath = "/QRBasedVehicleParking/qrcodes/qr_$uid.png"; // Relative path for storage

        // Generate QR Code
        QRGen::createQR($qrData, $fileName);

        // Store QR image in database
        $storeResult = QRGen::storeQRInDB($uid, $qrPath);

        // Output the QR code for preview
        echo "<h3>✅ QR Code Generated for " . htmlspecialchars($booking['uname']) . "</h3>";
        echo "<img src='$qrPath' width='200' alt='QR Code'>";
        echo "<br>" . $storeResult;

    } catch (PDOException $ex) {
        die("❌ Database Error: " . $ex->getMessage());
    }
} else {
    die("❌ Error: Valid UID is required to generate QR code.");
}
?>

