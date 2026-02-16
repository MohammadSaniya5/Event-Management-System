<?php
require 'db.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = 'Incorrect username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 220px 0 0 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        #bgVideo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.75);
            padding: 25px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(8px);
            border-bottom: 2px solid #9b00a3;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .logo-text span {
            color: #00c3ff;
        }

        .tagline {
            font-size: 14px;
            color: #ddd;
        }

        .login-container {
            background: transparent;
            margin-top: -50px;
            padding: 40px;
            border-radius: 30px;
            width: 360px;
        }

        h2 {
            text-align: center;
            color: rgb(225, 230, 234);
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid rgb(18, 66, 139);
            background-color: rgba(233, 225, 225, 0.8);
        }

        .button {
            background-color: #005fa3;
            color: white;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 15px;
            cursor: pointer;
        }

        .message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        .footer a {
            color: rgb(163, 30, 0);
            text-decoration: none;
        }

        p {
            text-align: center;
            color: rgb(238, 202, 24);
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <video autoplay muted loop id="bgVideo">
        <source src="v1.mp4" type="video/mp4">
    </video>
    <header>
        <div>
            <div class="logo-text">
                Event<span>Sphere</span>
            </div>

        </div>
    </header>

    <div class="login-container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
            <div style="text-align: right; margin-bottom: 10px;">
                <a href="password.php" style="color: #fff; text-decoration: none;">Forgot Password?</a>
            </div>
            <div class="footer">
                <p>Don't have an account? <a href="Registration.php">Register here</a></p>
            </div>
        </form>
    </div>
</body>

</html>