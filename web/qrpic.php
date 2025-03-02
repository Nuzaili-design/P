<?php
require 'db_connect.php'; // Ensure database connection

try {
    $conn = SQLConnection::getConnection();

    // Validate UID
    if (!isset($_GET['uid']) || !ctype_digit($_GET['uid'])) {
        header("HTTP/1.1 400 Bad Request");
        die("❌ Invalid UID");
    }

    $uid = intval($_GET['uid']);

    // Fetch QR Code image from database
    $query = "SELECT image_data FROM slot_booking WHERE uid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$uid]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || empty($result['image_data'])) {
        header("HTTP/1.1 404 Not Found");
        die("❌ No matching QR Code found.");
    }

    // Output the image
    header("Content-Type: image/png");
    header("Content-Length: " . strlen($result['image_data'])); // Correct image size for proper display
    echo $result['image_data'];
    exit();

} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die("❌ Database Error: " . $e->getMessage());
}
?>

