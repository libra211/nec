@extends('layouts.app', ['title' => 'Check Registration Status - NEC South Sudan', 'active_page' => 'voters'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Check Registration Status</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('voter.index') }}" class="text-white-50">Voters</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Registration Status</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-clipboard-check text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                @if(isset($error) && $error)
                <div class="alert alert-danger d-flex align-items-center gap-3 py-3" style="border-radius:12px;">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                    <div>{{ $error }}</div>
                </div>
                @endif

                @if(isset($voter) && $voter)
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div style="width:64px;height:64px;border-radius:50%;background:{{ $voter->status === 'active' ? '#dcfce7' : '#fef3c7' }};display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-{{ $voter->status === 'active' ? 'check-circle text-success' : 'clock text-warning' }}" style="font-size:28px;"></i>
                            </div>
                            <h4 class="fw-bold mb-1">Registration Found</h4>
                            <span class="badge bg-{{ $voter->status === 'active' ? 'success' : ($voter->status === 'pending' ? 'warning' : 'secondary') }} fs-6">
                                {{ ucfirst($voter->status) }}
                            </span>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tr><td class="text-muted" style="width:40%;">Voter ID</td><td class="fw-bold font-monospace">{{ $voter->voter_id }}</td></tr>
                            <tr><td class="text-muted">Full Name</td><td class="fw-bold">{{ $voter->full_name }}</td></tr>
                            <tr><td class="text-muted">State</td><td>{{ $voter->state }}</td></tr>
                            <tr><td class="text-muted">Constituency</td><td>{{ $voter->constituency }}</td></tr>
                            <tr><td class="text-muted">Polling Station</td><td>{{ $voter->polling_station ?? 'Not assigned' }}</td></tr>
                            <tr><td class="text-muted">Registered</td><td>{{ date('d M Y', strtotime($voter->created_at)) }}</td></tr>
                        </table>
                    </div>
                </div>
                @else
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-search text-muted" style="font-size:3rem;"></i>
                            <h4 class="fw-bold mt-3">Check Your Registration</h4>
                            <p class="text-muted">Enter your Voter ID or phone number to check your registration status.</p>
                        </div>
                        <form method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Voter ID</label>
                                <input type="text" name="voter_id" class="form-control" placeholder="e.g. NEC26M123456" value="{{ old('voter_id') }}">
                            </div>
                            <div class="text-center text-muted my-3">— OR —</div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +211 912 345 678" value="{{ old('phone') }}">
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg text-white" style="background:var(--nec-green);border-radius:10px;font-weight:600;">
                                    <i class="fas fa-search me-1"></i> Check Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('voter.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Voter Services</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
