<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
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
                <h2>Edit Product</h2>
                <a href="/products" class="btn btn-secondary">Back to Products</a>
            </div>
            
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <?= $error ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form action="/products/update/<?= $product['product_id'] ?>" method="POST">
                        <div class="mb-3">
                            <label>Product Code</label>
                            <input type="text" name="product_code" class="form-control" 
                                   value="<?= $product['product_code'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Generic Name</label>
                            <input type="text" name="generic_name" class="form-control" 
                                   value="<?= $product['generic_name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Brand Name</label>
                            <input type="text" name="brand_name" class="form-control" 
                                   value="<?= $product['brand_name'] ?>">
                        </div>
                        <div class="mb-3">
                            <label>Unit Price</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control" 
                                   value="<?= $product['unit_price'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control" 
                                   value="<?= $product['stock_quantity'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" 
                                   value="<?= $product['expiry_date'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Manufacturer</label>
                            <input type="text" name="manufacturer" class="form-control" 
                                   value="<?= $product['manufacturer'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="/products" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>