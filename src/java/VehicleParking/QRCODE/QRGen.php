<?php

require 'phpqrcode.php'; // Include the phpqrcode library
require 'db_connect.php'; // Include database connection

class QRGen {
    // Function to create the QR code
    public static function createQR($data, $path, $size = 5) {
        // Ensure the directory exists
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // Generate and save the QR Code
        QRcode::png($data, $path, QR_ECLEVEL_L, $size);
    }

    // Function to store QR Code in Database
    public static function storeQRInDB($uid, $qrPath) {
        try {
            $con = SQLConnection::getConnection();

            if (!file_exists($qrPath)) {
                return "❌ QR Code file not found at: " . htmlspecialchars($qrPath);
            }

            // Read QR image as binary data
            $imageData = file_get_contents($qrPath);
            if ($imageData === false) {
                return "❌ Failed to read QR Code image file!";
            }

            // Store the QR Code in the database
            $stmt = $con->prepare("UPDATE slot_booking SET image_data = ? WHERE id = ?");
            $stmt->bindParam(1, $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(2, $uid, PDO::PARAM_INT);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? "✅ QR Code stored in database!" : "❌ No rows updated! Check if the UID exists.";
        } catch (PDOException $e) {
            return "❌ Database Error: " . $e->getMessage();
        }
    }
}

// Handle QR Code Generation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = htmlspecialchars($_POST['data'] ?? 'Default QR Data'); // Prevent XSS
    $uid = intval($_POST['uid'] ?? 0); // Ensure ID is an integer

    if ($uid <= 0) {
        die("❌ Invalid UID!");
    }

    // Ensure QR code directory exists
    $qrDirectory = "qrcodes/";
    if (!is_dir($qrDirectory) && !mkdir($qrDirectory, 0777, true)) {
        die("❌ Error: Failed to create QR codes directory!");
    }

    $fileName = $qrDirectory . "qr_" . $uid . ".png"; // Unique filename for each UID

    // Generate QR Code
    QRGen::createQR($data, $fileName);

    // Store QR in DB
    $storeResult = QRGen::storeQRInDB($uid, $fileName);

    // Output result
    echo "QR Code generated successfully: <a href='$fileName' target='_blank'><img src='$fileName' width='200'></a>";
    echo "<br>" . $storeResult;
}
?>

<!-- HTML Form for Testing -->
<form method="POST">
    <label>Enter Data for QR Code:</label>
    <input type="text" name="data" required>
    <input type="number" name="uid" placeholder="Enter UID" required>
    <button type="submit">Generate QR</button>
</form>
