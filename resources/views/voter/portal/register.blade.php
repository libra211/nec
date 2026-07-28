<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Portal Registration - NEC South Sudan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --nec-green: #2E8B57; --nec-green-dark: #1a5c38; --nec-gold: #D4AF37; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
            display: flex; align-items: center; justify-content: center; padding: 30px 20px;
            position: relative; overflow-x: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .reg-wrapper { position: relative; z-index: 1; width: 100%; max-width: 640px; }
        .reg-card {
            background: #fff; border-radius: 20px; box-shadow: 0 8px 40px rgba(0,0,0,0.15); overflow: hidden;
        }
        .reg-header {
            background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);
            padding: 28px 32px 22px; text-align: center; color: #fff; position: relative; overflow: hidden;
        }
        .reg-header::before {
            content: ''; position: absolute; top: -50px; right: -50px; width: 180px; height: 180px;
            border-radius: 50%; background: rgba(255,255,255,0.06);
        }
        .reg-header img { height: 56px; margin-bottom: 10px; border-radius: 8px; position: relative; z-index: 1; }
        .reg-header h3 { font-weight: 800; font-size: 20px; margin-bottom: 2px; position: relative; z-index: 1; }
        .reg-header p { font-size: 13px; opacity: 0.75; margin-bottom: 0; position: relative; z-index: 1; }
        .reg-body { padding: 28px 32px 32px; }
        .step-indicator { display: flex; gap: 0; margin-bottom: 28px; }
        .step-item { flex: 1; text-align: center; position: relative; }
        .step-item:not(:last-child)::after {
            content: ''; position: absolute; top: 18px; left: 60%; width: 80%; height: 2px;
            background: #e2e8f0; z-index: 0;
        }
        .step-item.done:not(:last-child)::after { background: var(--nec-green); }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%; display: inline-flex;
            align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
            position: relative; z-index: 1; margin-bottom: 6px;
        }
        .step-circle.active { background: var(--nec-green); color: #fff; }
        .step-circle.done { background: rgba(46,139,87,0.12); color: var(--nec-green); }
        .step-circle.pending { background: #f1f5f9; color: #94a3b8; }
        .step-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; }
        .step-item.active .step-label { color: var(--nec-green); }
        .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px; font-size: 14px; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1);
        }
        .btn-nec {
            background: var(--nec-green); border-color: var(--nec-green); color: #fff;
            font-weight: 700; border-radius: 10px; padding: 12px; font-size: 15px; transition: all 0.2s;
        }
        .btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; transform: translateY(-1px); }
        .step-panel { display: none; }
        .step-panel.active { display: block; }
        .info-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #93c5fd;
            border-radius: 12px; padding: 16px; margin-bottom: 24px;
        }
        .info-box i { color: #2563eb; }
        .info-box p { margin-bottom: 0; font-size: 13px; color: #1e40af; }
        .flag-bar { display: flex; height: 4px; }
        .flag-bar .stripe { flex: 1; }
        .stripe-black { background: #000; } .stripe-red { background: #CE1126; }
        .stripe-green { background: #078930; } .stripe-blue { background: #0F47AF; }
        .stripe-gold { background: #FCDD09; }
        .back-link { position: absolute; top: 20px; left: 20px; z-index: 2; }
        .back-link a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; font-weight: 500; }
        .back-link a:hover { color: #fff; }
        .input-group-text { border: 1.5px solid #e2e8f0; border-radius: 10px; background: #f8fafc; }
        @media (max-width: 576px) { .reg-body { padding: 20px 16px 24px; } .reg-header { padding: 24px 16px 18px; } }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i> Back to NEC</a>
    </div>

    <div class="reg-wrapper">
        <div class="flag-bar" style="border-radius:20px 20px 0 0;overflow:hidden;">
            <div class="stripe stripe-black"></div>
            <div class="stripe stripe-red"></div>
            <div class="stripe stripe-green"></div>
            <div class="stripe stripe-blue"></div>
            <div class="stripe stripe-gold"></div>
        </div>

        <div class="reg-card">
            <div class="reg-header">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
                <h3>Create Voter Account</h3>
                <p>Register to access the voter portal</p>
            </div>
            <div class="reg-body">

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="step-indicator">
                    <div class="step-item active" id="stepIndicator1">
                        <div class="step-circle active" id="stepCircle1">1</div>
                        <div class="step-label">Verify Identity</div>
                    </div>
                    <div class="step-item" id="stepIndicator2">
                        <div class="step-circle pending" id="stepCircle2">2</div>
                        <div class="step-label">Create Account</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="d-flex gap-2">
                        <i class="fas fa-info-circle mt-1"></i>
                        <p>You must be a registered voter to create an account. Your details will be verified against the NEC voter register.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('voter.portal.register.submit') }}">
                    @csrf

                    <!-- Step 1: Verify Identity -->
                    <div class="step-panel active" id="step1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Voter ID Number <span class="text-danger">*</span></label>
                                <input type="text" name="voter_id" class="form-control" placeholder="e.g., NEC26M123456" value="{{ old('voter_id') }}" required>
                                @error('voter_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">National ID Number <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" class="form-control" placeholder="e.g., SS-123456789" value="{{ old('national_id') }}" required>
                                @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Full Name (as registered) <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" placeholder="Enter your full legal name" value="{{ old('full_name') }}" required>
                                @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                                @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-nec" onclick="goToStep2()">
                                Verify &amp; Continue <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Create Account -->
                    <div class="step-panel" id="step2">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" placeholder="Min 8 characters" id="regPassword" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('regPassword', this)" style="border-radius:0 10px 10px 0;border-color:#e2e8f0;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">PIN Code (4-6 digits) <span class="text-danger">*</span></label>
                                <input type="password" name="pin_code" class="form-control" placeholder="For quick login" maxlength="6" pattern="[0-9]{4,6}" value="{{ old('pin_code') }}" required>
                                <div class="form-text small text-muted">4-6 digit PIN for quick login via Voter ID.</div>
                                @error('pin_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep1()" style="border-radius:10px;font-weight:600;">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>
                            <div class="flex-grow-1 d-grid">
                                <button type="submit" class="btn btn-nec">
                                    <i class="fas fa-user-plus me-2"></i> Verify &amp; Register
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p style="font-size:13px;color:#64748b;margin-bottom:4px;">Already have an account?</p>
                    <a href="{{ route('voter.portal.login') }}" style="color:var(--nec-green);font-weight:600;text-decoration:none;font-size:14px;">
                        Login here <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function goToStep2() {
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step2').classList.add('active');
        document.getElementById('stepCircle1').classList.remove('active');
        document.getElementById('stepCircle1').classList.add('done');
        document.getElementById('stepCircle1').innerHTML = '<i class="fas fa-check" style="font-size:14px;"></i>';
        document.getElementById('stepIndicator1').classList.remove('active');
        document.getElementById('stepIndicator1').classList.add('done');
        document.getElementById('stepCircle2').classList.remove('pending');
        document.getElementById('stepCircle2').classList.add('active');
        document.getElementById('stepIndicator2').classList.add('active');
    }
    function goToStep1() {
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
        document.getElementById('stepCircle1').classList.add('active');
        document.getElementById('stepCircle1').classList.remove('done');
        document.getElementById('stepCircle1').innerHTML = '1';
        document.getElementById('stepIndicator1').classList.add('active');
        document.getElementById('stepIndicator1').classList.remove('done');
        document.getElementById('stepCircle2').classList.add('pending');
        document.getElementById('stepCircle2').classList.remove('active');
        document.getElementById('stepIndicator2').classList.remove('active');
    }
    function togglePassword(id, btn) {
        var input = document.getElementById(id);
        var icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
        else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
    }
    </script>
</body>
</html>
