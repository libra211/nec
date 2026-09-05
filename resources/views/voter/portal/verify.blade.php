<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Voter Registration - NEC South Sudan</title>
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
        .verify-wrapper { position: relative; z-index: 1; width: 100%; max-width: 560px; }
        .verify-card {
            background: #fff; border-radius: 20px; box-shadow: 0 8px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .verify-header {
            background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);
            padding: 28px 32px 22px; text-align: center; color: #fff; position: relative; overflow: hidden;
        }
        .verify-header::before {
            content: ''; position: absolute; top: -50px; right: -50px; width: 180px; height: 180px;
            border-radius: 50%; background: rgba(255,255,255,0.06);
        }
        .verify-header img { height: 56px; margin-bottom: 10px; border-radius: 8px; position: relative; z-index: 1; }
        .verify-header h3 { font-weight: 800; font-size: 20px; margin-bottom: 2px; position: relative; z-index: 1; }
        .verify-header p { font-size: 13px; opacity: 0.75; margin-bottom: 0; position: relative; z-index: 1; }
        .verify-body { padding: 28px 32px 32px; }
        .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
        .form-control {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 12px 14px; font-size: 15px; transition: all 0.2s;
        }
        .form-control:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1); }
        .btn-nec {
            background: var(--nec-green); border-color: var(--nec-green); color: #fff;
            font-weight: 700; border-radius: 10px; padding: 12px; font-size: 15px; transition: all 0.2s;
        }
        .btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; }
        .result-card { display: none; }
        .result-card.show { display: block; }
        .result-header {
            background: linear-gradient(135deg, #065f46, #0d9488);
            border-radius: 14px 14px 0 0; padding: 20px 24px; color: #fff; text-align: center;
        }
        .result-body { border: 1px solid #eef0f2; border-top: 0; border-radius: 0 0 14px 14px; padding: 20px; }
        .result-field {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 0; border-bottom: 1px solid #f0f2f5;
        }
        .result-field:last-child { border-bottom: none; }
        .result-field .rf-label { font-size: 12px; font-weight: 600; color: #8c8f94; text-transform: uppercase; letter-spacing: 0.3px; }
        .result-field .rf-value { font-size: 14px; font-weight: 700; color: #1d2327; }
        .verified-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 2px solid #6ee7b7;
            border-radius: 12px; padding: 12px 20px; margin-top: 16px;
        }
        .verified-badge i { color: #16a34a; font-size: 20px; }
        .verified-badge span { color: #065f46; font-weight: 700; font-size: 14px; }
        .not-found { display: none; text-align: center; padding: 32px 20px; }
        .not-found.show { display: block; }
        .flag-bar { display: flex; height: 4px; }
        .flag-bar .stripe { flex: 1; }
        .stripe-black { background: #000; } .stripe-red { background: #CE1126; }
        .stripe-green { background: #078930; } .stripe-blue { background: #0F47AF; }
        .stripe-gold { background: #FCDD09; }
        .back-link { position: absolute; top: 20px; left: 20px; z-index: 2; }
        .back-link a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; font-weight: 500; }
        .back-link a:hover { color: #fff; }
        .home-link { position: absolute; top: 20px; right: 20px; z-index: 2; }
        .home-link a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; font-weight: 500; }
        .home-link a:hover { color: #fff; }
        .note-box {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 12px 16px; margin-top: 20px; font-size: 12px; color: #64748b; text-align: center;
        }
        @media (max-width: 576px) { .verify-body { padding: 20px 16px 24px; } }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="{{ url('/voter') }}"><i class="fas fa-arrow-left me-1"></i> Voter Services</a>
    </div>
    <div class="home-link">
        <a href="{{ route('home') }}">NEC Home <i class="fas fa-external-link-alt ms-1"></i></a>
    </div>

    <div class="verify-wrapper">
        <div class="flag-bar" style="border-radius:20px 20px 0 0;overflow:hidden;">
            <div class="stripe stripe-black"></div>
            <div class="stripe stripe-red"></div>
            <div class="stripe stripe-green"></div>
            <div class="stripe stripe-blue"></div>
            <div class="stripe stripe-gold"></div>
        </div>

        <div class="verify-card">
            <div class="verify-header">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
                <h3>Verify Voter Registration</h3>
                <p>Check if a voter is registered with NEC</p>
            </div>
            <div class="verify-body">

                {{-- Search Form --}}
                <div id="searchSection">
                    <form method="GET" action="{{ route('voter.portal.verify') }}" id="verifyForm">
                        <div class="mb-3">
                            <label class="form-label">Enter Voter ID Number</label>
                            <input type="text" name="voter_id" class="form-control form-control-lg" placeholder="e.g., NEC26M123456" value="{{ request('voter_id') }}" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-nec">
                                <i class="fas fa-search me-2"></i> Verify
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Verified Result --}}
                @if(isset($voter) && $voter)
                <div class="result-card show mt-4" id="resultSection">
                    <div class="result-header">
                        <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                            <i class="fas fa-user" style="font-size:22px;color:#fff;"></i>
                        </div>
                        <div style="font-size:17px;font-weight:800;">{{ $voter->full_name ?? 'N/A' }}</div>
                        <div style="font-size:12px;opacity:0.7;font-family:monospace;">{{ $voter->voter_id ?? '' }}</div>
                    </div>
                    <div class="result-body">
                        <div class="result-field">
                            <span class="rf-label">Voter ID</span>
                            <span class="rf-value" style="font-family:monospace;">{{ $voter->voter_id ?? 'N/A' }}</span>
                        </div>
                        <div class="result-field">
                            <span class="rf-label">Name</span>
                            <span class="rf-value">{{ $voter->full_name ?? 'N/A' }}</span>
                        </div>
                        <div class="result-field">
                            <span class="rf-label">State</span>
                            <span class="rf-value">{{ $voter->state ?? 'N/A' }}</span>
                        </div>
                        <div class="result-field">
                            <span class="rf-label">Constituency</span>
                            <span class="rf-value">{{ $voter->constituency ?? 'N/A' }}</span>
                        </div>
                        <div class="result-field">
                            <span class="rf-label">Polling Station</span>
                            <span class="rf-value">{{ $voter->polling_station ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="result-field">
                            <span class="rf-label">Status</span>
                            <span class="rf-value">
                                @php
                                    $vStatus = $voter->status ?? 'active';
                                    $vBadge = $vStatus === 'deceased' ? 'bg-dark' : 'bg-success';
                                    $vIcon = $vStatus === 'deceased' ? 'fa-heart-crack' : 'fa-check-circle';
                                @endphp
                                <span class="badge {{ $vBadge }}" style="font-size:11px;font-weight:700;">
                                    <i class="fas {{ $vIcon }} me-1"></i> {{ ucfirst($vStatus) }}
                                </span>
                            </span>
                        </div>

                        @if($voter->isDeceased())
                        <div class="w-100 p-3 rounded" style="background:#343a40;color:#fff;text-align:center;font-size:13px;font-weight:600;">
                            <i class="fas fa-heart-crack me-1"></i> This record is marked deceased and is excluded from the electoral roll
                        </div>
                        @else
                        <div class="verified-badge w-100">
                            <i class="fas fa-shield-alt"></i>
                            <span>This voter is verified as registered with NEC</span>
                        </div>
                        @endif

                        <div class="text-center mt-3">
                            <button onclick="resetSearch()" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-weight:600;">
                                <i class="fas fa-redo me-1"></i> Verify Another
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Not Found --}}
                @if(isset($notFound) && $notFound)
                <div class="not-found show mt-4" id="notFoundSection">
                    <div style="width:56px;height:56px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="fas fa-times" style="font-size:24px;color:#dc2626;"></i>
                    </div>
                    <h5 style="font-weight:700;color:#1d2327;">No Voter Found</h5>
                    <p style="font-size:13px;color:#64748b;margin-bottom:16px;">No voter registration found with the provided Voter ID. Please check the number and try again.</p>
                    <button onclick="resetSearch()" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-weight:600;">
                        <i class="fas fa-redo me-1"></i> Try Again
                    </button>
                </div>
                @endif

                <div class="note-box">
                    <i class="fas fa-lock me-1"></i> This tool verifies registration status only. Personal details are protected under NEC data privacy policy.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function resetSearch() {
        window.location.href = '{{ route("voter.portal.verify") }}';
    }
    </script>
</body>
</html>
