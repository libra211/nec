<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NEC Voter Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --nec-green: #2E8B57; --nec-green-dark: #1a5c38; --nec-gold: #D4AF37; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
            display: flex; align-items: center; justify-content: center; padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .reset-wrapper { position: relative; z-index: 1; width: 100%; max-width: 480px; }
        .reset-card {
            background: #fff; border-radius: 20px; box-shadow: 0 8px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .reset-header {
            background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);
            padding: 28px 32px 22px; text-align: center; color: #fff; position: relative; overflow: hidden;
        }
        .reset-header::before {
            content: ''; position: absolute; top: -50px; right: -50px; width: 180px; height: 180px;
            border-radius: 50%; background: rgba(255,255,255,0.06);
        }
        .reset-header img { height: 56px; margin-bottom: 10px; border-radius: 8px; position: relative; z-index: 1; }
        .reset-header h3 { font-weight: 800; font-size: 20px; margin-bottom: 2px; position: relative; z-index: 1; }
        .reset-header p { font-size: 13px; opacity: 0.75; margin-bottom: 0; position: relative; z-index: 1; }
        .reset-body { padding: 28px 32px 32px; }
        .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
        .form-control {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px; font-size: 14px; transition: all 0.2s;
        }
        .form-control:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1); }
        .btn-nec {
            background: var(--nec-green); border-color: var(--nec-green); color: #fff;
            font-weight: 700; border-radius: 10px; padding: 12px; font-size: 15px; transition: all 0.2s;
        }
        .btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; }
        .flag-bar { display: flex; height: 4px; }
        .flag-bar .stripe { flex: 1; }
        .stripe-black { background: #000; }
        .stripe-red { background: #CE1126; }
        .stripe-green { background: #078930; }
        .stripe-blue { background: #0F47AF; }
        .stripe-gold { background: #FCDD09; }
        .back-link { position: absolute; top: 20px; left: 20px; z-index: 2; }
        .back-link a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; font-weight: 500; }
        .back-link a:hover { color: #fff; }
        @media (max-width: 576px) { .reset-body { padding: 20px 16px 24px; } }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="{{ route('voter.portal.login') }}"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
    </div>

    <div class="reset-wrapper">
        <div class="flag-bar" style="border-radius:20px 20px 0 0;overflow:hidden;">
            <div class="stripe stripe-black"></div>
            <div class="stripe stripe-red"></div>
            <div class="stripe stripe-green"></div>
            <div class="stripe stripe-blue"></div>
            <div class="stripe stripe-gold"></div>
        </div>

        <div class="reset-card">
            <div class="reset-header">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
                <h3>Reset Password</h3>
                <p>Recover access to your voter portal account</p>
            </div>
            <div class="reset-body">

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('voter.portal.forgot-password.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Voter ID Number <span class="text-danger">*</span></label>
                        <input type="text" name="voter_id" class="form-control" placeholder="e.g., NEC26M123456" value="{{ old('voter_id') }}" required>
                        @error('voter_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                        @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-nec">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('voter.portal.login') }}" style="color:var(--nec-green);font-weight:600;text-decoration:none;font-size:13px;">
                        <i class="fas fa-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
