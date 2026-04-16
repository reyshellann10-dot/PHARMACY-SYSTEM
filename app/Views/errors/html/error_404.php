<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .error-card { background: white; border-radius: 20px; padding: 50px; text-align: center; max-width: 500px; margin: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .error-number { font-size: 80px; font-weight: bold; color: #e74c3c; }
        .btn-home { background: #667eea; color: white; padding: 10px 30px; border-radius: 25px; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-number">404</div>
        <h3>Page Not Found</h3>
        <p>The page you requested could not be found.</p>
        <a href="/dashboard" class="btn-home">Back to Dashboard</a>
    </div>
</body>
</html>