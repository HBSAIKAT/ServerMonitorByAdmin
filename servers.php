<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Include database connection
include 'db.php';

// Handle add, edit, delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Add server
        $serverName = $_POST['server_name'];
        $ipAddress = $_POST['ip_address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO servers (name, ip_address, status) VALUES (?, ?, ?)");
        $stmt->execute([$serverName, $ipAddress, $status]);

        // Optionally, you can redirect after adding
        header('Location: servers.php');
        exit;
    } elseif (isset($_POST['edit'])) {
        // Edit server
        $serverId = $_POST['id'];
        $serverName = $_POST['server_name'];
        $ipAddress = $_POST['ip_address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE servers SET name = ?, ip_address = ?, status = ? WHERE id = ?");
        $stmt->execute([$serverName, $ipAddress, $status, $serverId]);

        // Optionally, you can redirect after editing
        header('Location: servers.php');
        exit;
    } elseif (isset($_POST['delete'])) {
        // Delete server
        $serverId = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM servers WHERE id = ?");
        $stmt->execute([$serverId]);

        // Optionally, you can redirect after deleting
        header('Location: servers.php');
        exit;
    }
}

// Fetch all servers
$stmt = $pdo->query("SELECT * FROM servers");
$servers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Servers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Manage Servers</h1>
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Add Server</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Last Checked</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servers as $server): ?>
                    <tr>
                        <td><?php echo $server['id']; ?></td>
                        <td><?php echo $server['name']; ?></td>
                        <td><?php echo $server['ip_address']; ?></td>
                        <td><?php echo $server['status']; ?></td>
                        <td><?php echo $server['last_checked']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal"
                                data-id="<?php echo $server['id']; ?>"
                                data-server-name="<?php echo $server['name']; ?>"
                                data-ip-address="<?php echo $server['ip_address']; ?>"
                                data-status="<?php echo $server['status']; ?>">Edit</button>
                            <form action="servers.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $server['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="servers.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Server</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="server_name">Server Name</label>
                            <input type="text" class="form-control" id="server_name" name="server_name" required>
                        </div>
                        <div class="form-group">
                            <label for="ip_address">IP Address</label>
                            <input type="text" class="form-control" id="ip_address" name="ip_address" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary">Add Server</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="servers.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Server</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-server-name">Server Name</label>
                            <input type="text" class="form-control" id="edit-server-name" name="server_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-ip-address">IP Address</label>
                            <input type="text" class="form-control" id="edit-ip-address" name="ip_address" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-status">Status</label>
                            <select class="form-control" id="edit-status" name="status" required>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var serverName = button.data('server-name');
            var ipAddress = button.data('ip-address');
            var status = button.data('status');
            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-server-name').val(serverName);
            modal.find('#edit-ip-address').val(ipAddress);
            modal.find('#edit-status').val(status);
        });
    </script>
</body>
</html>
