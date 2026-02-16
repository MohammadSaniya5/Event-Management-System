<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - EventSphere</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #141e30, #243b55);
            color: white;
        }

        header {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(8px);
            border-bottom: 2px solid #00c3ff;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .logo span {
            color: #00c3ff;
        }

        h2,
        p {
            text-align: center;
        }

        .nav {
            display: flex;
            gap: 25px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .nav a:hover {
            background: #00c3ff;
            color: black;
        }

        .active {
            background: #00c3ff;
            color: black !important;
        }

        .content {
            padding: 40px;
        }

        .card {
            background: linear-gradient(135deg, #141e30, #243b55);
            backdrop-filter: blur(12px);
            padding: 50px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">
            Event<span>Sphere</span> Admin
        </div>

        <div class="nav">
            <a href="admin_dashboard.php?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">Dashboard</a>
            <a href="admin.php?page=admin" class="<?= $page == 'admin' ? 'active' : '' ?>">Users Details</a>
            <a href="view_page.php?page=view" class="<?= $page == 'view' ? 'active' : '' ?>">Event-wise Users</a>
            <a href="logout1.php">Logout</a>
        </div>
    </header>

    <div class="content">
        <div class="card">
            <?php
            if ($page == 'view') {
                include 'view_page.php';
            } elseif ($page == 'admin') {
                include 'admin.php';
            } else {
                echo "<h2>Welcome Admin 👋</h2>";
                echo "<p>Manage registrations, verify payments, and monitor events here.</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>