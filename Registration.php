<?php
require 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $utr = trim($_POST['utr']);
    $events = $_POST['events'] ?? [];
    if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($phone) || empty($gender) || empty($password) || empty($confirm_password)) {
        $message = 'Please fill all fields.';
    } elseif (empty($utr)) {
        $message = 'Please provide a valid UTR (Transaction ID).';
    } elseif (!ctype_digit($phone) || strlen($phone) !== 10) {
        $message = 'Phone number must be exactly 10 digits.';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } elseif (empty($events)) {
        $message = 'Please select at least one event.';
    } elseif (!preg_match('/^[a-zA-Z0-9]{10,22}$/', $utr)) {
        $message = 'UTR must be 10–22 alphanumeric characters.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR utr = ?");
        $stmt->execute([$username, $email, $utr]);
        if ($stmt->rowCount() > 0) {
            $existingUser = $stmt->fetch();
            if ($existingUser['username'] === $username) {
                $message = 'Username already exists.';
            } elseif ($existingUser['email'] === $email) {
                $message = 'Email already registered.';
            } elseif ($existingUser['utr'] === $utr) {
                $message = 'This UTR has already been used. Please make a new payment.';
            } else {
                $message = 'User already exists.';
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, phone, gender, password,utr ) VALUES (?, ?, ?, ?, ?, ?, ? , ? )");
            if ($stmt->execute([$firstname, $lastname, $username, $email, $phone, $gender, $hashed_password, $utr])) {
                $user_id = $pdo->lastInsertId();
                $event_stmt = $pdo->prepare("INSERT INTO users_events (user_id, utr,event_name, amount, payment_date, payment_time) VALUES (?, ?, ?, ? , CURDATE(), CURTIME())");
                $totalAmount = 0;
                foreach ($events as $event_name) {
                    $event_details = getEventDetails($event_name);
                    if ($event_details && isset($event_details['amount'])) {
                        $amount = $event_details['amount'];
                        $event_stmt->execute([$user_id, $utr, $event_name, $amount]);
                    }
                }
                $message = 'Registration successful!';
                $firstname = $lastname = $username = $email = $phone = $gender = $utr = '';
            } else {
                $message = 'Registration failed. Please try again.';
            }
        }
    }
}
function getEventDetails($event_name)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_name = ?");
    $stmt->execute([$event_name]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
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
            height: 90px;
            box-sizing: border-box;
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

        .register-container {
            background-color: transparent;
            max-width: 400px;
            margin-top: 110px;
            width: 100%;
        }

        h2 {
            color: white;
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[name="utr"],
        select {
            width: 95%;
            padding: 12px;
            border: 2px solid rgb(6, 104, 169);
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 16px;
            outline: none;
            box-sizing: border-box;
            background-color: rgba(233, 225, 225, 0.8);
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #0077cc;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 119, 204, 0.2);
        }

        .button {
            width: 100%;
            padding: 14px;
            background-color: #005fa3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .dropdown-container {
            position: relative;
            margin-bottom: 15px;
            width: 95%;
        }

        .dropdown-btn {
            width: 100%;
            padding: 12px;
            background-color: rgba(233, 225, 225, 0.8);
            border: 2px solid rgb(6, 104, 169);
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            text-align: left;
            box-sizing: border-box;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            width: 100%;
            box-sizing: border-box;
            max-height: 150px;
            overflow-y: auto;
            border: 2px solid #d6e7f7;
            border-radius: 8px;
            margin-top: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 5;
        }

        .dropdown-content label {
            font-size: 14px;
            display: block;
            background-color: #ffffff;
            margin-bottom: 10px;
        }

        .dropdown-btn:focus,
        select:focus {
            background-color: #ffffff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: #555;
        }

        .footer a {
            color: rgba(227, 47, 11, 0.86);
            text-decoration: none;
        }

        p {
            text-align: center;
            color: rgb(238, 202, 24);
            font-size: 18px;
            font-weight: bold;
        }

        .category {
            margin-bottom: 10px;
        }

        .category-btn {
            width: 100%;
            padding: 10px;
            background-color: #e2eefc;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-align: left;
        }

        .event-list {
            display: none;
            padding-left: 10px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .register-container {
                width: 80%;
            }
        }

        h4 {
            color: yellow;
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
    <div class="register-container">
        <h2>Registration Form</h2>
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="Registration.php">
            <input type="text" name="firstname" placeholder="First Name"
                value="<?= htmlspecialchars($firstname ?? '') ?>" required>
            <input type="text" name="lastname" placeholder="Last Name" value="<?= htmlspecialchars($lastname ?? '') ?>"
                required>
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>"
                required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone ?? '') ?>"
                required>
            <select name="gender" required>
                <option value="" disabled selected hidden>Gender</option>
                <option value="Male" <?= (isset($gender) && $gender === 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($gender) && $gender === 'Female') ? 'selected' : '' ?>>Female</option>
            </select>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <div class="dropdown-container">
                <button type="button" class="dropdown-btn" id="eventDropdownBtn" onclick="toggleDropdown()">List of
                    Events</button>
                <div class="dropdown-content" id="dropdownContent" style="display: none;">
                    <div class="category">
                        <button type="button" class="category-btn">Technical</button>
                        <div class="event-list">
                            <label><input type="checkbox" name="events[]" value="Code relay"> Code relay - ₹100</label>
                            <label><input type="checkbox" name="events[]" value="Decode emoji"> Decode emoji -
                                ₹150</label>
                            <label><input type="checkbox" name="events[]" value="Tech Rapid fire"> Tech Rapid fire -
                                ₹200</label>
                            <label><input type="checkbox" name="events[]" value="Reverse engineering"> Reverse
                                engineering - ₹250</label>
                        </div>
                    </div>
                    <div class="category">
                        <button type="button" class="category-btn">Communication</button>
                        <div class="event-list">
                            <label><input type="checkbox" name="events[]"
                                    value="Listening to audio and answering questions"> Listening to audio & answering -
                                ₹50</label>
                            <label><input type="checkbox" name="events[]" value="Creative writing"> Creative writing -
                                ₹200</label>
                        </div>
                    </div>
                    <div class="category">
                        <button type="button" class="category-btn">Arts</button>
                        <div class="event-list">
                            <label><input type="checkbox" name="events[]" value="Art from waste"> Art from waste -
                                ₹50</label>
                            <label><input type="checkbox" name="events[]" value="Meme guessing"> Meme guessing -
                                ₹80</label>
                        </div>
                    </div>
                    <div class="category">
                        <button type="button" class="category-btn">Sports</button>
                        <div class="event-list">
                            <label><input type="checkbox" name="events[]" value="Badminton"> Badminton - ₹250</label>
                            <label><input type="checkbox" name="events[]" value="Bgmi"> Bgmi - ₹200</label>
                            <label><input type="checkbox" name="events[]" value="Room cricket"> Room cricket -
                                ₹150</label>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <h4>Scan QR to Pay</h4>
                    <img src="qr.jpeg" alt="Scan to pay"
                        style="width: 100%; max-width: 180px; height: auto; border: 2px solid #0077cc; border-radius: 10px;">
                    <p>After payment, enter your UTR/Transaction ID below:</p>
                    <input type="text" name="utr" placeholder="Enter UTR/Transaction ID"
                        value="<?= htmlspecialchars($utr ?? '') ?>" required
                        style="margin-top: 10px; width: 95%; padding: 14px; border: 2px solid rgb(6, 104, 169); border-radius: 8px;">
                </div>
                <button type="submit" class="button">Register</button>

                <div class="footer">
                    <p>Already registered? <a href="index.php">Login</a></p>
                </div>
        </form>
    </div>
    <script>
        const dropdownBtn = document.getElementById('eventDropdownBtn');
        const checkboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]');
        window.onclick = function (event) {
            if (!event.target.closest('.dropdown-container')) {
                document.querySelector('.dropdown-content').style.display = 'none';
            }
        };

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                dropdownBtn.textContent = selected > 0 ? `${selected} event${selected > 1 ? 's' : ''} selected` : 'Select Events';
            });
        });
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const eventList = this.nextElementSibling;
                eventList.style.display = eventList.style.display === 'block' ? 'none' : 'block';
            });
        });
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownContent');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

    </script>
</body>

</html>