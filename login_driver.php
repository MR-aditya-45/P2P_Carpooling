<?php
session_start();
require 'connection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT id, password, name FROM drivers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed_password, $name);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Save session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_type'] = 'driver'; // used in driver_dashboard.php

                // Redirect to secure PHP dashboard
                header("Location: driver_dashboard.php");
                exit();
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
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Driver Login</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f7f9; }
header, footer { background: #333; color: white; text-align: center; padding: 20px; }
nav a { color: white; margin: 0 10px; text-decoration: none; }
.form-container { display: flex; justify-content: center; align-items: center; height: 80vh; }
.form-container-inner { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px; width: 100%; }
h2 { margin-bottom: 20px; }
input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
button { width: 100%; padding: 10px; background: #333; color: white; border: none; border-radius: 5px; font-size: 1rem; }
button:hover { background: #444; }
.message { color: red; margin: 10px 0; text-align: center; }
</style>
</head>
<body>

<header>
    <h1>Peer-to-Peer Carpooling</h1>
    <nav>
        <a href="index.html">Home</a>
        <a href="register.html">Register</a>
    </nav>
</header>

<div class="form-container">
    <div class="form-container-inner">
        <h2>Driver Login</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:15px;">Don't have an account? <a href="register.html">Register here</a></p>
    </div>
</div>

<footer>
    <p>© 2025 Peer-to-Peer Carpooling. All Rights Reserved.</p>
</footer>

</body>
</html>
