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
    
     <!-- Favicon -->
     <link href="img/favicon.ico" rel="icon">

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet"> 

<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="lib/animate/animate.min.css" rel="stylesheet">
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Ubuntu', sans-serif;
    }

    .ticket-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 50px auto;
        max-width: 600px;
        display: grid;
        grid-template-columns: 1fr 200px;
        gap: 30px;
        align-items: center;
        transition: transform 0.3s ease;
    }

    .ticket-card:hover {
        transform: scale(1.01);
    }

    .ticket-details h2 {
        font-size: 26px;
        font-weight: 700;
        color: #007bff;
        margin-bottom: 20px;
    }

    .ticket-details p {
        font-size: 16px;
        color: #343a40;
        margin-bottom: 10px;
    }

    .qr-code img {
        width: 200px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 5px;
        background-color: #f8f9fa;
    }

    .btn-success {
        margin-top: 1px;
        border-radius: 20px;
        padding: 10px 10px;
        font-size: 16px;
        font-weight: 600;
    }
    .download-btn-wrapper {
    text-align: center;
    margin-top: 20px;
    width: 100%;
}

.download-btn {
    padding: 10px 20px;
    font-weight: 500;
    font-size: 16px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 128, 0, 0.2); /* Soft green shadow */
    transition: transform 0.2s ease;
}

.download-btn:hover {
    transform: translateY(-2px);
}


</style>

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fas fa-parking me-3"></i>Car Reservation System</h2>
        </a>
    </nav>

    

   <!-- Ticket Display -->
<div class="container">
    <div class="col-md-12">
        <div class="ticket-card" id="content">
            <!-- Ticket Info -->
            <div class="ticket-details">
                <h2>Parking Ticket</h2>
                <p><strong>Date:</strong> <?= htmlspecialchars($booking['pdate']) ?></p>
                <p><strong>Time:</strong> <?= $stime ?> - <?= $endtime ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($booking['slot_name']) ?></p>
                <p><strong>Cost:</strong> TL. <?= htmlspecialchars($booking['pcost']) ?></p>
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                <img src="qrpic.php?id=<?= htmlspecialchars($booking['id']); ?>" alt="QR Code" onerror="console.error('QR Code failed to load!')">
            </div>
            </div>
        

            <div class="download-btn-wrapper">
    <button class="btn btn-primary shadow download-btn" id="downloadBtn">
        <i class="fas fa-download me-2"></i>Download Ticket
    </button>
    <a href="your_bookings.php" class="btn btn-primary shadow download-btn">Go Back</a>
</div>

    </div>
</div>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


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
