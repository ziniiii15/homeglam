<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login • HomeGlam</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .auth-card {
            max-width: 420px;
            width: 100%;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .auth-header-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(214,51,132,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D63384;
        }
        .form-control:focus {
            border-color: #D63384;
            box-shadow: 0 0 0 .2rem rgba(214,51,132,.15);
        }
        .btn-primary {
            background: #D63384;
            border-color: #D63384;
        }
        .btn-primary:hover {
            background: #b02a6c;
            border-color: #b02a6c;
        }
        a {
            color: #D63384;
        }
        a:hover {
            color: #b02a6c;
        }
    </style>
</head>
<body>
    <div class="auth-card bg-white p-4 p-md-5">
        <div class="d-flex align-items-center mb-4">
            <div class="auth-header-icon me-3">
                <i class="bi bi-shield-lock-fill fs-4"></i>
            </div>
            <div>
                <h1 class="h4 mb-0">Admin Login</h1>
                <small class="text-muted">Access the HomeGlam admin dashboard</small>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
        </form>

        <p class="text-center text-muted small mb-0">
            Don't have an account?
            <a href="{{ route('admin.register') }}">Register</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
