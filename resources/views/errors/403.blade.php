<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card card shadow-sm">
            <div class="card-body text-center">
                <h1 class="display-1 text-danger">403</h1>
                <h3 class="card-title text-muted">Forbidden Access</h3>
                <p class="card-text">
                    {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
                </p>
                <a href="{{ Auth::check() ? route(match (Auth::user()->type) {
                    'admin' => 'admin.dashboard',
                    'user' => 'user.dashboard',
                    'manager' => 'manager.dashboard',
                    default => 'user.dashboard',
                }) : route('login') }}" class="btn btn-primary mt-3">
                    {{ Auth::check() ? 'Go to Dashboard' : 'Login' }}
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>