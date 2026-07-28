<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - NEC South Sudan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a3c8f 0%, #2E8B57 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 420px; width: 100%; border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-header { text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 16px 16px 0 0; }
        .login-header img { height: 60px; margin-bottom: 1rem; }
        .login-body { padding: 2rem; }
        .btn-nec-green { background-color: #2E8B57; border-color: #2E8B57; color: #fff; }
        .btn-nec-green:hover { background-color: #24704a; border-color: #24704a; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card">
            <div class="login-header">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
                <h4>National Elections Commission</h4>
                <p class="text-muted mb-0">Administration Portal</p>
            </div>
            <div class="login-body">
                @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-nec-green w-100 btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </button>
                </form>
            </div>
            <div class="text-center pb-3">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
