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
        // Add user
        $orgId = isset($_POST['org_id']) ? $_POST['org_id'] : '';
        $userType = isset($_POST['user_type']) ? $_POST['user_type'] : '';
        $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $loginId = isset($_POST['login_id']) ? $_POST['login_id'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        if (!empty($orgId) && !empty($userType)) {
            $stmt = $pdo->prepare("INSERT INTO users (org_id, user_type, first_name, last_name, phone, mobile, email, login_id, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$orgId, $userType, $firstName, $lastName, $phone, $mobile, $email, $loginId, $password, $status]);
        } else {
            echo "Organization ID and User Type are required.";
        }
    } elseif (isset($_POST['edit'])) {
        // Edit user
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $orgId = isset($_POST['org_id']) ? $_POST['org_id'] : '';
        $userType = isset($_POST['user_type']) ? $_POST['user_type'] : '';
        $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $loginId = isset($_POST['login_id']) ? $_POST['login_id'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        if (!empty($orgId) && !empty($userType)) {
            $stmt = $pdo->prepare("UPDATE users SET org_id = ?, user_type = ?, first_name = ?, last_name = ?, phone = ?, mobile = ?, email = ?, login_id = ?, password = ?, status = ? WHERE id = ?");
            $stmt->execute([$orgId, $userType, $firstName, $lastName, $phone, $mobile, $email, $loginId, $password, $status, $id]);
        } else {
            echo "Organization ID and User Type are required.";
        }
    } elseif (isset($_POST['delete'])) {
        // Delete user
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Manage Users</h1>
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Add User</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Organization ID</th>
                    <th>User Type</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Login ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['org_id']; ?></td>
                        <td><?php echo $user['user_type']; ?></td>
                        <td><?php echo $user['first_name']; ?></td>
                        <td><?php echo $user['last_name']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['mobile']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['login_id']; ?></td>
                        <td><?php echo $user['status']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal"
                                data-id="<?php echo $user['id']; ?>"
                                data-org-id="<?php echo $user['org_id']; ?>"
                                data-user-type="<?php echo $user['user_type']; ?>"
                                data-first-name="<?php echo $user['first_name']; ?>"
                                data-last-name="<?php echo $user['last_name']; ?>"
                                data-phone="<?php echo $user['phone']; ?>"
                                data-mobile="<?php echo $user['mobile']; ?>"
                                data-email="<?php echo $user['email']; ?>"
                                data-login-id="<?php echo $user['login_id']; ?>"
                                data-password="<?php echo $user['password']; ?>"
                                data-status="<?php echo $user['status']; ?>">Edit</button>
                            <form action="users.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
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
                <form action="users.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="org_id">Organization ID</label>
                            <input type="text" class="form-control" id="org_id" name="org_id" required>
                        </div>
                        <div class="form-group">
                            <label for="user_type">User Type</label>
                            <input type="text" class="form-control" id="user_type" name="user_type" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="login_id">Login ID</label>
                            <input type="text" class="form-control" id="login_id" name="login_id" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
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
                        <button type="submit" name="add" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="users.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-org-id">Organization ID</label>
                            <input type="text" class="form-control" id="edit-org-id" name="org_id" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-type">User Type</label>
                            <input type="text" class="form-control" id="edit-user-type" name="user_type" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-first-name">First Name</label>
                            <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-last-name">Last Name</label>
                            <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-phone">Phone</label>
                            <input type="text" class="form-control" id="edit-phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-mobile">Mobile</label>
                            <input type="text" class="form-control" id="edit-mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-login-id">Login ID</label>
                            <input type="text" class="form-control" id="edit-login-id" name="login_id" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-password">Password</label>
                            <input type="password" class="form-control" id="edit-password" name="password" required>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var orgId = button.data('org-id');
            var userType = button.data('user-type');
            var firstName = button.data('first-name');
            var lastName = button.data('last-name');
            var phone = button.data('phone');
            var mobile = button.data('mobile');
            var email = button.data('email');
            var loginId = button.data('login-id');
            var password = button.data('password');
            var status = button.data('status');
            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-org-id').val(orgId);
            modal.find('#edit-user-type').val(userType);
            modal.find('#edit-first-name').val(firstName);
            modal.find('#edit-last-name').val(lastName);
            modal.find('#edit-phone').val(phone);
            modal.find('#edit-mobile').val(mobile);
            modal.find('#edit-email').val(email);
            modal.find('#edit-login-id').val(loginId);
            modal.find('#edit-password').val(password);
            modal.find('#edit-status').val(status);
        });
    </script>
</body>
</html>
