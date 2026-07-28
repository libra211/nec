@extends('layouts.app', ['title' => 'Observer Portal - NEC South Sudan', 'active_page' => 'observers'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Observer Portal</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Observer Portal</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-binoculars text-white-50" style="font-size:3.5rem;opacity:0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm h-100 text-center p-5" style="border-radius:14px;">
                    <div class="mb-4" style="width:80px;height:80px;border-radius:50%;background:rgba(0,145,76,0.1);display:inline-flex;align-items:center;justify-content:center;color:var(--nec-green);font-size:2rem;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Observer Accreditation</h4>
                    <p class="text-muted mb-4">Learn about the observer accreditation process, eligibility requirements, and how to apply.</p>
                    <a href="{{ route('observers.accredit') }}" class="btn btn-success fw-semibold px-4"><i class="fas fa-info-circle me-2"></i>Accreditation Info</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm h-100 text-center p-5" style="border-radius:14px;">
                    <div class="mb-4" style="width:80px;height:80px;border-radius:50%;background:rgba(14,113,72,0.1);display:inline-flex;align-items:center;justify-content:center;color:#0e7148;font-size:2rem;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Apply Now</h4>
                    <p class="text-muted mb-4">Submit your observer accreditation application online. International and domestic observers welcome.</p>
                    <a href="{{ route('observers.apply') }}" class="btn btn-outline-success fw-semibold px-4"><i class="fas fa-arrow-right me-2"></i>Start Application</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
