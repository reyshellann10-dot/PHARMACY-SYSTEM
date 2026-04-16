<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c7da0, #2d3e50);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar position-fixed top-0 start-0 h-100" style="width: 260px;">
        <div class="p-3 text-white text-center border-bottom">
            <i class="fas fa-hospital-user fa-3x"></i>
            <h5 class="mt-2">PharmaTrack</h5>
            <small>Pharmacy Management System</small>
        </div>
        <nav class="nav flex-column p-3">
            <a href="/dashboard" class="nav-link text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/products" class="nav-link text-white"><i class="fas fa-capsules"></i> Products</a>
            <a href="/sales" class="nav-link text-white active"><i class="fas fa-receipt"></i> Sales</a>
            <a href="/reports" class="nav-link text-white"><i class="fas fa-chart-line"></i> Reports</a>
            <hr class="bg-light">
            <a href="/logout" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
        <div class="position-absolute bottom-0 start-0 w-100 p-3 text-center text-white-50 border-top">
            <small><?= session()->get('full_name') ?> (<?= ucfirst(session()->get('role')) ?>)</small>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-receipt"></i> Sale Details</h2>
                <a href="/sales" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Sales</a>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-file-invoice"></i> Invoice: <?= $sale['invoice_number'] ?? 'N/A' ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th style="width: 150px;">Sale ID:</th><td><?= $sale['sale_id'] ?></td></tr>
                                <tr><th>Date & Time:</th><td><?= date('F d, Y h:i A', strtotime($sale['sale_date'])) ?></td></tr>
                                <tr><th>Cashier ID:</th><td><?= $sale['user_id'] ?></td></tr>
                                <tr><th>Customer ID:</th><td><?= $sale['customer_id'] ?? 'Walk-in' ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th style="width: 150px;">Total Amount:</th><td class="fw-bold text-success">₱<?= number_format($sale['total_amount'], 2) ?></td></tr>
                                <tr><th>Amount Paid:</th><td>₱<?= number_format($sale['amount_paid'], 2) ?></td></tr>
                                <tr><th>Change Due:</th><td>₱<?= number_format($sale['change_due'], 2) ?></td></tr>
                                <tr><th>Payment Method:</th><td><?= ucfirst($sale['payment_method']) ?></td></tr>
                                <tr><th>Status:</th><td>
                                    <?php if ($sale['status'] == 'completed'): ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif ($sale['status'] == 'cancelled'): ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><?= $sale['status'] ?></span>
                                    <?php endif; ?>
                                </td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>