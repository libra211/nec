@extends('layouts.app', ['title' => 'Elections - NEC South Sudan', 'active_page' => 'elections'])

@section('hero')
<section class="page-header" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Elections</li>
            </ol>
        </nav>
        <h1 class="fw-bold text-white mb-2">Elections</h1>
        <p class="text-white-50 mb-0">Information about the electoral process in South Sudan</p>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <a href="{{ route('elections.calendar') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h5>Election Calendar</h5>
                    <p class="text-muted small">View key dates for upcoming elections</p>
                </a>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('elections.types') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-list"></i></div>
                    <h5>Types of Elections</h5>
                    <p class="text-muted small">Learn about different election types</p>
                </a>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('elections.results') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-chart-bar"></i></div>
                    <h5>Election Results</h5>
                    <p class="text-muted small">View election results and statistics</p>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
