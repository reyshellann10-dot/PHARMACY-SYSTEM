<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Customer Details</div>
        <div class="card-body">
            <h4><?= esc($customer['first_name']) ?> <?= esc($customer['last_name']) ?></h4>
            <p>Email: <?= esc($customer['email'] ?? '-') ?></p>
            <p>Contact: <?= esc($customer['contact_number'] ?? '-') ?></p>
            <p>Address: <?= esc($customer['address'] ?? '-') ?></p>
            <hr>
            <h5>Purchase History</h5>
            <table class="table table-sm">
                <thead><tr><th>Date</th><th>Invoice</th><th>Total</th></tr></thead>
                <tbody>
                    <?php foreach ($purchases as $p): ?>
                    <tr><td><?= $p['sale_date'] ?></td><td><?= $p['invoice_number'] ?></td><td>₱<?= number_format($p['total_amount'],2) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="/customers" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
</body>
</html>