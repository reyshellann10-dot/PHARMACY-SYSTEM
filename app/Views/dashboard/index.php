<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pharmacy Management System</title>
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
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 40px;
            color: #2c7da0;
        }
        .stat-card h3 {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
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
    <div class="sidebar position-fixed top-0 start-0 h-100" style="width: 260px; z-index: 1000;">
        <div class="p-3 text-white text-center border-bottom">
            <i class="fas fa-hospital-user fa-3x"></i>
            <h5 class="mt-2">PharmaTrack</h5>
            <small>Pharmacy Management System</small>
        </div>
        <nav class="nav flex-column p-3">
            <a href="/dashboard" class="nav-link active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/users" class="nav-link">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="/log" class="nav-link">
                <i class="fas fa-history"></i> Logs
            </a>
            <hr class="bg-light">
            <a href="/logout" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
        <div class="position-absolute bottom-0 start-0 w-100 p-3 text-center text-white-50 border-top">
            <small><?= session()->get('full_name') ?> (<?= ucfirst(session()->get('role')) ?>)</small>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-boxes"></i>
                        <h3><?= $totalProducts ?></h3>
                        <p class="text-muted mb-0">Total Products</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <h3 class="text-warning"><?= $lowStockItems ?></h3>
                        <p class="text-muted mb-0">Low Stock Items</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-calendar-times text-danger"></i>
                        <h3 class="text-danger"><?= $expiringCount ?></h3>
                        <p class="text-muted mb-0">Expiring Soon</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-chart-line text-success"></i>
                        <h3>₱<?= number_format($todaySales, 2) ?></h3>
                        <p class="text-muted mb-0">Today's Sales</p>
                    </div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Welcome back, <?= session()->get('full_name') ?>!</h4>
                    <p>You logged in at: <?= date('Y-m-d h:i:s A') ?></p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-plus-circle fa-3x text-primary"></i>
                            <h5 class="mt-2">Add Product</h5>
                            <a href="/products/create" class="btn btn-sm btn-primary">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-3x text-success"></i>
                            <h5 class="mt-2">New Sale</h5>
                            <a href="/sales/pos" class="btn btn-sm btn-success">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-bar fa-3x text-info"></i>
                            <h5 class="mt-2">View Reports</h5>
                            <a href="/reports" class="btn btn-sm btn-info">Go</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>