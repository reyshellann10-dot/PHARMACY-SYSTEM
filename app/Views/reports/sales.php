<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Sales Report</h2>
    <table class="table table-bordered">
        <thead><tr><th>Invoice</th><th>Date</th><th>Total</th><th>Payment</th></tr></thead>
        <tbody>
            <?php foreach($sales as $s): ?>
            <tr>
                <td><?= $s['invoice_number'] ?></td>
                <td><?= $s['sale_date'] ?></td>
                <td>₱<?= number_format($s['total_amount'], 2) ?></td>
                <td><?= $s['payment_method'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/reports" class="btn btn-secondary">Back</a>
</div>
</body>
</html>