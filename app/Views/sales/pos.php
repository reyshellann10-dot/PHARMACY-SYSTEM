<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
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
        .product-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .cart-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .search-box {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding: 10px 0;
        }
        .products-list {
            max-height: 500px;
            overflow-y: auto;
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar position-fixed top-0 start-0 h-100" style="width: 260px;">
        <div class="p-3 text-white text-center border-bottom">
            <i class="fas fa-hospital-user fa-3x"></i>
            <h5 class="mt-2">PharmaTrack</h5>
            <small>Point of Sale</small>
        </div>
        <nav class="nav flex-column p-3">
            <a href="/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/products" class="nav-link"><i class="fas fa-capsules"></i> Products</a>
            <a href="/sales" class="nav-link active"><i class="fas fa-receipt"></i> Sales</a>
            <a href="/reports" class="nav-link"><i class="fas fa-chart-line"></i> Reports</a>
            <hr class="bg-light">
            <a href="/logout" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Left: Products -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Products</div>
                        <div class="card-body">
                            <div class="search-box">
                                <input type="text" id="searchProduct" class="form-control" placeholder="Search product by name or code...">
                            </div>
                            <div class="products-list mt-3" id="productsList">
                                <?php foreach ($products as $p): ?>
                                <div class="product-card p-2" data-id="<?= $p['product_id'] ?>" data-name="<?= $p['generic_name'] ?>" data-price="<?= $p['unit_price'] ?>">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= $p['generic_name'] ?></strong><br>
                                            <small><?= $p['brand_name'] ?></small><br>
                                            <span class="badge bg-secondary">₱<?= number_format($p['unit_price'], 2) ?></span>
                                            <span class="badge bg-info">Stock: <?= $p['stock_quantity'] ?></span>
                                        </div>
                                        <button class="btn btn-sm btn-primary add-to-cart">+ Add</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Cart -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-success text-white">Shopping Cart</div>
                        <div class="card-body">
                            <div id="cartItems">
                                <?php if (empty($cart)): ?>
                                    <p class="text-muted text-center">Cart is empty</p>
                                <?php else: ?>
                                    <?php foreach ($cart as $id => $item): ?>
                                    <div class="cart-item" data-id="<?= $id ?>">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?= $item['product_name'] ?></strong><br>
                                                ₱<?= number_format($item['unit_price'], 2) ?> x <?= $item['quantity'] ?>
                                            </div>
                                            <div>
                                                ₱<?= number_format($item['subtotal'], 2) ?>
                                                <button class="btn btn-sm btn-danger remove-item" data-id="<?= $id ?>"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong id="cartTotal">₱<?= number_format($cartTotal, 2) ?></strong>
                            </div>
                            <hr>
                            <div class="mb-2">
    <label>Customer</label>
    <div class="input-group">
        <select id="customerId" class="form-select">
            <option value="">Walk-in Customer</option>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['customer_id'] ?>"><?= esc($c['first_name']) ?> <?= esc($c['last_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-plus"></i> New
        </button>
    </div>
</div>
<!-- Quick Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickCustomerForm">
                    <div class="mb-2">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name *" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name *" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="contact_number" class="form-control" placeholder="Contact Number">
                    </div>
                    <div class="mb-2">
                        <textarea name="address" class="form-control" placeholder="Address" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveQuickCustomer">Save Customer</button>
            </div>
        </div>
    </div>
</div>
                            <div class="mb-2">
                                <label>Amount Paid</label>
                                <input type="number" id="amountPaid" class="form-control" step="0.01">
                            </div>
                            <div class="mb-2">
                                <label>Change</label>
                                <input type="text" id="changeDue" class="form-control" readonly>
                            </div>
                            <button id="checkoutBtn" class="btn btn-success w-100">Checkout</button>
                            <button id="clearCartBtn" class="btn btn-danger w-100 mt-2">Clear Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add to cart
            $('.add-to-cart').click(function() {
                let card = $(this).closest('.product-card');
                let id = card.data('id');
                let name = card.data('name');
                let price = card.data('price');
                $.post('/sales/add-to-cart', {product_id: id, quantity: 1}, function(res) {
                    if (res.success) location.reload();
                    else alert(res.message);
                });
            });

            // Remove from cart
            $('.remove-item').click(function() {
                let id = $(this).data('id');
                $.post('/sales/remove-from-cart', {product_id: id}, function(res) {
                    if (res.success) location.reload();
                });
            });

            // Calculate change
            $('#amountPaid').on('input', function() {
                let total = parseFloat($('#cartTotal').text().replace('₱', '').replace(/,/g, ''));
                let paid = parseFloat($(this).val()) || 0;
                let change = paid - total;
                $('#changeDue').val(change >= 0 ? change.toFixed(2) : '0.00');
            });

        // Checkout button click
$('#checkoutBtn').click(function() {
    let total = parseFloat($('#cartTotal').text().replace('₱', '').replace(/,/g, ''));
    let paid = parseFloat($('#amountPaid').val());
    
    // Validate amount
    if (isNaN(paid) || paid <= 0) {
        alert('Please enter a valid amount paid');
        return;
    }
    
    if (paid < total) {
        alert('Amount paid is less than total');
        return;
    }
    
    // Disable button to prevent double click
    let btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    
    $.ajax({
        url: '/sales/checkout',
        method: 'POST',
        data: {
            customer_id: $('#customerId').val(),
            amount_paid: paid
        },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                // Show success message with invoice number
                alert('✅ Sale completed!\nInvoice: ' + res.invoice_number + '\nChange: ₱' + res.change_due.toFixed(2));
                // Redirect to sales list page (or receipt page)
                window.location.href = '/sales';
            } else {
                alert('❌ Error: ' + res.message);
                btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Complete Sale');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Server error. Please check console.');
            btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Complete Sale');
        }
    });
});

            // Clear cart
            $('#clearCartBtn').click(function() {
                $.post('/sales/clear-cart', function() {
                    location.reload();
                });
            });

            // Search products
            $('#searchProduct').on('input', function() {
                let keyword = $(this).val();
                if (keyword.length < 2) return;
                $.get('/sales/search-products?keyword=' + keyword, function(products) {
                    let html = '';
                    products.forEach(p => {
                        html += `<div class="product-card p-2" data-id="${p.product_id}" data-name="${p.generic_name}" data-price="${p.unit_price}">
                                    <div class="d-flex justify-content-between">
                                        <div><strong>${p.generic_name}</strong><br><small>${p.brand_name || ''}</small><br><span class="badge bg-secondary">₱${p.unit_price}</span><span class="badge bg-info">Stock: ${p.stock_quantity}</span></div>
                                        <button class="btn btn-sm btn-primary add-to-cart">+ Add</button>
                                    </div>
                                </div>`;
                    });
                    $('#productsList').html(html);
                    // Rebind click events
                    $('.add-to-cart').click(function() {
                        let card = $(this).closest('.product-card');
                        let id = card.data('id');
                        $.post('/sales/add-to-cart', {product_id: id, quantity: 1}, function(res) {
                            if (res.success) location.reload();
                            else alert(res.message);
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>