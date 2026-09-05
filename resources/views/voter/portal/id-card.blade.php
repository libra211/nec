@extends('layouts.app', ['title' => 'Voter ID Card - NEC South Sudan', 'active_page' => 'voter'])

@section('extra_head')
<style>
.id-card-container { max-width: 550px; margin: 0 auto; }
.voter-id-card {
    background: #fff; border-radius: 20px; overflow: hidden;
    box-shadow: 0 12px 48px rgba(0,0,0,0.15); border: 3px solid #d4af37;
    position: relative;
}
.card-top-stripe {
    height: 6px; background: linear-gradient(90deg, #000 0%, #000 16.6%, #CE1126 16.6%, #CE1126 33.2%, #078930 33.2%, #078930 49.8%, #0F47AF 49.8%, #0F47AF 66.4%, #FCDD09 66.4%, #FCDD09 83%, #000 83%);
}
.card-top-stripe { height: 4px; background: repeating-linear-gradient(90deg, #000 0 14%, #CE1126 14% 28%, #078930 28% 42%, #0F47AF 42% 56%, #FCDD09 56% 70%, #000 70% 84%); }
.card-header-section {
    background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
    padding: 20px 24px 14px; text-align: center; color: #fff; position: relative; overflow: hidden;
}
.card-header-section::before {
    content: ''; position: absolute; top: -30px; right: -30px; width: 120px; height: 120px;
    border-radius: 50%; background: rgba(255,255,255,0.06);
}
.card-header-section::after {
    content: ''; position: absolute; bottom: -40px; left: -20px; width: 140px; height: 140px;
    border-radius: 50%; background: rgba(255,255,255,0.04);
}
.card-header-section .nec-emblem { font-size: 32px; margin-bottom: 4px; position: relative; z-index: 1; }
.card-header-section .country { font-size: 9px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; opacity: 0.65; position: relative; z-index: 1; }
.card-header-section .org-name { font-size: 14px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 1px; position: relative; z-index: 1; }
.card-header-section .card-type { font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; opacity: 0.55; position: relative; z-index: 1; }

.card-body-section { padding: 20px 24px; }
.photo-and-name { display: flex; gap: 20px; align-items: flex-start; margin-bottom: 16px; }
.voter-photo-area {
    width: 90px; height: 110px; border-radius: 12px; background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 2px solid #bbf7d0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    overflow: hidden; position: relative;
}
.voter-photo-area .initials {
    font-size: 28px; font-weight: 800; color: #166534; letter-spacing: -1px;
}
.voter-photo-area .photo-overlay {
    position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.4);
    text-align: center; font-size: 8px; color: #fff; padding: 2px 0; font-weight: 600;
}
.name-section { flex: 1; }
.name-section .voter-name {
    font-size: 17px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 4px;
}
.name-section .voter-id-badge {
    display: inline-block; background: #f0fdf4; border: 1px solid #bbf7d0;
    padding: 2px 10px; border-radius: 20px; font-size: 12px; font-family: 'Courier New', monospace;
    font-weight: 700; color: #166534; letter-spacing: 1px;
}

.detail-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; margin-bottom: 14px;
}
.detail-item { display: flex; flex-direction: column; gap: 1px; }
.detail-item .label {
    font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: #94a3b8;
}
.detail-item .value {
    font-size: 12px; font-weight: 600; color: #1e293b; line-height: 1.3;
}
.detail-item.full-width { grid-column: 1 / -1; }

.divider { height: 1px; background: linear-gradient(90deg, transparent, #e2e8f0, transparent); margin: 12px 0; }

.reg-attribution {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 10px 14px; margin-bottom: 14px;
}
.reg-attribution .attr-title {
    font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    color: #94a3b8; margin-bottom: 6px;
}
.reg-attribution .attr-row {
    display: flex; align-items: center; gap: 6px; font-size: 11px; margin-bottom: 3px;
}
.reg-attribution .attr-row .attr-icon { color: var(--nec-green); font-size: 12px; width: 16px; text-align: center; }
.reg-attribution .attr-row .attr-label { color: #64748b; min-width: 60px; }
.reg-attribution .attr-row .attr-value { color: #1e293b; font-weight: 600; }
.reg-type-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 12px; font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.reg-type-badge.self { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
.reg-type-badge.agent { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

.barcode-area {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 12px; text-align: center;
}
.barcode-lines {
    display: flex; align-items: flex-end; justify-content: center; gap: 1.5px; height: 40px; margin-bottom: 6px;
}
.barcode-lines .bar { background: #0f172a; border-radius: 0.5px; }

.qr-and-verify { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
.qr-box {
    width: 70px; height: 70px; border: 2px solid #e2e8f0; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; background: #fff; position: relative;
}
.qr-box .qr-pattern {
    width: 54px; height: 54px;
    background:
        linear-gradient(90deg, #0f172a 3px, transparent 3px) 0 0 / 6px 6px,
        linear-gradient(0deg, #0f172a 3px, transparent 3px) 0 0 / 6px 6px;
    opacity: 0.08; border-radius: 4px;
}
.qr-box .qr-corner { position: absolute; width: 14px; height: 14px; border: 2.5px solid #0f172a; border-radius: 2px; }
.qr-box .qr-corner:nth-child(2) { top: 4px; left: 4px; }
.qr-box .qr-corner:nth-child(3) { top: 4px; right: 4px; }
.qr-box .qr-corner:nth-child(4) { bottom: 4px; left: 4px; }
.verify-section { text-align: right; }
.verify-section .verify-label { font-size: 9px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.verify-section .verify-url { font-size: 11px; color: var(--nec-green); font-weight: 700; }
.verify-section .verify-text { font-size: 10px; color: #64748b; margin-top: 2px; }

.card-footer {
    background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 24px;
    display: flex; justify-content: space-between; align-items: center;
    font-size: 9px; color: #94a3b8;
}
.card-footer .valid { font-weight: 700; color: #64748b; }
.card-footer .nec-brand { font-weight: 800; color: var(--nec-green); letter-spacing: 0.5px; }

@media print {
    body * { visibility: hidden; }
    #idCardPrint, #idCardPrint * { visibility: visible; }
    #idCardPrint { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); margin: 0; }
    .no-print { display: none !important; }
    .voter-id-card { box-shadow: none; }
}
</style>
@endsection

@section('hero')
<section style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);padding:24px 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('voter.portal.dashboard') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Dashboard</a></li>
                        <li class="breadcrumb-item active" style="color:#fff;" aria-current="page">ID Card</li>
                    </ol>
                </nav>
                <h4 class="text-white fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Voter ID Card</h4>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        @php
            $voter = $voter ?? null;
            $initials = '';
            if ($voter && $voter->full_name) {
                $parts = explode(' ', $voter->full_name);
                $initials = mb_substr($parts[0], 0, 1);
                if (count($parts) > 1) $initials .= mb_substr(end($parts), 0, 1);
            }
            [$eligibilityBadge, $eligibilityColor, $eligibilityText] = match (true) {
                $voter && $voter->isDeceased() => ['bg-dark', 'text-black-50', 'Deceased'],
                $voter && $voter->isPreRegistered() => ['bg-warning', 'text-dark', 'Pre-Registered'],
                default => ['bg-success', 'text-white', 'Eligible to Vote'],
            };
            $maskedNational = '';
            if (!empty($voter->national_id)) {
                $maskedNational = substr($voter->national_id, 0, 3) . '****' . substr($voter->national_id, -2);
            }
            $validUntil = date('d M Y', strtotime('+5 years'));
            $vid = $voter->voter_id ?? 'NEC0000000';
            $bars = [];
            for ($i = 0; $i < strlen($vid); $i++) {
                $c = ord($vid[$i]);
                $bars[] = max(1, ($c % 5) + 1);
                $bars[] = max(1, ($c % 3) + 1);
            }
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="id-card-container" id="idCardPrint">
                    <div class="voter-id-card">
                        <div class="card-top-stripe"></div>

                        <div class="card-header-section">
                            <div class="nec-emblem">🇿🇦</div>
                            <div class="country">Republic of South Sudan</div>
                            <div class="org-name">National Elections Commission</div>
                            <div class="card-type">Voter Identification Card</div>
                        </div>

                        <div class="card-body-section">
                            <div class="photo-and-name">
                                <div class="voter-photo-area">
                                    <div class="initials">{{ strtoupper($initials) }}</div>
                                    <div class="photo-overlay">PHOTO</div>
                                </div>
                                <div class="name-section">
                                    <div class="voter-name">{{ $voter->full_name ?? 'N/A' }}</div>
                                    <div class="voter-id-badge">{{ $voter->voter_id ?? 'N/A' }}</div>
                                    <div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap;">
                                        @php $rt = $voter->registration_type ?? 'self'; @endphp
                                        <span class="reg-type-badge {{ $rt }}">
                                            @if($rt === 'self')
                                                <i class="bi bi-person"></i> Self-Registered
                                            @else
                                                <i class="bi bi-people"></i> Agent-Assisted
                                            @endif
                                        </span>
                                        <span class="reg-type-badge {{ $eligibilityBadge }}" style="background:{{ $eligibilityBadge === 'bg-warning' ? '#fef3c7' : ($eligibilityBadge === 'bg-dark' ? '#343a40' : '#d1fae5') }};color:{{ $eligibilityBadge === 'bg-warning' ? '#92400e' : ($eligibilityBadge === 'bg-dark' ? '#fff' : '#166534') }};border:none;">
                                            <i class="bi {{ $voter->isDeceased() ? 'bi-heartbreak' : ($voter->isPreRegistered() ? 'bi-hourglass-split' : 'bi-check-circle') }}"></i>
                                            {{ $eligibilityText }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Gender</span>
                                    <span class="value">{{ ($voter->gender ?? '') === 'M' ? 'Male' : (($voter->gender ?? '') === 'F' ? 'Female' : ($voter->gender ?? 'N/A')) }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Date of Birth</span>
                                    <span class="value">{{ $voter->dob ? date('d M Y', strtotime($voter->dob)) : 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">National ID</span>
                                    <span class="value" style="font-family:monospace;">{{ $maskedNational ?: '—' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">State</span>
                                    <span class="value">{{ $voter->state ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">County</span>
                                    <span class="value">{{ $voter->county ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Constituency</span>
                                    <span class="value">{{ $voter->constituency ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Payam</span>
                                    <span class="value">{{ $voter->payam ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Polling Station</span>
                                    <span class="value">{{ $voter->polling_station ?? 'Not Assigned' }}</span>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="reg-attribution">
                                <div class="attr-title"><i class="bi bi-info-circle me-1"></i> Registration Information</div>
                                <div class="attr-row">
                                    <span class="attr-icon"><i class="bi bi-person-check"></i></span>
                                    <span class="attr-label">Registered by:</span>
                                    <span class="attr-value">{{ $voter->registered_by_name ?? 'System' }}</span>
                                </div>
                                @if($voter->registered_by_title)
                                <div class="attr-row">
                                    <span class="attr-icon"><i class="bi bi-briefcase"></i></span>
                                    <span class="attr-label">Title:</span>
                                    <span class="attr-value">{{ $voter->registered_by_title }}</span>
                                </div>
                                @endif
                                @if($voter->registered_by_location)
                                <div class="attr-row">
                                    <span class="attr-icon"><i class="bi bi-geo-alt"></i></span>
                                    <span class="attr-label">Location:</span>
                                    <span class="attr-value">{{ $voter->registered_by_location }}</span>
                                </div>
                                @endif
                                <div class="attr-row">
                                    <span class="attr-icon"><i class="bi bi-calendar3"></i></span>
                                    <span class="attr-label">Date:</span>
                                    <span class="attr-value">{{ $voter->registered_at ? date('d M Y', strtotime($voter->registered_at)) : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="barcode-area">
                                <div class="barcode-lines">
                                    @foreach($bars as $i => $h)
                                        <div class="bar" style="width:{{ ($i % 2 === 0) ? '2px' : '1px' }};height:{{ $h * 7 }}px;"></div>
                                    @endforeach
                                </div>
                                <div style="font-size:11px;font-family:'Courier New',monospace;color:#334155;font-weight:700;letter-spacing:2.5px;">{{ $vid }}</div>
                            </div>

                            <div class="qr-and-verify">
                                <div class="qr-box">
                                    <div class="qr-pattern"></div>
                                    <div class="qr-corner"></div>
                                    <div class="qr-corner"></div>
                                    <div class="qr-corner"></div>
                                </div>
                                <div class="verify-section">
                                    <div class="verify-label">Verify Online</div>
                                    <div class="verify-url">nec.gov.ss/verify</div>
                                    <div class="verify-text">Enter Voter ID to verify authenticity</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <span class="valid">Valid until: {{ $validUntil }}</span>
                            <span class="nec-brand">NEC South Sudan</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 no-print">
                    <button onclick="window.print()" class="btn" style="background:var(--nec-green);color:#fff;padding:10px 28px;border-radius:10px;font-weight:600;font-size:14px;">
                        <i class="bi bi-printer me-2"></i> Print ID Card
                    </button>
                    <a href="{{ route('voter.portal.dashboard') }}" class="btn btn-outline-secondary ms-2" style="border-radius:10px;font-weight:600;font-size:14px;">
                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
