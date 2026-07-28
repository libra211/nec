@extends('layouts.app', ['title' => $event->title])

@section('hero')
    <div class="hero-section" style="background: linear-gradient(135deg, #0a3d62 0%, #1a6b3c 100%); padding: 80px 0 40px;">
        <div class="container text-center text-white">
            <h1 class="display-5 fw-bold" style="color: #fff !important;">{{ $event->title }}</h1>
            <p class="lead" style="color: #d4edda !important;">
                <i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('l, d F Y') }}
                @if($event->location) &middot; <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }} @endif
            </p>
        </div>
    </div>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            @if($event->featured_image)
            <img src="{{ $event->featured_image }}" alt="{{ $event->title }}" class="img-fluid rounded shadow-sm mb-4" style="max-height:450px;width:100%;object-fit:cover;">
            @endif

            <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
                <span><i class="far fa-calendar me-1"></i> Start: {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, h:i A') }}</span>
                @if($event->end_date)
                <span><i class="far fa-calendar-check me-1"></i> End: {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, h:i A') }}</span>
                @endif
                @if($event->organizer)
                <span><i class="fas fa-user-tie me-1"></i> {{ $event->organizer }}</span>
                @endif
                @if($event->event_type)
                <span class="badge bg-nec-green ms-2">{{ ucfirst($event->event_type) }}</span>
                @endif
            </div>

            <div class="content-body">
                {!! nl2br(e($event->description)) !!}
            </div>

            <a href="{{ route('events.index') }}" class="btn btn-outline-nec-green mt-4"><i class="fas fa-arrow-left me-1"></i> All Events</a>
        </div>

        <div class="col-lg-4">
            @if($upcoming->count())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-nec-green text-white fw-bold">Upcoming Events</div>
                <div class="card-body p-0 list-group list-group-flush">
                    @foreach($upcoming as $u)
                    <a href="{{ route('events.show', $u->slug) }}" class="list-group-item list-group-item-action">
                        <small class="text-muted d-block"><i class="far fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($u->start_date)->format('d M Y') }}</small>
                        <strong>{{ $u->title }}</strong>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
