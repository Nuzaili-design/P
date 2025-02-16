<?php
include 'db_connect.php'; // Use the correct database connection file

$uid = isset($_GET['uid']) ? $_GET['uid'] : '';

if (empty($uid)) {
    die("❌ Error: Missing UID parameter.");
}

try {
    $con = SQLConnection::getConnection(); // Get the database connection

    // Prepare and execute query using PDO
    $stmt = $con->prepare("SELECT image_data FROM slot_booking WHERE id = :uid");
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $imageData = $row['image_data'];

        if (!empty($imageData)) {
            // Serve the image as a PNG
            header("Content-Type: image/png");
            echo $imageData;
            exit; // Ensure no extra output
        } else {
            die("❌ Error: No QR code found for this UID.");
        }
    } else {
        die("❌ Error: No matching record found.");
    }
} catch (PDOException $e) {
    die("❌ Database Error: " . $e->getMessage());
}
?>
