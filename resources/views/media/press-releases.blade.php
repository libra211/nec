@extends('layouts.app', ['title' => 'Press Releases', 'active_page' => 'media', 'meta_description' => 'Official press releases from the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Press Releases</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.press-releases') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Press Releases</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-bullhorn text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
$releaseList = [];
if (isset($releases) && $releases->count()) {
    foreach ($releases as $r) {
        $releaseList[] = [
            'id' => $r->id,
            'title' => $r->title ?? '',
            'excerpt' => $r->excerpt ?? '',
            'published_at' => $r->created_at instanceof \Carbon\Carbon ? $r->created_at->format('Y-m-d') : ($r->created_at ?? date('Y-m-d')),
        ];
    }
}
if (empty($releaseList)) {
    $releaseList = [
        ['id' => 1, 'title' => 'NEC Announces Final Voter Register Display Schedule', 'excerpt' => 'The National Elections Commission has announced the schedule for the display of the final voter register at all registration centers across the country.', 'published_at' => '2026-03-20'],
        ['id' => 2, 'title' => 'Commission Condemns Acts of Electoral Violence', 'excerpt' => 'The NEC strongly condemns recent acts of violence targeting electoral activities and urges all stakeholders to maintain peace.', 'published_at' => '2026-03-18'],
        ['id' => 3, 'title' => 'NEC Accredits Additional Election Observers', 'excerpt' => 'The Commission has accredited an additional batch of domestic and international election observers to monitor the electoral process.', 'published_at' => '2026-03-15'],
        ['id' => 4, 'title' => 'Statement on the Procurement of Election Materials', 'excerpt' => 'The NEC provides an update on the procurement and distribution of election materials for the upcoming general elections.', 'published_at' => '2026-03-12'],
        ['id' => 5, 'title' => 'NEC Confirms Election Date for 2026 General Elections', 'excerpt' => 'The Commission officially confirms the date for the 2026 general elections and calls on all citizens to participate peacefully.', 'published_at' => '2026-03-08'],
        ['id' => 6, 'title' => 'Voter Registration Period Extended by Two Weeks', 'excerpt' => 'The NEC has extended the voter registration period by two weeks to ensure maximum citizen participation in the electoral process.', 'published_at' => '2026-03-01'],
    ];
}
@endphp

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach ($releaseList as $release)
            <div class="col-12" data-aos="fade-up">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0 text-center d-none d-md-block" style="width: 80px;">
                                <div class="fw-bold fs-4" style="color: var(--nec-green);">{{ date('d', strtotime($release['published_at'])) }}</div>
                                <div class="small text-muted text-uppercase">{{ date('M Y', strtotime($release['published_at'])) }}</div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">{{ $release['title'] }}</h5>
                                <p class="text-muted mb-2">{{ $release['excerpt'] }}</p>
                                <a href="#" class="btn btn-sm btn-link p-0" style="color: var(--nec-green);">Read Full Release <i class="fas fa-arrow-right ms-1"></i></a>
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
