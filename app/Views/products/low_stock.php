<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-exclamation-triangle text-warning"></i> Low Stock Products</h2>
        <a href="<?= base_url('/products') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-warning">
            <i class="fas fa-bell"></i> Products Need Reordering
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><code><?= $product['product_code'] ?></code></td>
                            <td><?= $product['generic_name'] ?> (<?= $product['brand_name'] ?>)</td>
                            <td class="text-danger fw-bold"><?= $product['stock_quantity'] ?> units</td>
                            <td><?= $product['reorder_level'] ?> units</td>
                            <td>
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            </td>
                            <td>
                                <a href="<?= base_url('/products/adjustStock/' . $product['product_id']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-truck"></i> Restock
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>