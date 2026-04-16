<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Transactions</title>
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
                <li class="nav-item"><a class="nav-link text-white" href="/reports"><i class="fas fa-chart-line"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between mb-3">
                <h2><i class="fas fa-receipt"></i> Sales Transactions</h2>
                <a href="/sales/pos" class="btn btn-success"><i class="fas fa-plus"></i> New Sale</a>
            </div>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr><th>ID</th><th>Invoice #</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sales as $s): ?>
                                <tr>
                                    <td><?= $s['sale_id'] ?></td>
                                    <td><?= $s['invoice_number'] ?? 'N/A' ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($s['sale_date'])) ?></td>
                                    <td>₱<?= number_format($s['total_amount'], 2) ?></td>
                                    <td><?= ucfirst($s['payment_method']) ?></td>
                                    <td><span class="badge bg-<?= $s['status'] == 'completed' ? 'success' : 'danger' ?>"><?= $s['status'] ?></span></td>
                                    <td>
                                        <a href="/sales/view/<?= $s['sale_id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                                        <a href="<?= base_url('/sales/delete/' . $s['sale_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this sale? Stock will be restored.')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($sales)): ?>
                                <td><td colspan="7" class="text-center">No sales yet</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>