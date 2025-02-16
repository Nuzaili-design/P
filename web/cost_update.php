<?php
// Include database connection
include 'db_connect.php';
session_start();


// Get the new parking cost from the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pcost'])) {
    $pcost = $_POST['pcost'];

    try {
        // Establish database connection
        $conn = SQLConnection::getConnection();
        $query = "UPDATE parking_cost SET cost = :pcost";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pcost', $pcost, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Redirect back to ParkingCost.php with success message
            header("Location: ParkingCost.php?Cost_updated");
        } else {
            // Redirect back with failure message
            header("Location: ParkingCost.php?Failed");
        }
        exit;
    } catch (Exception $ex) {
        error_log($ex->getMessage()); // Log error for debugging
        header("Location: ParkingCost.php?Failed");
        exit;
    }
} else {
    // Redirect if accessed incorrectly
    header("Location: ParkingCost.php");
    exit;
}
?>
