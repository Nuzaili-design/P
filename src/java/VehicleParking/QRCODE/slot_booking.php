<?php
session_start();
require 'vendor/autoload.php'; // Load necessary libraries
require 'db_connect.php'; // Your database connection file
require 'QRGen.php'; // Your QR Code generation file

use Zxing\QrReader;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $con = SQLconnection::getConnection();

        $pdate = $_POST["pdate"];
        $stime1 = $_POST["stime"] . ":00";
        $phrs = $_POST["phrs"] . ":00";
        $slot_name = $_POST["Slot"];
        $totalcost = $_POST["totalcost"];

        // Convert start time and parking hours to calculate end time
        $stime = new DateTime($stime1, new DateTimeZone("UTC"));
        $phrsTime = new DateTime($phrs, new DateTimeZone("UTC"));
        $etime = clone $stime;
        $etime->add(new DateInterval('PT' . $phrsTime->format('H') . 'H' . $phrsTime->format('i') . 'M'));

        $stimeFormatted = $stime->format("H:i:s");
        $etimeFormatted = $etime->format("H:i:s");

        $uid = $_SESSION["uid"] ?? null;
        $uname = $_SESSION["uname"] ?? null;
        $umail = $_SESSION["umail"] ?? null;

        if (!$uid || !$uname || !$umail) {
            header("Location: Book_parking.php?SessionExpired");
            exit();
        }

        // Check if slot is already booked
        $stmt = $con->prepare("SELECT * FROM slot_booking WHERE pdate = ? AND stime = ? AND slot_name = ?");
        $stmt->bind_param("sss", $pdate, $stimeFormatted, $slot_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: Book_parking.php?AlreadyBooked");
            exit();
        } else {
            // Define QR code storage path (inside project directory)
            $path = __DIR__ . "/qr_codes";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Generate unique QR code value
            $characters = "378AIJKLM5CD4NOP126EFGHB9";
            $codeval = substr(str_shuffle($characters), 0, 10);
            $qrFileName = uniqid("QR_", true) . ".png";
            $pathQR = $path . "/" . $qrFileName;

            // Generate QR code
            if (!QRGen::createQR($codeval, $pathQR)) {
                error_log("QR Code Generation Failed for user: " . $umail);
                header("Location: Book_parking.php?QRGenFailed");
                exit();
            }

            // Ensure QR code file exists before reading
            if (!file_exists($pathQR)) {
                error_log("QR Code file not found: " . $pathQR);
                header("Location: Book_parking.php?QRFileNotFound");
                exit();
            }

            // Read QR code as binary data
            $imageData = file_get_contents($pathQR);
            if (!$imageData) {
                error_log("Failed to read QR Code file: " . $pathQR);
                header("Location: Book_parking.php?QRReadFailed");
                exit();
            }

            // Insert into database
            $stmt = $con->prepare("INSERT INTO slot_booking (uname, uid, pdate, stime, phrs, umail, slot_name, time, endtime, pcost, image_data, codeval) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");

            // Bind non-BLOB values
            $stmt->bind_param("ssssssssss", $uname, $uid, $pdate, $stimeFormatted, $phrs, $umail, $slot_name, $etimeFormatted, $totalcost, $codeval);

            // Bind BLOB separately
            $stmt->send_long_data(10, $imageData); // Correct BLOB handling

            // Execute the query
            if ($stmt->execute()) {
                header("Location: Book_parking.php?SlotBooked");
                exit();
            } else {
                error_log("Database Insert Failed: " . $stmt->error);
                header("Location: Book_parking.php?Failed");
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Slot Booking Error: " . $e->getMessage());
        header("Location: Book_parking.php?Error");
        exit();
    }
}
?>

