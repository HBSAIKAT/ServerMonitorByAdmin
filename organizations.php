<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include 'db.php';

// Handle add, edit, delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Add organization
        $name = $_POST['name'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("INSERT INTO organizations (name, address_details, contact_details, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $address, $contact, $status]);
    } elseif (isset($_POST['edit'])) {
        // Edit organization
        $id = $_POST['id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE organizations SET name = ?, address_details = ?, contact_details = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $address, $contact, $status, $id]);
    } elseif (isset($_POST['delete'])) {
        // Delete organization
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM organizations WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Fetch all organizations
$organizations = $pdo->query("SELECT * FROM organizations")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Organizations</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Manage Organizations</h1>
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Add Organization</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address Details</th>
                    <th>Contact Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizations as $organization): ?>
                    <tr>
                        <td><?php echo $organization['id']; ?></td>
                        <td><?php echo $organization['name']; ?></td>
                        <td><?php echo $organization['address_details']; ?></td>
                        <td><?php echo $organization['contact_details']; ?></td>
                        <td><?php echo $organization['status']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo $organization['id']; ?>" data-name="<?php echo $organization['name']; ?>" data-address="<?php echo $organization['address_details']; ?>" data-contact="<?php echo $organization['contact_details']; ?>" data-status="<?php echo $organization['status']; ?>">Edit</button>
                            <form action="organizations.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $organization['id']; ?>">
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
                <form action="organizations.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Organization</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address Details</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact Details</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary">Add Organization</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="organizations.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Organization</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-address">Address Details</label>
                            <textarea class="form-control" id="edit-address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-contact">Contact Details</label>
                            <input type="text" class="form-control" id="edit-contact" name="contact" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-status">Status</label>
                            <select class="form-control" id="edit-status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var address = button.data('address');
            var contact = button.data('contact');
            var status = button.data('status');
            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-name').val(name);
            modal.find('#edit-address').val(address);
            modal.find('#edit-contact').val(contact);
            modal.find('#edit-status').val(status);
        });
    </script>
</body>
</html>
