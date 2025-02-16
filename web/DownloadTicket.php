<?php
session_start();
include 'db_connect.php'; // Ensure database connection

$conn = SQLConnection::getConnection();

if (!isset($_GET['reqid'])) {
    die("Invalid request");
}

$reqid = $_GET['reqid'];

// Fetch booking details
$query = "SELECT * FROM slot_booking WHERE id = :reqid";
$stmt = $conn->prepare($query);
$stmt->bindParam(':reqid', $reqid);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("No booking found!");
}

// Format time to AM/PM
function formatTimeAMPM($time)
{
    return date("h:i A", strtotime($time));
}

$stime = formatTimeAMPM($booking['stime']);
$endtime = formatTimeAMPM($booking['endtime']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QR Code-based Smart Vehicle Parking Management System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .ticket {
            background-color: #fff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 5px solid red;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .ticket-content {
            flex-grow: 1;
            padding-left: 20px;
        }
        .ticket-heading {
            font-size: 24px;
            color: #007bff;
        }
        .ticket-details {
            margin-top: 20px;
        }
        .qr-code {
            text-align: center;
        }
        p {
            color: #000;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
        </a>
    </nav>

    <!-- Page Header -->
    <div class="container-fluid page-header mb-5 p-0">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <h1 class="display-5 text-white">Booking Ticket</h1>
            </div>
        </div>
    </div>

    <!-- Ticket Display -->
    <div class="container">
        <div class="col-md-12">
            <div class="ticket" id="content">
                <div class="ticket-content">
                    <h2 class="ticket-heading">Parking Ticket</h2>
                    <div class="ticket-details">
                        <p><strong>Date: </strong><?= htmlspecialchars($booking['pdate']) ?></p>
                        <p><strong>Time: </strong><?= $stime ?> - <?= $endtime ?></p>
                        <p><strong>Location: </strong> <?= htmlspecialchars($booking['slot_name']) ?></p>
                        <p><strong>Cost: </strong>Rs. <?= htmlspecialchars($booking['pcost']) ?></p>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="qr-code">
                    
                    <img src="qrpic.php?uid=<?= $uid; ?>" alt="QR Code" width="150">

                </div>
            </div>

            <!-- Download Button -->
            <center><button class="btn btn-success text-center" id="downloadBtn">Download</button></center>
        </div>
    </div>

    <!-- Footer -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5">
        <div class="container">
            <div class="copyright text-center">
                <p>&copy; QR Code-based Smart Vehicle Parking Management System</p>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        const contentDiv = document.getElementById('content');
        const downloadBtn = document.getElementById('downloadBtn');

        downloadBtn.addEventListener('click', () => {
            html2canvas(contentDiv, { scale: 2 }).then(canvas => {
                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                const downloadLink = document.createElement('a');
                downloadLink.href = dataUrl;
                downloadLink.download = 'Parking_Ticket.png';
                downloadLink.click();
            });
        });
    </script>
</body>
</html>
