@extends('layouts.app', ['title' => 'GIS Electoral Map', 'active_page' => 'gis'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">GIS Electoral Map</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">GIS Electoral Map</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-map-marked-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="gis-map-container" style="height: 600px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px dashed #dee2e6;">
            <div class="text-center p-5">
                <i class="fas fa-map-marked-alt" style="font-size: 4rem; color: var(--nec-green); opacity: 0.5; display: block; margin-bottom: 1rem;"></i>
                <h4 class="fw-bold" style="color: var(--nec-black);">Interactive Electoral Map</h4>
                <p class="text-muted">Interactive electoral map of South Sudan — coming soon.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <h3 class="fw-bold mb-4" style="color: var(--nec-black);">States of South Sudan</h3>
        <div class="row g-3">
            @foreach($states as $i => $state)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <span class="badge rounded-pill" style="background: var(--nec-green); width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">{{ $i + 1 }}</span>
                        <span class="fw-semibold small">{{ $state->name }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
