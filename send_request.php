<?php
include 'connection.php';
session_start();

// Check if user is logged in and data is sent
if (!isset($_SESSION['passenger_id']) || !isset($_POST['ride_id'])) {
    echo "Invalid request.";
    exit;
}

$ride_id = mysqli_real_escape_string($conn, $_POST['ride_id']);
$passenger_id = $_SESSION['passenger_id'];

// Optional: Check if request already exists
$check_sql = "SELECT * FROM ride_requests WHERE ride_id = '$ride_id' AND passenger_id = '$passenger_id'";
$check_result = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_result) > 0) {
    echo "You have already requested this ride.";
    exit;
}

// Insert new request
$sql = "INSERT INTO ride_requests (ride_id, passenger_id, status)
        VALUES ('$ride_id', '$passenger_id', 'pending')";

if (mysqli_query($conn, $sql)) {
    echo "✅ Ride request sent successfully!<br><a href='passenger_dashboard.php'>Back to Dashboard</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
