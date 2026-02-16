<?php
require 'db.php';
$categories = $pdo->query("SELECT DISTINCT category FROM events")->fetchAll(PDO::FETCH_COLUMN);
$selectedCategory = $_POST['category'] ?? '';
$selectedEvent = $_POST['event'] ?? '';
$events = [];
$users = [];
if ($selectedCategory) {
    $stmt = $pdo->prepare("SELECT event_name FROM events WHERE category = ?");
    $stmt->execute([$selectedCategory]);
    $events = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
if ($selectedEvent) {
    $stmt = $pdo->prepare("
        SELECT u.firstname, u.lastname, u.username, u.email, u.phone
        FROM users u
        JOIN users_events ue ON u.id = ue.user_id
        WHERE ue.event_name = ?
    ");
    $stmt->execute([$selectedEvent]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Filter Users by Category & Event</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('p4.jpg');
            padding: 80px;
            background-repeat: no-repeat;
            background-size: cover;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            color: yellow;
            margin-bottom: 30px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        select,
        button {
            padding: 10px 16px;
            font-size: 16px;
            margin: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #0077cc;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #005fa3;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .no-users {
            text-align: center;
            font-size: 30px;
            color: red;
            font-weight: bold;
            margin-top: 20px;
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


    <h1>Welcome, Admin </h1>
    <h2> Registered Users by Category and Event</h2>

    <form method="POST">
        <select name="category" onchange="this.form.submit()">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($events)): ?>
            <select name="event">
                <option value=""> Select Event </option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= htmlspecialchars($event) ?>" <?= $selectedEvent === $event ? 'selected' : '' ?>>
                        <?= htmlspecialchars($event) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Show Users</button>
        <?php endif; ?>
    </form>
    <h3 style="text-align:center;color:white;">
        <?= count($users) ?> users are registered for <span
            style="color:yellow"><?= htmlspecialchars($selectedEvent) ?></span>
    </h3>
    <?php if (!empty($users)): ?>
        <table>
            <tr>
                <th>S.no</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            <?php foreach ($users as $i => $user): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table><br>
    <?php elseif ($selectedEvent): ?>
        <div class="no-users">No users registered for this event.</div>
    <?php endif; ?>
    <center><button onclick="printPage()" class="button-group">Print</button></center>
</body>

</html>