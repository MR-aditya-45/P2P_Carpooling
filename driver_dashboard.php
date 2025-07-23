<?php
// Session & Auth Check
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'driver') {
    header("Location: login.html");
    exit();
}

// Database connection
include 'auth/driver_auth.php'; 

include 'connection.php';
// session_start();
// $driver_id = $_SESSION['driver_id'];

// $sql = "SELECT rr.request_id, rr.status, p.name AS passenger_name, r.origin, r.destination
//         FROM ride_requests rr
//         JOIN rides r ON rr.ride_id = r.ride_id
//         JOIN passengers p ON rr.passenger_id = p.passenger_id
//         WHERE r.driver_id = '$driver_id' AND rr.status = 'pending'";

// $result = mysqli_query($conn, $sql);

// echo "<h2>Pending Requests</h2>";
// while ($row = mysqli_fetch_assoc($result)) {
//     echo "Passenger: " . $row['passenger_name'] . " | Route: " . $row['origin'] . " → " . $row['destination'];
//     echo "<form action='accept_request.php' method='POST'>
//             <input type='hidden' name='request_id' value='" . $row['request_id'] . "'>
//             <button type='submit'>Accept</button>
//           </form><hr>";
// }
// Fetch driver's info
$driver_id = $_SESSION['user_id'];
$query = "SELECT * FROM drivers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$driver = $result->fetch_assoc();

// Dummy listing info (replace with your rides table later)
// $from = "12.9716, 77.5946";
// $to = "13.0827, 80.2707";
// $departure = "2025-05-20 08:30 AM";
// $available_seats = 3;
// $pending_requests = 2;
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Driver Dashboard</title>
  <style>
    body {
      background: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }
    header {
      background: #444;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      padding: 30px;
      max-width: 900px;
      margin: auto;
    }
    .card {
      background: #f9f9f9;
      margin-bottom: 20px;
      padding: 20px;
      border-left: 5px solid #888;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card h3 {
      margin-top: 0;
      color: #333;
    }
    .info-label {
      font-weight: bold;
    }
    .logout-btn {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 15px;
      background-color: #d9534f;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .logout-btn:hover {
      background-color: #c9302c;
    }
  </style>
</head>
<body>
  <header>
    <a href="add_ride.php">
    <div style="text-align:center; margin-bottom: 20px;">
  <a href="add_ride.php" style="text-decoration:none;">
    <button style="padding:10px 20px; font-size:1rem; border:none; background:#333; color:#fff; border-radius:5px;">➕ Add New Ride</button>
  </a>
</div>

    <h1>Welcome, <?php echo htmlspecialchars($driver['name']); ?>!</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <div class="container">

    <!-- <div class="card">
      <h3>My Carpool Listing</h3>
      <p><span class="info-label">From:</span> <?php echo $from; ?></p>
      <p><span class="info-label">To:</span> <?php echo $to; ?></p>
      <p><span class="info-label">Departure:</span> <?php echo $departure; ?></p>
      <p><span class="info-label">Available Seats:</span> <?php echo $available_seats; ?></p>
    </div> -->
<!-- 
    <div class="card">
      <h3>Ride Requests</h3>
      <p>✅ You have <strong><?php echo $pending_requests; ?></strong> new ride requests pending approval.</p>
    </div> -->

    <div class="card">
      <h3>Profile & Vehicle Info</h3>
      <p><span class="info-label">Name:</span> <?php echo htmlspecialchars($driver['name']); ?></p>
      <p><span class="info-label">Car Model:</span> <?php echo htmlspecialchars($driver['car_model']); ?></p>
      <p><span class="info-label">Plate Number:</span> <?php echo htmlspecialchars($driver['plate_number']); ?></p>
    </div>
  </div>
</body>
</html>
