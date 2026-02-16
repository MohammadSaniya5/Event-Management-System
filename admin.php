<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_utr'], $_POST['user_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE users_events SET utr_status = ? WHERE user_id = ?");
    $stmt->execute([$_POST['status'], $_POST['user_id']]);
}
$stmt = $pdo->query("
    SELECT 
        u.id, u.firstname, u.lastname, u.username, u.email, u.phone, u.gender, u.utr,
        GROUP_CONCAT(DISTINCT e.`event_name` SEPARATOR ', ') AS events,
        SUM(ue.amount) AS amount,
        MAX(ue.payment_date) AS payment_date,
        MAX(ue.payment_time) AS payment_time,
        MAX(ue.utr_status) AS utr_status
    FROM users u
    JOIN users_events ue ON u.id = ue.user_id
    JOIN events e ON ue.event_name = e.event_name
    GROUP BY u.id, u.firstname, u.lastname, u.username, u.email, u.phone, u.gender, u.utr
    ORDER BY payment_date DESC, payment_time DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Registered Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('p2.jpg');
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
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

        h2 {
            color: #005fa3;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #005fa3;
            color: white;
        }

        td {
            color: black;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-verified {
            color: green;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        .btn {
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid transparent;
            cursor: pointer;
            color: white;
            border-radius: 3px;
            margin: 2px;
            transition: background-color 0.2s ease;
        }

        .btn-verify {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-verify:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        form.inline {
            display: inline;
        }

        @media print {
            .button-group {
                display: none;
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

    <h2>Registered Users (Admin View)</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>UTR</th>
            <th>Events</th>
            <th>Amount</th>
            <th>Payment Date/Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $i => $user): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['phone']) ?></td>
                <td><?= htmlspecialchars($user['gender']) ?></td>
                <td><?= htmlspecialchars($user['utr']) ?></td>
                <td><?= htmlspecialchars($user['events']) ?></td>
                <td>₹<?= htmlspecialchars($user['amount']) ?></td>
                <td><?= $user['payment_date'] ?> / <?= $user['payment_time'] ?></td>
                <td>
                    <?php
                    $status = $user['utr_status'] ?? 'Pending';
                    echo "<span class='status-" . strtolower($status) . "'>$status</span>";
                    ?>
                </td>
                <td>
                    <form method="POST" class="inline">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <input type="hidden" name="status" value="Verified">
                        <button class="btn btn-verify" name="verify_utr">Verify</button>
                    </form>
                    <form method="POST" class="inline">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <input type="hidden" name="status" value="Rejected">
                        <button class="btn btn-reject" name="verify_utr">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table><br>
    <center><button onclick="printPage()" class="button-group">Print</button></center>
</body>

</html>