<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Passenger Dashboard</title>
  <style>
    body { background: #e0e0e0; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; }
    header { background: #555; color: white; padding: 20px; text-align: center; }
    .container { padding: 30px; max-width: 900px; margin: auto; }
    .card {
      background: #f3f3f3;
      margin-bottom: 20px;
      padding: 20px;
      border-left: 5px solid #aaa;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card h3 { margin-top: 0; color: #333; }
    .card button {
      padding: 8px 16px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      margin-top: 10px;
    }
    .card button:hover {
      background: #0056b3;
    }
    .search-form input {
      padding: 8px;
      margin: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .search-form button {
      padding: 8px 16px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
    }
    .search-form button:hover {
      background: #218838;
    }
  </style>
</head>
<body>
  <header>
    <h1>Passenger Dashboard</h1>
  </header>

  <div class="container">

    <div class="card">
      <h3>Search Rides</h3>
      <form class="search-form" method="GET" action="">
        <input type="text" name="origin" placeholder="Origin" value="<?= htmlspecialchars($_GET['origin'] ?? '') ?>">
        <input type="text" name="destination" placeholder="Destination" value="<?= htmlspecialchars($_GET['destination'] ?? '') ?>">
        <button type="submit">Search</button>
      </form>
    </div>

    <div class="card">
      <h3>Available Rides</h3>

      <?php
      include 'connection.php';

      $origin = $_GET['origin'] ?? '';
      $destination = $_GET['destination'] ?? '';

      // Build query
      $sql = "SELECT rides.*, drivers.name AS driver_name 
              FROM rides 
              JOIN drivers ON rides.driver_id = drivers.id
              WHERE 1=1";

      if (!empty($origin)) {
          $origin = mysqli_real_escape_string($conn, $origin);
          $sql .= " AND LOWER(rides.origin) LIKE LOWER('%$origin%')";
      }
      if (!empty($destination)) {
          $destination = mysqli_real_escape_string($conn, $destination);
          $sql .= " AND LOWER(rides.destination) LIKE LOWER('%$destination%')";
      }

      $sql .= " ORDER BY ride_time ASC";

      $result = mysqli_query($conn, $sql);

      echo "<p><strong>Total rides fetched:</strong> " . mysqli_num_rows($result) . "</p>";
      // Uncomment to debug SQL:
      // echo "<pre>$sql</pre>";

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<div class='card'>";
          echo "<h4>{$row['origin']} → {$row['destination']}</h4>";
          echo "<p><strong>Driver:</strong> {$row['driver_name']}</p>";
          echo "<p><strong>Time:</strong> " . date("d M Y, h:i A", strtotime($row['ride_time'])) . "</p>";
          echo "<p><strong>Seats Available:</strong> {$row['seats_available']}</p>";
          echo "<form method='POST' action='send_request.php'>";
          echo "<input type='hidden' name='ride_id' value='{$row['ride_id']}'>";
          echo "<button type='submit'>Request Ride</button>";
          echo "</form>";
          echo "</div>";
        }
      } else {
        echo "<p>No available rides for the selected route.</p>";
      }

      mysqli_close($conn);
      ?>
    </div>

    <div class="card">
      <h3>Profile Settings</h3>
      <p>Update your preferences and contact info.</p>
    </div>

  </div>
</body>
</html>
