<?php
// Include the connection file
include 'db_connect.php';
session_start();

$conn = SQLConnection::getConnection();

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die('Access denied. Please login first.');
}

$uid = $_SESSION['uid'];

// Fetch user details from user_reg
$user_query = 'SELECT name, email FROM user_reg WHERE id = :uid';
$user_stmt = $conn->prepare($user_query);
$user_stmt->bindParam(':uid', $uid);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found!');
}

$uname = $user['name'];
$umail = $user['email'];

// Fetch parking cost details
$cost_query = 'SELECT * FROM parking_cost';
$cost_result = $conn->query($cost_query);
$costs = [];
while ($row = $cost_result->fetch(PDO::FETCH_ASSOC)) {
    $costs[$row['hour']] = $row['cost'];
}

// Get session values
$selected_date  = $_SESSION['selected_date'] ?? '';
$selected_time  = $_SESSION['selected_time'] ?? '';
$selected_hours = $_SESSION['selected_hours'] ?? '';
$hourly_cost    = $costs[1] ?? 0;
$parking_cost   = $selected_hours * $hourly_cost;

$endtime = date('H:i:s', strtotime("+{$selected_hours} hours", strtotime($selected_time)));

// Fetch booked slots
$booked_slots = [];
$booking_query = 'SELECT slot_name FROM slot_booking
                  WHERE pdate = :pdate
                  AND (
                      (:selected_time >= stime AND :selected_time < endtime)
                      OR
                      (:endtime > stime AND :endtime <= endtime)
                      OR
                      (stime >= :selected_time AND stime < :endtime)
                  )';

$stmt = $conn->prepare($booking_query);
$stmt->bindParam(':pdate', $selected_date);
$stmt->bindParam(':selected_time', $selected_time);
$stmt->bindParam(':endtime', $endtime);
$stmt->execute();
$booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Define floors and slot count
$floors = [1, 2, 3];
$slotsPerFloor = 10;
$success = false;

