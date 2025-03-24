<?php
require 'db_connect.php'; // Ensure database connection

$conn = SQLConnection::getConnection();

// Validate QR code input
if (!isset($_GET['codeval'])) {
    header("Location: TCHome.php?Invalid");
    exit();
}

$codeval = htmlspecialchars($_GET['codeval']);

try {
    // Check if ticket is already used
    $stmt1 = $conn->prepare("SELECT ustatus FROM slot_booking WHERE codeval = ?");
    $stmt1->execute([$codeval]);
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['ustatus'] === 'Yes') {
            header("Location: TCHome.php?AlreadyUsed"); // Correct response for already used ticket
            exit();
        }
    } else {
        header("Location: TCHome.php?Invalid"); // Invalid ticket
        exit();
    }

    // Now check if the ticket is within the valid time range
    $stmt2 = $conn->prepare("
        SELECT * FROM slot_booking 
        WHERE codeval = ? 
        AND NOW() BETWEEN CONCAT(pdate, ' ', stime) AND CONCAT(pdate, ' ', endtime)
    ");
    $stmt2->execute([$codeval]);

    if ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        // Mark ticket as used
        $stmt3 = $conn->prepare("UPDATE slot_booking SET ustatus = 'Yes' WHERE codeval = ?");
        $stmt3->execute([$codeval]);

        // Store user details
        $uid = htmlspecialchars($row['uid']);
        $uname = htmlspecialchars($row['uname']);
        $slot_name = htmlspecialchars($row['slot_name']);
        ?>

        <script>alert('✅ Ticket Verified!');</script>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Verify Parking Ticket</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/style.css" rel="stylesheet">
        </head>

        <body>
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
                <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
                    <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Vehicle Parking</h2>
                </a>
            </nav>

            <!-- Ticket Details Form -->
            <div class="container-xxl py-5">
                <div class="container text-center">
                    <h3 class="text-primary text-uppercase">// Parking Details //</h3>
                    <h1 class="mb-5">Enter Vehicle Number</h1>
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-6">
                            <form action="TCHome.php" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required name="vnumber" placeholder="Vehicle Number">
                                    <label for="vnumber">Vehicle Number</label>
                                </div>
                                <input type="hidden" name="codeval" value="<?= $codeval ?>">
                                <input type="hidden" name="uid" value="<?= $uid ?>">
                                <input type="hidden" name="uname" value="<?= $uname ?>">
                                <input type="hidden" name="slot_name" value="<?= $slot_name ?>">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="js/main.js"></script>
        </body>
        </html>

        <?php
    } else {
        header("Location: TCHome.php?NotValidTime"); // Ticket is expired or not valid
        exit();
    }
} catch (Exception $ex) {
    error_log($ex->getMessage());
    header("Location: TCHome.php?Error");
    exit();
}
?>
