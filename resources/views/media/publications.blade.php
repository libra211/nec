@extends('layouts.app', ['title' => 'Publications', 'active_page' => 'media', 'meta_description' => 'Official publications, annual reports, and election reports from NEC South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Publications</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.publications') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Publications</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-book-open text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
$pubList = [];
if (isset($publications) && $publications->count()) {
    foreach ($publications as $p) {
        $pubList[] = [
            'id' => $p->id,
            'title' => $p->title ?? '',
            'description' => $p->description ?? '',
            'file_url' => $p->file_path ?? '#',
            'file_size' => $p->file_size ?? '',
            'file_type' => $p->file_type ?? 'pdf',
            'published_at' => $p->created_at ?? '',
        ];
    }
}
if (empty($pubList)) {
    $pubList = [
        ['id' => 1, 'title' => 'Annual Report 2024-2025', 'description' => 'Comprehensive annual report detailing the activities, achievements, and financial statements of the NEC for the fiscal year 2024-2025.', 'file_url' => '#', 'file_size' => '4.2 MB', 'file_type' => 'pdf', 'published_at' => '2025-12-15'],
        ['id' => 2, 'title' => 'Annual Report 2023-2024', 'description' => 'Detailed annual report covering the Commission\'s operations and electoral preparations during the 2023-2024 fiscal year.', 'file_url' => '#', 'file_size' => '3.8 MB', 'file_type' => 'pdf', 'published_at' => '2024-12-10'],
        ['id' => 3, 'title' => 'Post-Election Report 2021', 'description' => 'Comprehensive post-election evaluation report documenting the conduct, outcomes, and lessons learned from the 2021 elections.', 'file_url' => '#', 'file_size' => '5.1 MB', 'file_type' => 'pdf', 'published_at' => '2022-03-20'],
        ['id' => 4, 'title' => 'Election Guidelines for Political Parties', 'description' => 'Official guidelines and regulations governing the participation of political parties in the electoral process.', 'file_url' => '#', 'file_size' => '2.3 MB', 'file_type' => 'pdf', 'published_at' => '2026-01-15'],
        ['id' => 5, 'title' => 'Voter Registration Operational Manual', 'description' => 'Standard operating procedures and guidelines for voter registration officers across all registration centers.', 'file_url' => '#', 'file_size' => '3.5 MB', 'file_type' => 'pdf', 'published_at' => '2026-01-10'],
        ['id' => 6, 'title' => 'Code of Conduct for Election Observers', 'description' => 'The code of conduct that all accredited election observers must adhere to during the electoral process.', 'file_url' => '#', 'file_size' => '1.1 MB', 'file_type' => 'pdf', 'published_at' => '2026-02-01'],
    ];
}
@endphp

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach ($pubList as $pub)
            <div class="col-lg-6" data-aos="fade-up">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="file-icon file-icon-pdf" style="width: 56px; height: 64px; background: #e74c3c; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff;">
                                    <i class="fas fa-file-pdf" style="font-size: 1.5rem;"></i>
                                    <small style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase;">PDF</small>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $pub['title'] }}</h5>
                                <p class="small text-muted mb-2">{{ $pub['description'] }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ date('d M Y', strtotime($pub['published_at'])) }} &middot; <i class="fas fa-file me-1"></i>{{ $pub['file_size'] }}</small>
                                    <a href="{{ $pub['file_url'] }}" class="btn btn-sm btn-success" style="background: var(--nec-green); border-color: var(--nec-green);"><i class="fas fa-download me-1"></i> Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
