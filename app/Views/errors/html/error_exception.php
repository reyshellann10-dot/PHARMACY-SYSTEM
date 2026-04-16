<!DOCTYPE html>
<html>
<head>
    <title>System Exception</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-danger text-white">Exception</div>
            <div class="card-body">
                <p><?= $message ?? 'An exception occurred' ?></p>
                <?php if (ENVIRONMENT === 'development' && isset($file)): ?>
                    <small class="text-muted">File: <?= $file ?> line <?= $line ?></small>
                <?php endif; ?>
                <hr>
                <a href="/dashboard" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</body>
</html>