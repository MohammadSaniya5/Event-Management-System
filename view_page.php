<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$query = $pdo->prepare("
    SELECT event_name, username
    FROM users_events
    JOIN users ON users_events.user_id = users.id
    ORDER BY event_name, username
");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($rows as $row) {
    $events[$row['event_name']][] = $row['username'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Enrollments</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-image: url('\p2.jpg');
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
            margin-top: 10px;
            margin-left: 20px;
        }

        .back-btn {
            background-color: #005fa3;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .back-btn:hover {
            background-color: #003f73;
        }


        .container {
            max-width: 850px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border: solid #007bff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.1);
        }

        h2 {
            color: rgb(118, 12, 98);
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .event {
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
            padding-left: 15px;
            background-color: rgb(205, 228, 247);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
        }

        .event h3 {
            margin: 0;
            color: #0056b3;
            font-size: 18px;
        }

        .event ul {
            margin-top: 10px;
            list-style: none;
            padding-left: 0;
        }

        .event li {
            margin-bottom: 5px;
            color: solid #333;
            font-size: 15px;

        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;

            }
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>

<body>
    <div class="top-bar">
        <a href="admin_dashboard.php" class="back-btn">← Back</a>
    </div>
    <div class="container">
        <h2> Admin Dashboard</h2>
        <p>Welcome, Admin! Below is the list of enrolled users for each event:</p>

        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event => $users): ?>
                <div class="event">
                    <h3> <?= htmlspecialchars($event) ?></h3>
                    <ul>
                        <?php foreach ($users as $user): ?>
                            <li>👤 <?= htmlspecialchars($user) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#555;">No enrollments found.</p>
        <?php endif; ?>
        <center>
            <button onclick="printPage()" class="print-btn">Print</button>
        </center>
        <div class="footer">
            &copy; <?= date('Y') ?> | Admin View
        </div>
    </div>
</body>

</html>