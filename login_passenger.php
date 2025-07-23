<?php
session_start();
require 'connection.php'; // Ensure this contains $conn for DB connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT id, name, password FROM passengers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['passenger_id'] = $id;
                $_SESSION['passenger_name'] = $name;
                header("Location: passenger_dashboard.php");
                exit;
            } else {
                $message = "Invalid email or password.";
            }
        } else {
            $message = "Invalid email or password.";
        }

        $stmt->close();
    } else {
        $message = "Please fill in both fields.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Passenger Login - Peer-to-Peer Carpooling</title>
    <style>
        /* [Your same styling remains unchanged] */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f9; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
        header { background: linear-gradient(135deg, #333, #555); color: white; padding: 20px; text-align: center; position: relative; }
        header .logo img { width: 50px; height: 50px; vertical-align: middle; }
        nav { margin-top: 10px; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        nav a:hover { color: #c4c4c4; }
        .form-container { flex: 1; display: flex; justify-content: center; align-items: center; background: #e0e0e0; padding: 30px; background-image: url('https://www.toptal.com/designers/subtlepatterns/patterns/dark_denim.png'); background-size: cover; color: #333; }
        .form-container-inner { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .form-container-inner h2 { color: #333; margin-bottom: 20px; }
        .form-container-inner label { display: block; margin-top: 1rem; font-weight: 600; color: #333; }
        .form-container-inner input { width: 100%; padding: 0.8rem; margin-top: 0.5rem; border: 1px solid #ccc; border-radius: 5px; font-size: 1rem; background-color: #fff; color: #333; }
        .form-container-inner button { margin-top: 1.5rem; padding: 1rem 2rem; background-color: #333; color: white; font-weight: bold; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; width: 100%; font-size: 1.1rem; }
        .form-container-inner button:hover { background-color: #444; }
        .message { margin-top: 15px; font-weight: bold; color: red; }
        footer { background-color: #333; color: white; text-align: center; padding: 1rem; margin-top: 3rem; }
        footer p { margin: 0; }
        a { color: #333; text-decoration: none; }
        a:hover { color: #c4c4c4; }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Carpool Logo" id="logo" />
    </div>
    <nav>
        <a href="index.html">Home</a>
        <a href="register.html">Register</a>
    </nav>
</header>

<section class="form-container">
    <div class="form-container-inner">
        <h2>Passenger Login</h2>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.html">Register here</a></p>
    </div>
</section>

<footer>
    <p>© 2025 Peer-to-Peer Carpooling. All Rights Reserved.</p>
</footer>

</body>
</html>
