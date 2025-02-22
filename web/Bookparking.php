<?php
// Include the connection file
include 'db_connect.php';
session_start();

$conn = SQLConnection::getConnection();

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please login first.");
}

$uid = $_SESSION['uid']; // Secure user ID from session

// Fetch parking cost details from the database
$cost_query = "SELECT * FROM parking_cost";
$cost_result = $conn->query($cost_query);
$costs = [];
while ($row = $cost_result->fetch(PDO::FETCH_ASSOC)) {
    $costs[$row['hour']] = $row['cost'];
}

// Get session values
$selected_date = $_SESSION['selected_date'] ?? '';
$selected_time = $_SESSION['selected_time'] ?? '';
$selected_hours = $_SESSION['selected_hours'] ?? '';
$hourly_cost = $costs[1] ?? 0;
$parking_cost = $selected_hours * $hourly_cost;

$endtime = date("H:i:s", strtotime("+$selected_hours hours", strtotime($selected_time)));

// Fetch booked slots and ensure availability based on time overlap
$booked_slots = [];
$booking_query = "SELECT slot_name FROM slot_booking 
                  WHERE pdate = :pdate 
                  AND (
                      (:selected_time >= stime AND :selected_time < endtime)  
                      OR 
                      (:endtime > stime AND :endtime <= endtime)  
                      OR
                      (stime >= :selected_time AND stime < :endtime)  
                  )";

$stmt = $conn->prepare($booking_query);
$stmt->bindParam(':pdate', $selected_date);
$stmt->bindParam(':selected_time', $selected_time);
$stmt->bindParam(':endtime', $endtime);
$stmt->execute();
$booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_slots = $_POST['Slot'] ?? [];
    $uname = htmlspecialchars($_POST['uname'] ?? '');
    $umail = htmlspecialchars($_POST['umail'] ?? '');
    $pdate = $_POST['pdate'] ?? $selected_date;
    $stime = date("H:i:s", strtotime($_POST['stime']));
    $phrs = $_POST['phrs'] ?? 0;
    $endtime = date("H:i:s", strtotime("+$phrs hours", strtotime($stime)));

    foreach ($selected_slots as $slot) {
        // Check if slot is already booked for overlapping time
        $checkQuery = "SELECT COUNT(*) FROM slot_booking 
                       WHERE slot_name = :slot_name 
                       AND pdate = :pdate 
                       AND (stime < :endtime AND endtime > :stime)";

        $stmt = $conn->prepare($checkQuery);
        $stmt->bindParam(':slot_name', $slot);
        $stmt->bindParam(':pdate', $pdate);
        $stmt->bindParam(':stime', $stime);
        $stmt->bindParam(':endtime', $endtime);
        $stmt->execute();
        $slotAlreadyBooked = $stmt->fetchColumn();

        if ($slotAlreadyBooked > 0) {
            echo "<script>alert('Slot $slot is already booked for this time!');</script>";
            continue;
        }

        // Insert into database
        $insert_query = $conn->prepare("
            INSERT INTO slot_booking (uname, uid, pdate, stime, phrs, umail, slot_name, time, endtime, pcost, ustatus)
            VALUES (:uname, :uid, :pdate, :stime, :phrs, :umail, :slot_name, :stime, :endtime, :pcost, 'No')
        ");

        $insert_query->bindParam(':uname', $uname);
        $insert_query->bindParam(':uid', $uid);
        $insert_query->bindParam(':pdate', $pdate);
        $insert_query->bindParam(':stime', $stime);
        $insert_query->bindParam(':phrs', $phrs);
        $insert_query->bindParam(':umail', $umail);
        $insert_query->bindParam(':slot_name', $slot);
        $insert_query->bindParam(':endtime', $endtime);
        $insert_query->bindParam(':pcost', $parking_cost);

        $insert_query->execute();
    }

    echo "<script>alert('Slots booked successfully!'); window.location.href='your_bookings.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code-based Smart Vehicle Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <form method="POST" action="">

            <div class="form-group">
                <label for="uname">User Name:</label>
                <input type="text" class="form-control" id="uname" name="uname" required>
            </div>

            <div class="form-group">
                <label for="umail">User Email:</label>
                <input type="email" class="form-control" id="umail" name="umail" required>
            </div>

            <div class="form-group">
                <label>Selected Date:</label>
                <input type="date" class="form-control" name="pdate" value="<?php echo $selected_date; ?>" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Selected Start Time:</label>
                <input type="text" class="form-control" name="stime" value="<?php echo $selected_time; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Parking Hours:</label>
                <input type="text" class="form-control" name="phrs" value="<?php echo $selected_hours; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Parking Cost (in Turkish lire):</label>
                <input type="text" class="form-control" value="<?php echo $parking_cost; ?>" readonly>
            </div>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Slot</th>
                        <?php for ($i = 1; $i <= 10; $i++) { echo "<th>Slot $i</th>"; } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: black;">A</td>
                        <?php
                        for ($i = 1; $i <= 10; $i++) {
                            $slot = "Slot $i";
                            echo '<td>
                                    <input type="checkbox" class="slectOne" name="Slot[]" value="' . $slot . '" ' . (in_array($slot, $booked_slots) ? 'disabled' : '') . '>
                                  </td>';
                        }
                        ?>
                    </tr>
                </tbody>
            </table>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-md">Book</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
