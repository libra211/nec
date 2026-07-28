@extends('layouts.app', ['title' => 'Leadership', 'active_page' => 'about', 'meta_description' => 'Meet the leadership of the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Leadership</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Leadership</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-user-tie text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color: var(--nec-black);">Commission Leadership</h2>
            <p class="text-muted">Meet the Commissioners of the National Elections Commission of South Sudan</p>
        </div>

        @php
            if (!isset($commissioners) || empty($commissioners)) {
                $commissioners = [
                    (object)['id' => 1, 'name' => 'Hon. Prof. Abednego A. A. Akok', 'position' => 'Chairperson', 'bio' => 'Appointed as Chairperson of NEC with extensive experience in public administration and electoral management.', 'position_order' => 1],
                    (object)['id' => 2, 'name' => 'Hon. Sarah N. Wani', 'position' => 'Deputy Chairperson', 'bio' => 'Experienced legal professional and advocate for democratic governance and electoral integrity.', 'position_order' => 2],
                    (object)['id' => 3, 'name' => 'Hon. James L. Maker', 'position' => 'Commissioner', 'bio' => 'Specialist in electoral operations and field coordination with decades of public service.', 'position_order' => 3],
                    (object)['id' => 4, 'name' => 'Hon. Dr. Mary A. Nyandeng', 'position' => 'Commissioner', 'bio' => 'Academic and civil society leader with expertise in civic education and voter outreach.', 'position_order' => 4],
                    (object)['id' => 5, 'name' => 'Hon. John M. Deng', 'position' => 'Commissioner', 'bio' => 'Former diplomat with extensive experience in international relations and election observation.', 'position_order' => 5],
                    (object)['id' => 6, 'name' => 'Hon. Rebecca N. Kiden', 'position' => 'Commissioner', 'bio' => 'Gender equality advocate with a strong background in human rights and legal affairs.', 'position_order' => 6],
                ];
            }
            $chairperson = collect($commissioners)->firstWhere('position', 'Chairperson');
        @endphp

        @if($chairperson)
        <div class="row justify-content-center mb-5" data-aos="fade-up">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 text-center p-4">
                            <div style="width: 220px; height: 220px; border-radius: 50%; margin: 0 auto; overflow: hidden; border: 4px solid var(--nec-gold); box-shadow: 0 0 30px rgba(212,175,55,0.3);">
                                @if($chairperson->photo ?? null)
                                    <img src="{{ asset($chairperson->photo) }}" alt="{{ $chairperson->name }}" style="width:100%;height:100%;object-fit:cover;transform:scale(1.3);">
                                @else
                                    <div style="width:100%;height:100%;background:linear-gradient(135deg, #1a3c8f, #2E8B57);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-user-tie" style="font-size: 5rem; color: rgba(255,255,255,0.8);"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 p-4">
                            <span class="badge bg-warning text-dark mb-2">Chairperson</span>
                            <h3 class="fw-bold mb-1" style="color: var(--nec-black);">{{ $chairperson->name }}</h3>
                            <p class="text-muted mb-2" style="color: var(--nec-green) !important;"><strong>Chairperson, National Elections Commission</strong></p>
                            <p class="text-muted mb-0">{{ $chairperson->bio ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4">
            @foreach($commissioners as $c)
                @if($c->position === 'Chairperson')
                    @continue
                @endif
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        @if($c->photo ?? null)
                            <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden; border: 3px solid var(--nec-green);">
                                <img src="{{ asset($c->photo) }}" alt="{{ $c->name }}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #1a3c8f, #2E8B57); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-circle" style="font-size: 3.5rem; color: rgba(255,255,255,0.8);"></i>
                            </div>
                        @endif
                        <span class="badge bg-success mx-auto mb-2" style="background: var(--nec-green) !important; width: fit-content;">{{ $c->position }}</span>
                        <h5 class="fw-bold mb-1" style="color: var(--nec-black);">{{ $c->name }}</h5>
                        <p class="text-muted small mb-0">{{ $c->bio ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
