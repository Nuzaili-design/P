<?php
// Include the connection file
include 'db_connect.php';
session_start();

$conn = SQLConnection::getConnection();

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die("Access denied. Please login first.");
}

$uid = $_SESSION['uid'];

// Fetch user details from user_reg
$user_query = "SELECT name, email FROM user_reg WHERE id = :uid";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bindParam(':uid', $uid);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found!");
}

$uname = $user['name'];
$umail = $user['email'];

// Fetch parking cost details
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

// Fetch booked slots
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

$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_slots = $_POST['Slot'] ?? [];
    $pdate = $_POST['pdate'] ?? $selected_date;
    $stime = date("H:i:s", strtotime($_POST['stime']));
    $phrs = $_POST['phrs'] ?? 0;
    $endtime = date("H:i:s", strtotime("+$phrs hours", strtotime($stime)));

    foreach ($selected_slots as $slot) {
        // Check slot availability
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
            $_SESSION['error_message'] = "Slot $slot is already booked!";
            continue;
        }

        // Insert booking with auto-populated user details
        $insert_query = $conn->prepare("
            INSERT INTO slot_booking (uname, uid, pdate, stime, phrs, umail, slot_name, time, endtime, pcost, ustatus)
            VALUES (:uname, :uid, :pdate, :stime, :phrs, :umail, :slot_name, NOW(), :endtime, :pcost, 'No')
        ");

        $insert_query->execute([
            ':uname' => $uname,
            ':uid' => $uid,
            ':pdate' => $pdate,
            ':stime' => $stime,
            ':phrs' => $phrs,
            ':umail' => $umail,
            ':slot_name' => $slot,
            ':endtime' => $endtime,
            ':pcost' => $parking_cost
        ]);
        $success = true;
    }

    if ($success) {
        $_SESSION['success_message'] = "Slots booked successfully!";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code-based Smart Vehicle Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
   

    <!-- Template Stylesheet -->
    <style>
    body {
        background-color: #f8f9fa;
    }

    .booking-box {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control, .btn {
        border-radius: 8px;
    }

    
</style>

    
   
   
</head>
<body>

    


<div class="booking-box">
<h3 class="text-center">Confirm Your Booking</h3>
    <form method="POST" action="">

        <div class="form-group">
            <label>Selected Date:</label>
            <input type="date" class="form-control" name="pdate" value="<?php echo $selected_date; ?>" readonly required min="<?php echo date('Y-m-d'); ?>">
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
        <h5 class="mt-4 text-center">Select Your Slot</h5>
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
                    <?php for ($i = 1; $i <= 10; $i++) {
                        $slot = "Slot $i";
                        echo '<td>
                                <input type="checkbox" class="slectOne" name="Slot[]" value="' . $slot . '" ' . (in_array($slot, $booked_slots) ? 'disabled' : '') . '>
                              </td>';
                    } ?>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-md">Book Now</button>
        </div>
    </form>
</div>

<script>
    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire('Success!', '<?php echo $_SESSION["success_message"]; ?>', 'success').then(() => {
            window.location.href = 'your_bookings.php'; 
        });
        <?php unset($_SESSION['success_message']); endif; ?>
</script>




</body>
</html>