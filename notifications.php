<?php
// Include database connection
include 'db.php';
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Add notification
        $userId = $_POST['user_id'];
        $notificationMsg = $_POST['notification_msg'];
        $serverId = $_POST['server_id'];
        $serverName = $_POST['server_name'];
        $ipAddress = $_POST['ip_address'];
        $dateTime = date('Y-m-d H:i:s'); // Current timestamp for DateTime
        $status = 'Active'; // Default status for new notifications
        
        // Optional: Fetch values for CompletedDateTime, CompletedUserID, and Details if provided
        $completedDateTime = $_POST['completed_date_time'] ?? null;
        $completedUserId = $_POST['completed_user_id'] ?? null;
        $details = $_POST['details'] ?? null;

        // Insert query with placeholders
        $stmt = $pdo->prepare("INSERT INTO notifications (UserID, NotificationMsg, ServerID, ServerName, IPAddress, DateTime, Status, CompletedDateTime, CompletedUserID, Details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $notificationMsg, $serverId, $serverName, $ipAddress, $dateTime, $status, $completedDateTime, $completedUserId, $details]);

        // Redirect after adding
        header('Location: notifications.php');
        exit;
    } elseif (isset($_POST['edit'])) {
        // Edit notification
        $notificationId = $_POST['edit_id'];
        $notificationMsg = $_POST['edit_notification_msg'];
        $serverId = $_POST['edit_server_id'];
        $serverName = $_POST['edit_server_name'];
        $ipAddress = $_POST['edit_ip_address'];
        $dateTime = $_POST['edit_date_time']; // Ensure to fetch this properly from the form
        $status = $_POST['edit_status']; // Ensure to fetch this properly from the form

        // Optional: Fetch values for CompletedDateTime, CompletedUserID, and Details if provided
        $completedDateTime = $_POST['edit_completed_date_time'] ?? null;
        $completedUserId = $_POST['edit_completed_user_id'] ?? null;
        $details = $_POST['edit_details'] ?? null;

        // Update query with placeholders
        $stmt = $pdo->prepare("UPDATE notifications SET NotificationMsg = ?, ServerID = ?, ServerName = ?, IPAddress = ?, DateTime = ?, Status = ?, CompletedDateTime = ?, CompletedUserID = ?, Details = ? WHERE NotificationID = ?");
        $stmt->execute([$notificationMsg, $serverId, $serverName, $ipAddress, $dateTime, $status, $completedDateTime, $completedUserId, $details, $notificationId]);

        // Redirect after editing
        header('Location: notifications.php');
        exit;
    } elseif (isset($_POST['delete'])) {
        // Delete notification
        $notificationId = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM notifications WHERE NotificationID = ?");
        $stmt->execute([$notificationId]);

        // Redirect after deleting
        header('Location: notifications.php');
        exit;
    }
}

// Fetch notifications
$stmt = $pdo->query("SELECT * FROM notifications ORDER BY DateTime DESC");
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Notifications</h1>

        <!-- Add Notification Form -->
        <div class="card mb-4">
            <div class="card-header">
                Add Notification
            </div>
            <div class="card-body">
                <form action="notifications.php" method="post">
                    <div class="form-group">
                        <label for="user_id">User ID</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" required>
                    </div>
                    <div class="form-group">
                        <label for="notification_msg">Notification Message</label>
                        <textarea class="form-control" id="notification_msg" name="notification_msg" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="server_id">Server ID</label>
                        <input type="text" class="form-control" id="server_id" name="server_id" required>
                    </div>
                    <div class="form-group">
                        <label for="server_name">Server Name</label>
                        <input type="text" class="form-control" id="server_name" name="server_name" required>
                    </div>
                    <div class="form-group">
                        <label for="ip_address">IP Address</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address" required>
                    </div>
                    <div class="form-group">
                        <label for="completed_date_time">Completed Date Time</label>
                        <input type="text" class="form-control" id="completed_date_time" name="completed_date_time">
                    </div>
                    <div class="form-group">
                        <label for="completed_user_id">Completed User ID</label>
                        <input type="text" class="form-control" id="completed_user_id" name="completed_user_id">
                    </div>
                    <div class="form-group">
                        <label for="details">Details</label>
                        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Add Notification</button>
                </form>
            </div>
        </div>

        <!-- Notifications Table -->
        <div class="card">
            <div class="card-header">
                Notifications List
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Notification ID</th>
                            <th>User ID</th>
                            <th>Notification Message</th>
                            <th>Server ID</th>
                            <th>Server Name</th>
                            <th>IP Address</th>
                            <th>Date Time</th>
                            <th>Status</th>
                            <th>Completed Date Time</th>
                            <th>Completed User ID</th>
                            <th>Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notifications as $notification): ?>
                            <tr>
                                <td><?php echo $notification['NotificationID']; ?></td>
                                <td><?php echo $notification['UserID']; ?></td>
                                <td><?php echo $notification['NotificationMsg']; ?></td>
                                <td><?php echo $notification['ServerID']; ?></td>
                                <td><?php echo $notification['ServerName']; ?></td>
                                <td><?php echo $notification['IPAddress']; ?></td>
                                <td><?php echo $notification['DateTime']; ?></td>
                                <td><?php echo $notification['Status']; ?></td>
                                <td><?php echo $notification['CompletedDateTime']; ?></td>
                                <td><?php echo $notification['CompletedUserID']; ?></td>
                                <td><?php echo $notification['Details']; ?></td>
                                <td>
                                    <!-- Edit Form -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $notification['NotificationID']; ?>">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Form -->
                                    <form action="notifications.php" method="post" style="display: inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $notification['NotificationID']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal_<?php echo $notification['NotificationID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel_<?php echo $notification['NotificationID']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel_<?php echo $notification['NotificationID']; ?>">Edit Notification</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="notifications.php" method="post">
                                                <input type="hidden" name="edit_id" value="<?php echo $notification['NotificationID']; ?>">
                                                <div class="form-group">
                                                    <label for="edit_notification_msg">Notification Message</label>
                                                    <textarea class="form-control" id="edit_notification_msg" name="edit_notification_msg" rows="3" required><?php echo $notification['NotificationMsg']; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_server_id">Server ID</label>
                                                    <input type="text" class="form-control" id="edit_server_id" name="edit_server_id" value="<?php echo $notification['ServerID']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_server_name">Server Name</label>
                                                    <input type="text" class="form-control" id="edit_server_name" name="edit_server_name" value="<?php echo $notification['ServerName']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_ip_address">IP Address</label>
                                                    <input type="text" class="form-control" id="edit_ip_address" name="edit_ip_address" value="<?php echo $notification['IPAddress']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_date_time">Date Time</label>
                                                    <input type="text" class="form-control" id="edit_date_time" name="edit_date_time" value="<?php echo $notification['DateTime']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_status">Status</label>
                                                    <select class="form-control" id="edit_status" name="edit_status">
                                                        <option value="Active" <?php if ($notification['Status'] == 'Active') echo 'selected'; ?>>Active</option>
                                                        <option value="Inactive" <?php if ($notification['Status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_completed_date_time">Completed Date Time</label>
                                                    <input type="text" class="form-control" id="edit_completed_date_time" name="edit_completed_date_time" value="<?php echo $notification['CompletedDateTime']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_completed_user_id">Completed User ID</label>
                                                    <input type="text" class="form-control" id="edit_completed_user_id" name="edit_completed_user_id" value="<?php echo $notification['CompletedUserID']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_details">Details</label>
                                                    <textarea class="form-control" id="edit_details" name="edit_details" rows="3"><?php echo $notification['Details']; ?></textarea>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
