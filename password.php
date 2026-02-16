<?php
require 'db.php';
$message = '';
$showResetForm = false;
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_user'])) {
        $username = trim($_POST['username']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $showResetForm = true;
        } else {
            $message = "Username not found.";
        }
    }
    if (isset($_POST['reset_password'])) {
        $username = $_POST['username'];
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $message = "Passwords do not match.";
            $showResetForm = true;
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$hashedPassword, $username]);
            $message = "Password has been successfully updated.<br> <a href='index.php'>Back</a>.";
            $showResetForm = false;
            $passwordUpdated = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('p3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            text-align: center;
            padding: 100px;
            align-items: center;
        }

        form {
            background: white;
            padding: 50px;
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        input {
            padding: 10px;
            width: 250px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            margin-top: 40px;
            color: white;
            font-size: 30px;
        }

        a,
        h2 {
            color: yellow;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <h2>Forgot Password</h2>
    <div class="message"><?= $message ?></div><br>
    <?php if (!$showResetForm && !$passwordUpdated): ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter your username" required>
            <br>
            <button type="submit" name="check_user">Continue</button>
        </form>
    <?php elseif ($showResetForm): ?>
        <form method="POST">
            <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <br>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <br>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    <?php endif; ?>
</body>

</html>