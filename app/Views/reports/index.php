<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
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
            <h2>Reports Dashboard</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5>Total Sales</h5>
                            <h3>₱<?= number_format($totalSales, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5>Total Products</h5>
                            <h3><?= $totalProducts ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5>Low Stock Items</h5>
                            <h3><?= $lowStock ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <a href="/reports/sales" class="btn btn-primary">View Sales Report</a>
        </div>
    </div>
</div>
</body>
</html>