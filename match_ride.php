<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['driver_id'])) {
    header("Location: login_driver.php");
    exit;
}

$ride_id = $_GET['ride_id'];

// Fetch ride details
$stmt = $conn->prepare("SELECT origin, destination, departure_time FROM rides WHERE id = ?");
$stmt->bind_param("i", $ride_id);
$stmt->execute();
$stmt->bind_result($origin, $destination, $departure_time);
$stmt->fetch();
$stmt->close();

// Match with passenger requests within ±60 mins
$stmt = $conn->prepare("SELECT id, passenger_id, origin, destination, requested_time FROM passenger_requests 
                        WHERE origin = ? AND destination = ? 
                        AND ABS(TIMESTAMPDIFF(MINUTE, requested_time, ?)) <= 60");
$stmt->bind_param("sss", $origin, $destination, $departure_time);
$stmt->execute();
$result = $stmt->get_result();

$matches = [];
while ($row = $result->fetch_assoc()) {
    $matches[] = $row;

    // Save match (optional)
    $insert = $conn->prepare("INSERT INTO ride_matches (ride_id, passenger_id, match_time) VALUES (?, ?, NOW())");
    $insert->bind_param("ii", $ride_id, $row['passenger_id']);
    $insert->execute();
    $insert->close();
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head><title>Matching Passengers</title></head>
<body>
    <h2>Matched Passenger Requests</h2>
    <?php if (count($matches) > 0): ?>
        <?php foreach ($matches as $m): ?>
            <div>
                <strong>Passenger ID:</strong> <?= $m['passenger_id'] ?><br>
                <strong>Origin:</strong> <?= $m['origin'] ?> |
                <strong>Destination:</strong> <?= $m['destination'] ?><br>
                <strong>Requested Time:</strong> <?= $m['requested_time'] ?><br><hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No matching passengers found.</p>
    <?php endif; ?>
</body>
</html>
