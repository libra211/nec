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
<section class="py-5">
    <div class="container">
        @if ($albums->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="fas fa-images mb-3" style="font-size:3rem;opacity:0.3;display:block;"></i>
            No gallery albums available yet.
        </div>
        @else
        <div class="row g-4">
            @foreach ($albums as $album)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm overflow-hidden album-card" style="cursor:pointer;transition:transform .2s,box-shadow .2s;" data-slug="{{ $album->slug }}" onclick="openLightbox('album-{{ $album->id }}')">
                    <div style="position:relative;overflow:hidden;">
                        @if ($album->featured_image)
                        <img src="{{ asset($album->featured_image) }}" alt="{{ $album->title }}" style="width:100%;height:240px;object-fit:cover;transition:transform .3s;">
                        @else
                        <div style="height:240px;background:linear-gradient(135deg,var(--nec-gold) 0%,var(--nec-green) 100%);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-image text-white" style="font-size:4rem;opacity:0.6;"></i>
                        </div>
                        @endif
                        <div style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.65);color:#fff;border-radius:20px;padding:2px 12px;font-size:0.75rem;backdrop-filter:blur(4px);">
                            <i class="fas fa-image me-1"></i>{{ $album->images->count() }}
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">{{ $album->title }}</h5>
                        @if($album->description)
                        <p class="small text-muted mb-2">{{ Str::limit($album->description, 100) }}</p>
                        @endif
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($album->created_at)->format('d M Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Lightbox data for this album (hidden) -->
            <div id="album-{{ $album->id }}" class="album-images" style="display:none;">
                @foreach($album->images as $img)
                <div class="lb-image" data-src="{{ asset($img->image_path) }}" data-title="{{ $img->alt_text }}"></div>
                @endforeach
                @if($album->featured_image && $album->images->where('image_path', $album->featured_image)->isEmpty())
                <div class="lb-image" data-src="{{ asset($album->featured_image) }}" data-title="{{ $album->title }} (Cover)"></div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $albums->links() }}</div>
        @endif
    </div>
</section>

<!-- Lightbox Modal -->
<div id="galleryLightbox" class="lightbox-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.92);z-index:9999;justify-content:center;align-items:center;">
    <button type="button" id="lb-close" style="position:fixed;top:20px;right:25px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:10000;"><i class="fas fa-times"></i></button>
    <div style="position:relative;max-width:90vw;max-height:90vh;display:flex;align-items:center;justify-content:center;">
        <button type="button" id="lb-prev" style="position:fixed;left:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.12);border:none;color:#fff;width:48px;height:48px;border-radius:50%;font-size:1.3rem;cursor:pointer;backdrop-filter:blur(4px);transition:background .2s;"><i class="fas fa-chevron-left"></i></button>
        <div style="text-align:center;">
            <img id="lb-img" src="" alt="" style="max-width:90vw;max-height:82vh;border-radius:6px;object-fit:contain;">
            <div id="lb-counter" style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin-top:8px;"></div>
            <div id="lb-title" style="color:#fff;font-size:0.9rem;margin-top:4px;font-weight:500;"></div>
        </div>
        <button type="button" id="lb-next" style="position:fixed;right:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.12);border:none;color:#fff;width:48px;height:48px;border-radius:50%;font-size:1.3rem;cursor:pointer;backdrop-filter:blur(4px);transition:background .2s;"><i class="fas fa-chevron-right"></i></button>
    </div>
</div>
@endsection

@section('extra_scripts')
<style>
.album-card:hover { transform:translateY(-4px); box-shadow:0 12px 24px rgba(0,0,0,0.12) !important; }
.album-card:hover img { transform:scale(1.05); }
#galleryLightbox { display:none; }
#galleryLightbox.active { display:flex; }
#lb-prev:hover, #lb-next:hover { background:rgba(255,255,255,0.25); }
</style>
<script>
var currentImages = [];
var currentIndex = 0;

function openLightbox(albumId) {
    var albumEl = document.getElementById(albumId);
    if (!albumEl) return;
    var items = albumEl.querySelectorAll('.lb-image');
    currentImages = [];
    items.forEach(function(item) {
        currentImages.push({
            src: item.dataset.src,
            title: item.dataset.title
        });
    });
    if (currentImages.length === 0) return;
    currentIndex = 0;
    showImage();
    document.getElementById('galleryLightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function showImage() {
    var img = document.getElementById('lb-img');
    var counter = document.getElementById('lb-counter');
    var title = document.getElementById('lb-title');
    img.src = currentImages[currentIndex].src;
    counter.textContent = (currentIndex + 1) + ' / ' + currentImages.length;
    title.textContent = currentImages[currentIndex].title || '';
}

document.getElementById('lb-prev').addEventListener('click', function(e) {
    e.stopPropagation();
    if (currentImages.length === 0) return;
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    showImage();
});

document.getElementById('lb-next').addEventListener('click', function(e) {
    e.stopPropagation();
    if (currentImages.length === 0) return;
    currentIndex = (currentIndex + 1) % currentImages.length;
    showImage();
});

document.getElementById('lb-close').addEventListener('click', function() {
    closeLightbox();
});

document.getElementById('galleryLightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('galleryLightbox').classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') document.getElementById('lb-prev').click();
    if (e.key === 'ArrowRight') document.getElementById('lb-next').click();
});

function closeLightbox() {
    document.getElementById('galleryLightbox').classList.remove('active');
    document.body.style.overflow = '';
}

// Auto-open album from URL param
$(document).ready(function() {
    var params = new URLSearchParams(window.location.search);
    var albumSlug = params.get('album');
    if (albumSlug) {
        var card = document.querySelector('[data-slug="' + albumSlug + '"]');
        if (card) setTimeout(function() {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            card.click();
        }, 500);
    }
});
</script>
@endsection