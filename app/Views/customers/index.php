<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-dark vh-100 p-3">
            <h4 class="text-white">PharmaTrack</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/products"><i class="fas fa-capsules"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/sales"><i class="fas fa-receipt"></i> Sales</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/customers"><i class="fas fa-users"></i> Customers</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/reports"><i class="fas fa-chart-line"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between mb-3">
                <h2><i class="fas fa-users"></i> Customer Management</h2>
                <a href="/customers/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Customer</a>
            </div>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Contact</th><th>Email</th><th>Address</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><?= $c['customer_id'] ?></td>
                                <td><?= esc($c['first_name']) ?> <?= esc($c['last_name']) ?></td>
                                <td><?= esc($c['contact_number'] ?? '-') ?></td>
                                <td><?= esc($c['email'] ?? '-') ?></td>
                                <td><?= esc($c['address'] ?? '-') ?></td>
                                <td>
                                    <a href="/customers/view/<?= $c['customer_id'] ?>" class="btn btn-sm btn-info">View</a>
                                    <a href="/customers/edit/<?= $c['customer_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="/customers/delete/<?= $c['customer_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</a>
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
</body>
</html>