<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
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
        
        <!-- Main content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between mb-3">
                <h2>Products</h2>
                <a href="/products/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
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
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Expiry</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $p): ?>
                            <tr>
                                <td><?= $p['product_id'] ?></td>
                                <td><?= $p['product_code'] ?></td>
                                <td><?= $p['generic_name'] ?> <?= $p['brand_name'] ? "({$p['brand_name']})" : '' ?></td>
                                <td>₱<?= number_format($p['unit_price'], 2) ?></td>
                                <td><?= $p['stock_quantity'] ?></td>
                                <td><?= date('Y-m-d', strtotime($p['expiry_date'])) ?></td>
                                <td>
                                    <a href="/products/edit/<?= $p['product_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="/products/delete/<?= $p['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
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