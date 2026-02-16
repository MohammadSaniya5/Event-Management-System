<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
require 'db.php';

$stmt = $pdo->prepare("SELECT event_name, payment_date, payment_time, amount, utr FROM users_events WHERE user_id = ?");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
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

        .dashboard-container {
            background-color: rgb(246, 239, 244);
            padding: 40px;
            border-radius: 30px;
            border: 2px solid #333333;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        h1 {
            text-align: center;
            color: rgb(102, 33, 154);
            margin-bottom: 30px;
        }

        p {
            text-align: center;
            color: black;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 10px 8px;
            border: 1px solid #aaa;
            text-align: left;
        }

        th {
            background-color: rgb(209, 223, 245);
            color: #111;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
            gap: 15px;
        }

        .logout-btn {
            background-color: #005fa3;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            width: 120%;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 95, 163, 0.3);
        }

        .logout-btn:active {
            background-color: #004b80;
            transform: translateY(2px);
        }

        @media print {
            .button-group {
                display: none;
            }

            #bgVideo {
                display: none !important;
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
    <video autoplay muted loop id="bgVideo">
        <source src="v1.mp4" type="video/mp4">
    </video>
    <div class="dashboard-container">
        <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <p>Glad to have you here on your dashboard.</p>
        <?php if (!empty($events)): ?>
            <p>Your Registered Events</p>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Payment Date</th>
                        <th>Payment Time</th>
                        <th>Amount (₹)</th>
                        <th>UTR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['event_name']) ?></td>
                            <td><?= htmlspecialchars($event['payment_date']) ?></td>
                            <td><?= date('g:i A', strtotime($event['payment_time'])) ?></td>
                            <td><?= number_format($event['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($event['utr']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not enrolled in any events yet.</p>
        <?php endif; ?>
        <div class="button-group">
            <button type="button" onclick="printPage()">Print</button>
            <form action="logout.php" method="post" style="margin: 0;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>

</html>