@extends('layouts.app', ['title' => 'Photo Gallery', 'active_page' => 'media', 'meta_description' => 'Photo gallery of NEC events, elections, and voter registration activities in South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Photo Gallery</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.gallery') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Photo Gallery</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-images text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
$galleryItems = [];
$albums = [];
if (isset($photos) && $photos->count()) {
    $rawAlbums = [];
    foreach ($photos as $item) {
        $galleryItems[] = (object)[
            'id' => $item->id,
            'title' => $item->title ?? '',
            'description' => $item->description ?? '',
            'image_path' => $item->image_path ?? '',
            'album' => $item->album ?? '',
            'created_at' => $item->created_at,
        ];
        if ($item->album && !in_array($item->album, $rawAlbums)) {
            $rawAlbums[] = $item->album;
        }
    }
    $albums = $rawAlbums;
}
@endphp

<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <div class="btn-group flex-wrap" role="group" aria-label="Gallery filter">
                <button class="btn btn-success active" data-filter="all" style="background: var(--nec-green); border-color: var(--nec-green);">All</button>
                @foreach ($albums as $ab)
                <button class="btn btn-outline-success" data-filter="{{ $ab }}" style="border-color: var(--nec-green); color: var(--nec-green);">{{ ucfirst($ab) }}</button>
                @endforeach
            </div>
        </div>
        <div class="row g-4" id="galleryGrid">
            @if (empty($galleryItems))
            <div class="col-12 text-center text-muted py-5"><i class="fas fa-images mb-3" style="font-size:3rem;opacity:0.3;display:block;"></i>No gallery images available yet.</div>
            @else
                @foreach ($galleryItems as $item)
                <div class="col-md-6 col-lg-4 gallery-item" data-category="{{ $item->album }}">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        @if ($item->image_path)
                        <a href="{{ $item->image_path }}" data-lightbox="gallery" data-title="{{ $item->title }}">
                            <img src="{{ $item->image_path }}" alt="{{ $item->title }}" style="width:100%;height:220px;object-fit:cover;">
                        </a>
                        @else
                        <div style="height:220px;background:linear-gradient(135deg,var(--nec-gold) 0%,var(--nec-green) 100%);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-image text-white" style="font-size:4rem;opacity:0.6;"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ $item->title }}</h6>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function() {
    $('[data-filter]').click(function() {
        var filter = $(this).data('filter');
        $('[data-filter]').removeClass('active btn-success').addClass('btn-outline-success');
        $(this).addClass('active btn-success').removeClass('btn-outline-success');
        if (filter === 'all') {
            $('.gallery-item').show();
        } else {
            $('.gallery-item').hide();
            $('.gallery-item[data-category="' + filter + '"]').show();
        }
    });
});
</script>
@endsection
