<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include 'db.php';

// Fetch data for summary counts
$orgCount = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$serverCount = $pdo->query("SELECT COUNT(*) FROM servers")->fetchColumn();
$notificationCount = $pdo->query("SELECT COUNT(*) FROM notifications")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Organizations</h5>
                        <p class="card-text">Total: <?php echo $orgCount; ?></p>
                        <a href="organizations.php" class="btn btn-light">Manage Organizations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Total: <?php echo $userCount; ?></p>
                        <a href="users.php" class="btn btn-light">Manage Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Servers</h5>
                        <p class="card-text">Total: <?php echo $serverCount; ?></p>
                        <a href="servers.php" class="btn btn-light">Manage Servers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text">Total: <?php echo $notificationCount; ?></p>
                        <a href="notifications.php" class="btn btn-light">Manage Notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
