<?php
require 'connection.php';

$message = "";

// Only process form if it's a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirmPassword'] ?? '';

    if ($name && $email && $phone && $password && $password === $confirm) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM passengers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO passengers (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);
            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $message = "Please fill all fields correctly.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styles same as before */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0; padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #333;
            color: white;
        }

        header .logo img {
            width: 120px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }

        header nav a:hover {
            color: #ff8c00;
        }

        .form-container {
            padding: 40px;
            background-color: white;
            max-width: 600px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px;
            background-color: #ff8c00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e07b00;
        }

        .error, .success {
            text-align: center;
            font-size: 0.95rem;
            margin: 10px 0;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <nav>
            <a href="index.html">Home</a>
            <a href="login_passenger.html">Login</a>
        </nav>
    </header>

    <section class="form-container">
        <h2>Passenger Registration</h2>

        <?php if ($message): ?>
            <div class="<?php echo str_contains($message, 'successful') ? 'success' : 'error'; ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <button type="submit">Register</button>
        </form>

        <p style="text-align:center;">Already have an account? <a href="login.html">Login here</a></p>
    </section>

    <footer>
        <p>© 2025 Peer-to-Peer Carpooling. All Rights Reserved.</p>
    </footer>
</body>
</html>
