<?php
include 'connection.php';

$sql = "SELECT rides.*, drivers.name AS driver_name 
        FROM rides 
        JOIN drivers ON rides.driver_id = drivers.id
        WHERE ride_time >= NOW()
        ORDER BY ride_time ASC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='card'>";
    echo "<h3>{$row['origin']} → {$row['destination']}</h3>";
    echo "<p><strong>Driver:</strong> {$row['driver_name']}</p>";
    echo "<p><strong>Time:</strong> " . date("d M Y, h:i A", strtotime($row['ride_time'])) . "</p>";
    echo "<p><strong>Seats Available:</strong> {$row['seats_available']}</p>";
    echo "<button>Request Ride</button>";
    echo "</div>";
  }
} else {
  echo "<p>No available rides at the moment.</p>";
}
?>
