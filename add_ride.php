<?php
require 'auth/driver_auth.php';  // ✅ Already starts session and verifies

require 'connection.php'; // ✅ Connection file

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $origin = trim($_POST['origin'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $departure = trim($_POST['departure'] ?? '');
    $seats = intval($_POST['seats'] ?? 0);

    if ($origin && $destination && $departure && $seats > 0) {
        $driver_id = $_SESSION['user_id']; // ✅ Use correct session key from driver_auth.php

        $stmt = $conn->prepare("INSERT INTO rides (driver_id, origin, destination, ride_time, seats_available) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $driver_id, $origin, $destination, $departure, $seats);

        if ($stmt->execute()) {
            $message = "✅ Ride added successfully!";
        } else {
            $message = "❌ Failed to add ride.";
        }
        $stmt->close();
    } else {
        $message = "❗ Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Ride</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f1f1f1; margin: 0; padding: 0; }
    header { background: #444; color: white; padding: 20px; text-align: center; }
    .container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { margin-top: 0; }
    label { display: block; margin-top: 15px; }
    input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
    button { margin-top: 20px; padding: 10px 20px; background: #444; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .message { margin-top: 15px; color: green; }
  </style>
</head>
<body>
<header>
  <h1>Add a New Ride</h1>
</header>

<div class="container">
  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  
  <form action="" method="POST">
    <label for="origin">From (Origin):</label>
    <input type="text" id="origin" name="origin" required />

    <label for="destination">To (Destination):</label>
    <input type="text" id="destination" name="destination" required />

    <label for="departure">Departure Time:</label>
    <input type="datetime-local" id="departure" name="departure" required />

    <label for="seats">Available Seats:</label>
    <input type="number" id="seats" name="seats" min="1" max="6" required />

    <button type="submit">Add Ride</button>
  </form>
</div>
</body>
</html>
