<?php
require 'lib/qrlib.php'; // Include the phpqrcode library
require 'db_connect.php'; // Include database connection

class QRGen {
    // Function to create the QR code
    public static function createQR($data, $path, $size = 5) {
        // Ensure the directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        // Generate and save the QR Code
        QRcode::png($data, $path, QR_ECLEVEL_L, $size);
    }

    // Function to store QR Code in Database
    public static function storeQRInDB($uid, $qrPath) {
        try {
            $con = SQLConnection::getConnection();

            if (!file_exists($qrPath)) {
                return "❌ QR Code file not found!";
            }

            // Read image as binary data
            $imageData = file_get_contents($qrPath);

            // Store the QR Code in the database
            $stmt = $con->prepare("UPDATE slot_booking SET image_data = :image WHERE id = :uid");
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? "✅ QR Code stored in database!" : "❌ No rows updated!";
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

    $fileName = "qrcodes/qr_" . $uid . ".png"; // Unique filename for each UID

    // Generate QR Code
    QRGen::createQR($data, $fileName);

    // Store QR in DB
    $storeResult = QRGen::storeQRInDB($uid, $fileName);

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
