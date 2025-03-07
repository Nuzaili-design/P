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

    // Fetch booking details
    $query = "SELECT * FROM slot_booking WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        header("HTTP/1.1 404 Not Found");
        die("❌ No matching booking found.");
    }

    // Generate QR code dynamically
    $data = "User ID: " . $id . "\n";
    $data .= "Date: " . $result['pdate'] . "\n";
    $data .= "Time: " . $result['stime'] . " - " . $result['endtime'] . "\n";
    $data .= "Location: " . $result['slot_name'] . "\n";
    $data .= "Cost: " . $result['pcost'];

    // Output the QR code as a PNG image
    header("Content-Type: image/png");
    QRcode::png($data, false, QR_ECLEVEL_L, 10); // Adjust size and error correction level as needed
    exit();

} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die("❌ Database Error: " . $e->getMessage());
}
?>