<?php
session_start();
include 'db_connect.php';  // Database connection
require 'phpqrcode/qrlib.php';  // Include QR Code Library

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $codeval = htmlspecialchars(trim($_POST['codeval'] ?? ''));
    $uid = htmlspecialchars(trim($_POST['uid'] ?? ''));
    $uname = htmlspecialchars(trim($_POST['uname'] ?? ''));
    $slotName = htmlspecialchars(trim($_POST['slot_name'] ?? ''));

    // Debugging log
    error_log("Parking Details Entry: Name = $name, Code = $codeval, UID = $uid, Uname = $uname, Slot = $slotName");

    try {
        // Establish database connection
        $conn = SQLConnection::getConnection();

        // Insert data into parking_details table
        $stmt = $conn->prepare("INSERT INTO parking_details (vnumber, codeval, uid, uname, slot_name) 
                                VALUES (:name, :codeval, :uid, :uname, :slot_name)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':codeval', $codeval);
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':uname', $uname);
        $stmt->bindParam(':slot_name', $slotName);

        if ($stmt->execute()) {
            // Generate QR Code
            $qrData = "Vehicle No: $name\nSlot: $slotName\nCode: $codeval";
            $qrDir = "qrcodes/";
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true); // Ensure directory exists
            }
            $qrFileName = $qrDir . $codeval . ".png";  
            QRcode::png($qrData, $qrFileName, QR_ECLEVEL_L, 6);

            // Update slot_booking status
            $updateStmt = $conn->prepare("UPDATE slot_booking SET ustatus = 'Yes' WHERE codeval = :codeval");
            $updateStmt->bindParam(':codeval', $codeval);
            $updateStmt->execute();

            // Redirect with success and QR code
            header("Location: TCHome.php?LogAdded&qr=" . urlencode($qrFileName));
            exit();
        } else {
            header("Location: TCHome.php?LogFailed");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: TCHome.php?LogFailed");
        exit();
    }
}
?>