$blockLabels = ['A', 'B', 'C'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_slots = $_POST['Slot'] ?? [];
    $pdate = $_POST['pdate'] ?? $selected_date;
    $stime = date('H:i:s', strtotime($_POST['stime']));
    $phrs  = $_POST['phrs'] ?? 0;
    $endtime = date('H:i:s', strtotime("+{$phrs} hours", strtotime($stime)));

    $conn->beginTransaction();
    try {
        $checkStmt = $conn->prepare(
            'SELECT COUNT(*) FROM slot_booking
             WHERE slot_name = :slot_name
               AND pdate = :pdate
               AND (stime < :endtime AND endtime > :stime)'
        );

        $insertStmt = $conn->prepare(
            "INSERT INTO slot_booking
                         (uname, uid, pdate, stime, phrs, umail, slot_name, time, endtime, pcost, ustatus)
            VALUES
                         (:uname, :uid, :pdate, :stime, :phrs, :umail, :slot_name, NOW(), :endtime, :pcost, 'No')"

        );

        foreach ($selected_slots as  $slot) { 

            $checkStmt->execute([
                ':slot_name' => $slot,
                ':pdate'     => $pdate,
                ':stime'     => $stime,
                ':endtime'   => $endtime,
            ]);
            if ($checkStmt->fetchColumn() > 0) {
                throw new Exception("Slot {$slot} is already booked!");
            }

            $insertStmt->execute([
                ':uname'     => $uname,
                ':uid'       => $uid,
                ':pdate'     => $pdate,
                ':stime'     => $stime,
                ':phrs'      => $phrs,
                ':umail'     => $umail,
                ':slot_name' => $slot,
                ':endtime'   => $endtime,
                ':pcost'     => $parking_cost,
            ]);
        }
        $conn->commit();
        $_SESSION['success_message'] = 'Slots booked successfully!';
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = $e->getMessage();
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
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

<!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
  
  
  @media (max-width: 768px) {
  .card-body label {
    font-size: 13px;
    padding: 6px 0;
  }
}
.form-control, .btn {
            border-radius: 8px;
        }
        .form-control:focus {
    border-color: #4a00e0;
    box-shadow: 0 0 0 0.15rem rgba(74, 0, 224, 0.25); /* subtle gradient glow */
    outline: none;
}
        select.form-control {
            height: 40px;
            font-size: 14px;
        }


    </style>
    
</head>
<body>

  <!-- Spinner Start -->
  <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="#" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-calendar-check me-3"></i>Parking System</h2>
        </a>
   
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
      <div class="card shadow-lg border-0 rounded-4 p-4">
        <h3 class="text-primary">Confirm Your Booking</h3>
        <br>

        <?php if (isset($_SESSION['error_message'])): ?>
          <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
              <label class="form-label">Selected Date:</label>
              <input type="date" class="form-control" name="pdate" value="<?= $selected_date; ?>" readonly required min="<?= date('Y-m-d'); ?>">
          </div>
          <div class="mb-3">
              <label class="form-label">Selected Start Time:</label>
              <input type="text" class="form-control" name="stime" value="<?= $selected_time; ?>" readonly>
          </div>
          <div class="mb-3">
              <label class="form-label">Parking Hours:</label>
              <input type="text" class="form-control" name="phrs" value="<?= $selected_hours; ?>" readonly>
          </div>
          <div class="mb-3">
              <label class="form-label">Parking Cost (₺):</label>
              <input type="text" class="form-control" value="<?= $parking_cost; ?>" readonly>
          </div>

         <!-- Desktop View (Grid Style for Better Visuals) -->
<div class="d-none d-md-block mb-4">
  <?php foreach ($floors as $floorIndex => $floor): ?>
    <h5 class="mb-2">Floor <?= $floor; ?></h5>
    <div class="d-grid mb-3" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.5rem;">
      <?php for ($j = 1; $j <= $slotsPerFloor; $j++):
        $label = $blockLabels[$floorIndex] . '-' . $j;
        $disabled = in_array($label, $booked_slots) ? 'disabled' : '';
      ?>
        <div class="text-center">
          <input class="btn-check" type="radio" name="Slot[]" id="slot-<?= $label; ?>" value="<?= $label; ?>" <?= $disabled; ?> <?= ($j == 1 && $floorIndex == 0) ? 'required' : '' ?>>
          <label class="btn btn-outline-primary w-100 py-2 small <?= $disabled ? 'btn-secondary text-white' : '' ?>" for="slot-<?= $label; ?>"><?= $label; ?></label>
        </div>
      <?php endfor; ?>
    </div>

    <?php if ($floorIndex < count($floors) - 1): ?>
      <div class="floor-divider my-4"></div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

          <!-- Mobile View (Cards) -->
          <div class="d-md-none">
            <?php foreach ($floors as $floorIndex => $floor): ?>
              <h6 class="mt-3">Floor <?= $floor; ?></h6>
              <div class="row g-2 mb-2">
                <?php for ($j = 1; $j <= $slotsPerFloor; $j++):
                  $label = $blockLabels[$floorIndex] . '-' . $j;
                  $disabled = in_array($label, $booked_slots) ? 'disabled' : '';
                ?>
                  <div class="col-4">
                    <div class="card text-center border-<?= $disabled ? 'secondary' : 'primary'; ?> <?= $disabled ? 'bg-light' : ''; ?>">
                      <div class="card-body py-2 px-1">
                        <input class="btn-check" type="radio" name="Slot[]" id="mobile-slot-<?= $label; ?>" value="<?= $label; ?>" autocomplete="off" <?= $disabled; ?>>
                        <label class="btn w-100 small <?= $disabled ? 'btn-danger disabled' : 'btn-outline-primary'; ?>" for="mobile-slot-<?= $label; ?>"><?= $label; ?></label>

                      </div>
                    </div>
                  </div>
                <?php endfor; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <div>
            <button type="submit" class="btn btn-primary px-4">Book Now</button>

            <a href="Book_parking.php" class="btn btn-primary px-4">Go Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire('Success!', '<?php echo $_SESSION['success_message']; ?>', 'success').then(() => {
            window.location.href = 'your_bookings.php';
        });
    <?php unset($_SESSION['success_message']); endif; ?>
</script>

 <!-- JavaScript Libraries -->
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
 
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>