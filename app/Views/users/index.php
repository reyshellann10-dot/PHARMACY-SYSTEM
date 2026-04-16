<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-dark vh-100 p-3">
            <h4 class="text-white">PharmaTrack</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/users"><i class="fas fa-users"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/log"><i class="fas fa-history"></i> Logs</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between mb-3">
                <h2>User Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="usersTable">
                        <thead>
                            <tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><?= $u['user_id'] ?></td>
                                <td><?= $u['username'] ?></td>
                                <td><?= $u['full_name'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><?= ucfirst($u['role']) ?></td>
                                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-user" data-id="<?= $u['user_id'] ?>"><i class="fas fa-edit"></i></button>
                                    <a href="/users/delete/<?= $u['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Add New User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/users/save" method="POST">
                <div class="modal-body">
                    <div class="mb-2"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                    <div class="mb-2"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-2"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="mb-2"><label>Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="pharmacist">Pharmacist</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/users/update" method="POST">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="mb-2"><label>Username</label><input type="text" name="username" id="edit_username" class="form-control" required></div>
                    <div class="mb-2"><label>Full Name</label><input type="text" name="full_name" id="edit_full_name" class="form-control" required></div>
                    <div class="mb-2"><label>Email</label><input type="email" name="email" id="edit_email" class="form-control" required></div>
                    <div class="mb-2"><label>New Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
                    <div class="mb-2"><label>Role</label>
                        <select name="role" id="edit_role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="pharmacist">Pharmacist</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.edit-user').click(function() {
        let id = $(this).data('id');
        $.ajax({
            url: '/users/edit/' + id,
            method: 'GET',
            success: function(user) {
                $('#edit_user_id').val(user.user_id);
                $('#edit_username').val(user.username);
                $('#edit_full_name').val(user.full_name);
                $('#edit_email').val(user.email);
                $('#edit_role').val(user.role);
                $('#editUserModal').modal('show');
            }
        });
    });
});
</script>
</body>
</html>