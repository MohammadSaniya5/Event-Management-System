<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'user' && $password === 'userpass') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $message = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login - EventSphere</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #141e30, #243b55);
        }

        .login-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .logo span {
            color: #00c3ff;
        }

        h2 {
            color: white;
            margin-bottom: 25px;
        }

        input {
            width: 93%;
            padding: 12px;
            margin-bottom: 18px;
            border: none;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
        }

        input:focus {
            box-shadow: 0 0 8px #00c3ff;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00c3ff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #009fd4;
        }

        .error {
            color: #ff4d4d;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .admin-tag {
            color: #ccc;
            font-size: 13px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <div class="logo">
            Event<span>Sphere</span>
        </div>
        <h2>Admin Login</h2>

        <?php if ($message): ?>
            <div class="error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="admin-tag">
            Secure Admin Access
        </div>
    </div>

</body>

</html>
