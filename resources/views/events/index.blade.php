@extends('layouts.app', ['title' => 'Events'])

@section('hero')
    <div class="hero-section" style="background: linear-gradient(135deg, #0a3d62 0%, #1a6b3c 100%); padding: 80px 0 40px;">
        <div class="container text-center text-white">
            <h1 class="display-5 fw-bold" style="color: #fff !important;">Events</h1>
            <p class="lead" style="color: #d4edda !important;">Upcoming and past events from the National Elections Commission</p>
        </div>
    </div>
@endsection

@section('content')
<div class="container py-5">

    @if($events->count())
    <h2 class="fw-bold mb-4"><i class="fas fa-calendar-alt text-nec-green me-2"></i>Upcoming Events</h2>
    <div class="row g-4 mb-5">
        @foreach($events as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm" data-aos="fade-up">
                @if($item->featured_image)
                <img src="{{ $item->featured_image }}" class="card-img-top" alt="{{ $item->title }}" style="height:200px;object-fit:cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y, h:i A') }}
                        @if($item->location) &nbsp;|&nbsp; <i class="fas fa-map-marker-alt me-1"></i>{{ $item->location }} @endif
                    </div>
                    <h5 class="card-title fw-semibold">{{ $item->title }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit(strip_tags($item->description), 150) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-nec-green">{{ $item->event_type ?? 'Public' }}</span>
                        <a href="{{ route('events.show', $item->slug) }}" class="btn btn-sm btn-outline-nec-green">View Details &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $events->links() }}
    @endif

    @if($pastEvents->count())
    <h2 class="fw-bold mb-4 mt-5"><i class="fas fa-history text-muted me-2"></i>Past Events</h2>
    <div class="row g-4">
        @foreach($pastEvents as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm opacity-75" data-aos="fade-up">
                @if($item->featured_image)
                <img src="{{ $item->featured_image }}" class="card-img-top" alt="{{ $item->title }}" style="height:180px;object-fit:cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}
                        @if($item->location) &nbsp;|&nbsp; <i class="fas fa-map-marker-alt me-1"></i>{{ $item->location }} @endif
                    </div>
                    <h5 class="card-title fw-semibold">{{ $item->title }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit(strip_tags($item->description), 120) }}</p>
                    <a href="{{ route('events.show', $item->slug) }}" class="btn btn-sm btn-outline-secondary">Read More &rarr;</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $pastEvents->links() }}
    @endif

    @if(!$events->count() && !$pastEvents->count())
    <div class="text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
        <h4 class="fw-bold">No Events Yet</h4>
        <p class="text-muted">Check back soon for upcoming events from the NEC.</p>
        <a href="/" class="btn btn-nec-green"><i class="fas fa-home me-1"></i> Back to Home</a>
    </div>
    @endif
</div>
@endsection
