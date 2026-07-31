@extends('layouts.app', ['title' => 'Download Forms - NEC South Sudan', 'active_page' => 'downloads'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Download Forms</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('downloads.index') }}">Downloads</a></li>
                <li class="breadcrumb-item active">Forms</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Official Forms</h2>
            <p>Download official NEC forms for various applications</p>
        </div>

        @isset($forms)
        <div class="row g-4">
            @foreach($forms as $form)
            <div class="col-lg-6" data-aos="fade-up">
                <div class="form-download-card h-100">
                    <div class="form-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="form-details">
                        <h5>{{ $form->title }}</h5>
                        <p>{{ $form->description ?? 'Official NEC form' }}</p>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('downloads.serve', ['type' => 'file', 'id' => $form->id]) }}" class="btn btn-sm btn-primary" download>
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                            <small class="text-muted" style="font-size:0.72rem;"><i class="fas fa-download me-1"></i>{{ number_format($form->downloads_count ?? 0) }} downloads</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row g-4">
            @php
            $defaultForms = [
                ['title' => 'Voter Registration Form', 'desc' => 'For new voter registration or updates.'],
                ['title' => 'Transfer of Registration Form', 'desc' => 'To transfer registration to a new constituency.'],
                ['title' => 'Candidate Nomination Form', 'desc' => 'For candidates contesting elections.'],
                ['title' => 'Observer Accreditation Form', 'desc' => 'For election observer accreditation.'],
                ['title' => 'Political Party Registration Form', 'desc' => 'For new political party registration.'],
                ['title' => 'Complaint/Inquiry Form', 'desc' => 'For filing complaints or inquiries.'],
                ['title' => 'Voter ID Replacement Form', 'desc' => 'For requesting a replacement voter ID.'],
                ['title' => 'Media Accreditation Form', 'desc' => 'For media personnel election coverage.'],
            ];
            @endphp
            @foreach($defaultForms as $form)
            <div class="col-lg-6" data-aos="fade-up">
                <div class="form-download-card h-100">
                    <div class="form-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="form-details">
                        <h5>{{ $form['title'] }}</h5>
                        <p>{{ $form['desc'] }}</p>
                        <a href="#" class="btn btn-sm btn-primary" download>
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endisset
    </div>
</section>
@endsection
