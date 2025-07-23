<?php
require 'connection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $car = trim($_POST['car'] ?? '');
    $plate = trim($_POST['plate'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $phone && $car && $plate && $password) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM drivers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "This email is already registered.";
        } else {
            $stmt->close();

            // Hash the password
            // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);


            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO drivers (name, email, phone, car_model, plate_number, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $phone, $car, $plate, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Driver Registration</title>
  <style>
    /* (Same CSS as before, unchanged) */
    body {
      font-family: Arial, sans-serif;
      margin: 0; padding: 0;
      background-color: #f4f4f4;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background-color: #333;
      color: white;
    }
    header .logo img { width: 120px; }
    header nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-size: 1.1rem;
    }
    header nav a:hover { color: #ff8c00; }
    section {
      padding: 40px;
      background-color: white;
      max-width: 600px;
      margin: 50px auto;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    label {
      margin: 10px 0 5px;
      font-weight: bold;
      color: #333;
    }
    input {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    button {
      padding: 12px 20px;
      background-color: #ff8c00;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }
    button:hover {
      background-color: #e07b00;
    }
    .message {
      text-align: center;
      font-size: 1rem;
      margin-bottom: 15px;
      color: red;
    }
    .success { color: green; }
    footer {
      text-align: center;
      padding: 15px;
      background-color: #333;
      color: white;
      margin-top: 40px;
    }
    footer p { font-size: 0.9rem; }
  </style>
</head>
<body>

  <header>
    <div class="logo">
      <img src="logo.png" alt="Carpool Logo" />
    </div>
    <nav>
      <a href="index.html">Home</a>
      <a href="login_driver.php">Login</a>
    </nav>
  </header>

  <section>
    <h2>Driver Registration</h2>

    <?php if ($message): ?>
      <div class="message <?= strpos($message, 'successful') !== false ? 'success' : '' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form id="driverForm" action="" method="POST">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

      <label for="car">Car Model:</label>
      <input type="text" id="car" name="car" required value="<?= htmlspecialchars($_POST['car'] ?? '') ?>">

      <label for="plate">Car Plate Number:</label>
      <input type="text" id="plate" name="plate" required value="<?= htmlspecialchars($_POST['plate'] ?? '') ?>">

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Register</button>
    </form>
  </section>

  <footer>
    <p>© 2025 Peer-to-Peer Carpooling. All Rights Reserved.</p>
  </footer>

</body>
</html>
