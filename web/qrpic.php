<?php
require 'db_connect.php'; // Ensure database connection

$conn = SQLConnection::getConnection();

if (!isset($_GET['uid']) || !is_numeric($_GET['uid'])) {
    die("❌ Invalid UID");
}

$uid = intval($_GET['uid']);

// Fetch QR Code from database
$query = "SELECT image_data FROM slot_booking WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $uid, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result || empty($result['image_data'])) {
    die("❌ Error: No matching record found.");
}

// Output the image
header("Content-Type: image/png");
echo $result['image_data'];
?>

