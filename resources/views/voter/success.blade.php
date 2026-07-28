@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="card-body p-0">
                    <div class="text-white text-center py-5" style="background: linear-gradient(135deg, var(--nec-green), #236B45);">
                        <div class="mb-3">
                            <i class="fas fa-check-circle" style="font-size:4rem; opacity:0.95;"></i>
                        </div>
                        <h2 class="fw-bold mb-2">Registration Successful!</h2>
                        <p class="mb-0 opacity-90">Your voter registration has been completed.</p>
                    </div>

                    <div class="p-4 p-md-5">
                        {{-- Voter ID Card --}}
                        <div class="card border-2 mb-4" style="border-color: var(--nec-green) !important; border-radius: 12px;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:1px;">Republic of South Sudan</small>
                                        <h6 class="fw-bold mb-0" style="color: var(--nec-green);">National Elections Commission</h6>
                                    </div>
                                    <div class="text-end">
                                        <img src="{{ asset('assets/images/nec-logo.png') }}" alt="NEC" height="40">
                                    </div>
                                </div>

                                <div class="text-center py-3 mb-3" style="background: rgba(46,139,87,0.05); border-radius: 8px;">
                                    <small class="text-muted d-block mb-1">Your Voter ID Number</small>
                                    <h3 class="fw-bold mb-0" style="color: var(--nec-green); letter-spacing:2px; font-family: 'Courier New', monospace;">
                                        {{ session('new_voter_id', session('voter_id', 'N/A')) }}
                                    </h3>
                                </div>

                                <div class="row g-2" style="font-size:0.85rem;">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Full Name</small>
                                        <span class="fw-semibold">{{ session('new_voter_name', 'N/A') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Gender</small>
                                        <span class="fw-semibold">{{ session('new_voter_gender', 'N/A') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">State</small>
                                        <span class="fw-semibold">{{ session('new_voter_state', 'N/A') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">County</small>
                                        <span class="fw-semibold">{{ session('new_voter_county', 'N/A') }}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted d-block">Polling Station</small>
                                        <span class="fw-semibold">{{ session('new_voter_station', 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Important Notice --}}
                        <div class="alert alert-warning mb-4" style="border-radius: 8px;">
                            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-1"></i> Important</h6>
                            <ul class="mb-0" style="font-size:0.88rem;">
                                <li>Please <strong>screenshot or save</strong> your Voter ID number above.</li>
                                <li>You will need this ID to check your registration status.</li>
                                <li>Carry a valid photo ID on election day.</li>
                                <li>Your information is protected under South Sudan electoral law.</li>
                            </ul>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('voter.status') }}" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fas fa-search me-1"></i> Check Status
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('home') }}" class="btn w-100 py-2" style="background:var(--nec-green);color:#fff;">
                                    <i class="fas fa-home me-1"></i> Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
