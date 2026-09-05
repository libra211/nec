@extends('layouts.app', ['title' => 'Transfer Registration - NEC South Sudan', 'active_page' => 'voter'])

@section('extra_head')
<style>
.section-title {
    font-size: 12px; font-weight: 700; color: var(--nec-green); text-transform: uppercase;
    letter-spacing: 1.5px; padding-bottom: 8px; border-bottom: 2px solid rgba(46,139,87,0.12);
    margin-bottom: 20px;
}
.transfer-card {
    background: #fff; border-radius: 16px; box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    padding: 28px 32px; margin-bottom: 24px;
}
.form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
.form-control, .form-select {
    border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; font-size: 14px; transition: all 0.2s;
}
.form-control:focus, .form-select:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1); }
.form-control:disabled, .form-control[readonly] { background: #f8fafc; color: #475569; }
.btn-nec {
    background: var(--nec-green); border-color: var(--nec-green); color: #fff;
    font-weight: 700; border-radius: 10px; padding: 12px 24px; font-size: 14px; transition: all 0.2s;
}
.btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; }
.info-box {
    background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #93c5fd;
    border-radius: 12px; padding: 16px; margin-bottom: 24px;
}
.current-detail {
    display: flex; align-items: center; gap: 10px; padding: 10px 14px;
    background: #fafbfc; border-radius: 10px; border: 1px solid #f0f2f5;
}
.current-detail .detail-icon {
    width: 32px; height: 32px; border-radius: 8px; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; font-size: 13px;
}
.current-detail .detail-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #8c8f94; }
.current-detail .detail-value { font-size: 13px; font-weight: 600; color: #1d2327; }
</style>
@endsection

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);padding:24px 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('voter.portal.dashboard') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Dashboard</a></li>
                        <li class="breadcrumb-item active" style="color:#fff;" aria-current="page">Transfer Request</li>
                    </ol>
                </nav>
                <h4 class="text-white fw-bold mb-0">Transfer of Voter Registration</h4>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php $voter = $voter ?? null; @endphp

                {{-- Info Box --}}
                <div class="info-box">
                    <div class="d-flex gap-2">
                        <i class="fas fa-info-circle mt-1" style="color:#2563eb;"></i>
                        <div style="font-size:13px;color:#1e40af;">
                            <strong>Transfer Guidelines:</strong>
                            <ul class="mb-0 mt-1" style="padding-left:18px;">
                                <li>You must be a registered voter to request a transfer.</li>
                                <li>Transfers must be completed at least <strong>30 days before Election Day</strong>.</li>
                                <li>You will need to provide proof of your new address (utility bill, tenancy agreement, etc.).</li>
                                <li>Transfer requests are reviewed within <strong>5-7 working days</strong>.</li>
                                <li>You will be notified via email once your transfer is processed.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Current Registration --}}
                <div class="transfer-card">
                    <div class="section-title"><i class="fas fa-map-marker-alt me-2"></i> Current Registration Details</div>
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-3">
                            <div class="current-detail">
                                <div class="detail-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-flag"></i></div>
                                <div><div class="detail-label">State</div><div class="detail-value">{{ $voter->state ?? 'N/A' }}</div></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="current-detail">
                                <div class="detail-icon" style="background:#fefce8;color:#ca8a04;"><i class="fas fa-building"></i></div>
                                <div><div class="detail-label">County</div><div class="detail-value">{{ $voter->county ?? 'N/A' }}</div></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="current-detail">
                                <div class="detail-icon" style="background:#f0f7ff;color:#2563eb;"><i class="fas fa-landmark"></i></div>
                                <div><div class="detail-label">Constituency</div><div class="detail-value">{{ $voter->constituency ?? 'N/A' }}</div></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="current-detail">
                                <div class="detail-icon" style="background:#ecfeff;color:#0891b2;"><i class="fas fa-school"></i></div>
                                <div><div class="detail-label">Polling Station</div><div class="detail-value">{{ $voter->polling_station ?? 'N/A' }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transfer Form --}}
                <div class="transfer-card">
                    <div class="section-title"><i class="fas fa-exchange-alt me-2"></i> New Location Details</div>
                    <form method="POST" action="{{ route('voter.portal.transfer.submit') }}">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">New State <span class="text-danger">*</span></label>
                                <select name="new_state" class="form-select" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                    <option value="{{ $state }}" {{ old('new_state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                @error('new_state') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">New County <span class="text-danger">*</span></label>
                                <input type="text" name="new_county" class="form-control" placeholder="County name" value="{{ old('new_county') }}" required>
                                @error('new_county') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">New Constituency <span class="text-danger">*</span></label>
                                <input type="text" name="new_constituency" class="form-control" placeholder="Constituency" value="{{ old('new_constituency') }}" required>
                                @error('new_constituency') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Payam <span class="text-danger">*</span></label>
                                <input type="text" name="new_payam" class="form-control" placeholder="Payam name" value="{{ old('new_payam') }}" required>
                                @error('new_payam') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Polling Station</label>
                                <input type="text" name="new_polling_station" class="form-control" placeholder="Preferred polling station" value="{{ old('new_polling_station') }}">
                                @error('new_polling_station') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Reason for Transfer <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Explain why you are requesting a transfer (e.g., relocation due to work, marriage, etc.)" required>{{ old('reason') }}</textarea>
                            @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Supporting Document (optional)</label>
                            <input type="file" name="proof_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text small text-muted">Upload proof of new address (utility bill, tenancy letter, etc.) — Max 5MB</div>
                            @error('proof_document') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="confirmTransfer" required>
                            <label class="form-check-label" for="confirmTransfer" style="font-size:13px;">
                                I confirm that I have relocated to the new address and request transfer of my voter registration.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-nec w-100">
                            <i class="fas fa-paper-plane me-2"></i> Submit Transfer Request
                        </button>
                    </form>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-clock fa-2x mb-2" style="color:var(--nec-gold);"></i>
                        <h6 class="fw-bold mb-1">Processing Time</h6>
                        <p class="text-muted small mb-0">Transfer requests are reviewed within 5-7 working days. You will receive an email notification once your request has been processed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
