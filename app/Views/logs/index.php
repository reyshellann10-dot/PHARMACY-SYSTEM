<!DOCTYPE html>
<html>
<head>
    <title>System Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
                <h2>System Logs</h2>
                <div>
                    <a href="/log/export" class="btn btn-success"><i class="fas fa-download"></i> Export CSV</a>
                    <a href="/log/clear" class="btn btn-danger" onclick="return confirm('Clear all logs?')"><i class="fas fa-trash"></i> Clear Logs</a>
                </div>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="logsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($logs)): ?>
                                    <?php foreach($logs as $log): ?>
                                    <tr>
                                        <td><?= $log['log_id'] ?></td>
                                        <td><?= $log['user_name'] ?? 'System' ?></td>
                                        <td><?= $log['action'] ?></td>
                                        <td><?= $log['ip_address'] ?></td>
                                        <td><?= substr($log['user_agent'], 0, 60) ?>...</td>
                                        <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No logs yet. Perform actions (login, add user, add product) to generate logs.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    if ($('#logsTable tbody tr').length > 0 && $('#logsTable tbody td:first').text() != 'No logs yet') {
        $('#logsTable').DataTable();
    }
});
</script>
</body>
</html>