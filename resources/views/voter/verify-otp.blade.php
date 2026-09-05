@extends('layouts.app')

@section('title', 'Verify Your Registration - National Elections Commission')

@push('styles')
<style>
    .otp-body { background: #f4f7f5; min-height: 70vh; }
    .otp-card { max-width: 520px; margin: 0 auto; border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); overflow: hidden; }
    .otp-header { background: linear-gradient(135deg, #0b6b3a, #1a5c38); color: #fff; padding: 34px 28px 26px; text-align: center; position: relative; overflow: hidden; }
    .otp-header::after { content: ''; position: absolute; top: -60px; right: -60px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.06); }
    .otp-header i { font-size: 2.6rem; opacity: 0.95; }
    .otp-header h3 { font-weight: 800; margin: 10px 0 4px; }
    .otp-header p { opacity: 0.8; font-size: 13px; margin-bottom: 0; }
    .otp-box { padding: 30px 34px 34px; }
    .otp-input { letter-spacing: 12px; font-size: 1.7rem; font-weight: 700; text-align: center; height: 62px; color: #0b6b3a; border: 2px solid #d5e5da; border-radius: 12px; }
    .otp-input:focus { border-color: #0b6b3a; box-shadow: 0 0 0 3px rgba(11,107,58,0.12); }
    .btn-otp { background: linear-gradient(135deg, #0b6b3a, #1a5c38); color: #fff; font-weight: 700; border-radius: 10px; padding: 12px 20px; width: 100%; border: none; }
    .btn-otp:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(11,107,58,0.3); }
    .btn-otp:disabled { opacity: 0.5; }
    .resend-btn { background: none; border: none; color: #0b6b3a; font-weight: 600; font-size: 13px; text-decoration: underline; }
    .demo-hint { background: #f0fdf4; border: 1px dashed #86efac; color: #166534; border-radius: 8px; padding: 10px 14px; font-size: 12px; }
</style>
@endpush

@section('content')
<div class="otp-body py-5">
    <div class="container">
        <div class="card otp-card">
            <div class="otp-header">
                <i class="fas fa-shield-halved"></i>
                <h3>Verify Your Registration</h3>
                <p>{{ session()->has('voter_draft') ? '' : '' }}{{ isset($voterName) ? 'Hi ' . e($voterName) . ', ' : '' }}enter the code sent to <strong>{{ e($identifier ?? '') }}</strong></p>
            </div>
            <div class="otp-box">
                @if(!empty($success))
                    <div class="alert alert-success">{{ $success }}</div>
                @endif
                @if(!empty($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                <form method="POST" action="{{ route('voter.register.verify-otp') }}" id="otpForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Verification Code</label>
                        <input type="text" name="otp" id="otpInput" class="form-control otp-input" inputmode="numeric" maxlength="6" autocomplete="one-time-code" autofocus required>
                    </div>
                    <button type="submit" class="btn btn-otp" id="otpSubmit" disabled>
                        <i class="fas fa-check-circle me-2"></i>Verify &amp; Complete Registration
                    </button>
                </form>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <form method="POST" action="{{ route('voter.register.resend-otp') }}">
                        @csrf
                        <button type="submit" class="resend-btn"><i class="fas fa-rotate-right me-1"></i>Resend code</button>
                    </form>
                    <a href="{{ route('voter.register') }}" class="text-muted small">Start over</a>
                </div>

                <div class="demo-hint mt-4">
                    <i class="fas fa-circle-info me-1"></i> For verification, use the 6-digit code sent to your email/phone. In the demo environment the code <strong>000000</strong> also works.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('otpInput');
        const submit = document.getElementById('otpSubmit');
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            submit.disabled = this.value.length !== 6;
        });
    })();
</script>
@endpush