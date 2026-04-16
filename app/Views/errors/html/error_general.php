<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; min-height: 100vh; }
        .error-card { background: white; border-radius: 10px; padding: 40px; text-align: center; max-width: 500px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .error-icon { font-size: 60px; color: #e74c3c; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">⚠️</div>
        <h3>Something Went Wrong</h3>
        <p><?= $message ?? 'An unexpected error occurred.' ?></p>
        <a href="/dashboard" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>