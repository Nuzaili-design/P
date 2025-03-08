<?php
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/phpqrcode/phpqrcode.php';
require $_SERVER['DOCUMENT_ROOT'] . '/QRBasedVehicleParking/web/db_connect.php';

try {
    $conn = SQLConnection::getConnection();

    // Validate id
    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
        header("HTTP/1.1 400 Bad Request");
        die("❌ Invalid id");
    }

    $id = intval($_GET['id']);

    // Check if a codevalue already exists
    $stmt = $conn->prepare("SELECT codeval FROM slot_booking WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        header("HTTP/1.1 404 Not Found");
        die("❌ No matching booking found.");
    }

    if (empty($result['codeval'])) {
        // Generate a unique codevalue (random 10-character string)
        $codeval = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

        // Store codevalue in the database
        $updateStmt = $conn->prepare("UPDATE slot_booking SET codeval = ? WHERE id = ?");
        $updateStmt->execute([$codeval, $id]);
    } else {
        $codeval = $result['codeval']; // Use existing codevalue
    }

    // Generate QR code with only the codevalue
    $qrData = "QR-Based-Parking|$codeval";

    // Output the QR code as a PNG image
    header("Content-Type: image/png");
    QRcode::png($qrData, false, QR_ECLEVEL_L, 10); // Adjust size and error correction level as needed
    exit();

} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die("❌ Database Error: " . $e->getMessage());
}
?>
