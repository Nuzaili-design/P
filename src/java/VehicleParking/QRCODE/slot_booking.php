<?php
session_start();
require 'vendor/autoload.php'; // Load necessary libraries
require 'SQLconnection.php'; // Your database connection file
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
            header("Location: Book_parking.php?Already");
            exit();
        } else {
            // Create folder for QR codes
            $path = "D://QRParking";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Generate unique QR code value
            $characters = "378AIJKLM5CD4NOP126EFGHB9";
            $codeval = substr(str_shuffle($characters), 0, 10);

            $pathQR = $path . "/" . $umail . ".png";

            // Create QR code
            QRGen::createQR($codeval, $pathQR);

            // Read QR code as binary data
            $imageData = file_get_contents($pathQR);

            // Insert into database
            $stmt = $con->prepare("INSERT INTO slot_booking (uname, uid, pdate, stime, phrs, umail, slot_name, time, endtime, pcost, image_data, codeval) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssb", $uname, $uid, $pdate, $stimeFormatted, $phrs, $umail, $slot_name, $etimeFormatted, $totalcost, $imageData, $codeval);

            if ($stmt->execute()) {
                header("Location: Book_parking.php?Slot_booked");
                exit();
            } else {
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
