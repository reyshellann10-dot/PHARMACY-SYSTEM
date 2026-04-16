<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">Add New Product</div>
        <div class="card-body">
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('errors') as $field => $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/products/store" method="POST">
                <div class="mb-3">
                    <label>Product Code <span class="text-danger">*</span></label>
                    <input type="text" name="product_code" class="form-control" 
                           value="<?= old('product_code') ?>" required>
                    <small class="text-muted">Unique code (e.g., MED001)</small>
                </div>
                <div class="mb-3">
                    <label>Generic Name <span class="text-danger">*</span></label>
                    <input type="text" name="generic_name" class="form-control" 
                           value="<?= old('generic_name') ?>" required>
                </div>
                <div class="mb-3">
                    <label>Brand Name</label>
                    <input type="text" name="brand_name" class="form-control" 
                           value="<?= old('brand_name') ?>">
                </div>
                <div class="mb-3">
                    <label>Unit Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" 
                           value="<?= old('unit_price') ?>" required>
                </div>
                <div class="mb-3">
                    <label>Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="stock_quantity" class="form-control" 
                           value="<?= old('stock_quantity', 0) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="expiry_date" class="form-control" 
                           value="<?= old('expiry_date') ?>" required>
                </div>
                <div class="mb-3">
                    <label>Manufacturer</label>
                    <input type="text" name="manufacturer" class="form-control" 
                           value="<?= old('manufacturer') ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="/products" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>