<?php
require 'lib/qrlib.php'; // Include the phpqrcode library

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
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST['data'] ?? 'Default QR Data';
    $fileName = "qrcodes/" . uniqid() . ".png"; // Generate unique filename

    // Generate QR Code
    QRGen::createQR($data, $fileName);

    echo "QR Code generated successfully: <a href='$fileName' target='_blank'><img src='$fileName' width='200'></a>";
}
?>

<!-- HTML Form for testing -->
<form method="POST">
    <label>Enter Data for QR Code:</label>
    <input type="text" name="data" required>
    <button type="submit">Generate QR</button>
</form>
