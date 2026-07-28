@extends('layouts.app', ['title' => 'Speeches', 'active_page' => 'media', 'meta_description' => 'Speeches by NEC leadership including the Chairperson and Commissioners.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Speeches</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.speeches') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Speeches</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-microphone-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
$speechList = [];
if (isset($speeches) && $speeches->count()) {
    foreach ($speeches as $s) {
        $speechList[] = (object)[
            'id' => $s->id,
            'title' => $s->title ?? '',
            'speaker' => $s->speaker ?? '',
            'event' => $s->event_name ?? '',
            'date' => $s->speech_date ?? '',
            'file_url' => $s->document_url ?? '',
        ];
    }
}
@endphp

<section class="py-5">
    <div class="container">
        @if (empty($speechList))
        <div class="text-center text-muted py-5"><i class="fas fa-microphone-alt mb-3" style="font-size:3rem;opacity:0.3;display:block;"></i>No speeches published yet.</div>
        @else
        <div class="row g-4">
            @foreach ($speechList as $speech)
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:var(--nec-gold);color:#fff;font-size:1.3rem;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-uppercase fw-bold" style="color:var(--nec-gold);letter-spacing:1px;">{{ $speech->speaker }}</small>
                                <h5 class="fw-bold mb-1">{{ $speech->title }}</h5>
                                <p class="small text-muted mb-2"><i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($speech->date)->format('d M Y') }} &middot; <i class="fas fa-map-marker-alt me-1"></i>{{ $speech->event }}</p>
                                @if ($speech->file_url && $speech->file_url !== '#')
                                <a href="{{ $speech->file_url }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Download PDF</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
