@extends('layouts.app', ['title' => 'Videos', 'active_page' => 'media', 'meta_description' => 'Video library from the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Videos</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.videos') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Videos</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-video text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
$videoList = [];
if (isset($videos) && $videos->count()) {
    foreach ($videos as $v) {
        $videoList[] = [
            'id' => $v->id,
            'title' => $v->title ?? '',
            'description' => $v->description ?? '',
            'youtube_url' => $v->url ?? '#',
            'duration' => $v->duration ?? '',
            'thumbnail_url' => $v->thumbnail ?? '',
            'views' => $v->views ?? 0,
            'likes' => $v->likes ?? 0,
            'published_at' => $v->created_at ?? '',
        ];
    }
}
if (empty($videoList)) {
    $videoList = [
        ['id' => 1, 'title' => 'NEC Chairperson Address to the Nation on 2026 Elections', 'description' => 'The Chairperson outlines the Commission\'s preparedness for the upcoming general elections.', 'youtube_url' => '#', 'duration' => '12:30', 'thumbnail_url' => '', 'published_at' => '2026-03-15', 'views' => 1456, 'likes' => 89],
        ['id' => 2, 'title' => 'How to Register to Vote - Step by Step Guide', 'description' => 'A comprehensive guide on the voter registration process for South Sudanese citizens.', 'youtube_url' => '#', 'duration' => '8:45', 'thumbnail_url' => '', 'published_at' => '2026-03-10', 'views' => 892, 'likes' => 54],
        ['id' => 3, 'title' => 'Highlights: Electoral Officers Training Workshop', 'description' => 'Key moments from the nationwide training of electoral officers.', 'youtube_url' => '#', 'duration' => '15:20', 'thumbnail_url' => '', 'published_at' => '2026-03-05', 'views' => 673, 'likes' => 42],
        ['id' => 4, 'title' => 'Understanding the Voting Process', 'description' => 'Learn how to cast your vote on election day, from arrival at the polling station to the final count.', 'youtube_url' => '#', 'duration' => '10:15', 'thumbnail_url' => '', 'published_at' => '2026-02-28', 'views' => 2104, 'likes' => 127],
        ['id' => 5, 'title' => 'NEC Stakeholder Engagement Forum Highlights', 'description' => 'Highlights from the recent stakeholder engagement forum with civil society and political parties.', 'youtube_url' => '#', 'duration' => '20:00', 'thumbnail_url' => '', 'published_at' => '2026-02-20', 'views' => 534, 'likes' => 31],
        ['id' => 6, 'title' => 'Domestic Observer Training Program Overview', 'description' => 'An overview of the training program for domestic election observers.', 'youtube_url' => '#', 'duration' => '14:30', 'thumbnail_url' => '', 'published_at' => '2026-02-15', 'views' => 789, 'likes' => 48],
    ];
}
@endphp

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach ($videoList as $video)
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card border-0 shadow-sm h-100">
                    <a href="{{ $video['youtube_url'] }}" class="text-decoration-none" target="_blank">
                        <div class="card-img-top position-relative" style="height: 210px; background: linear-gradient(135deg, var(--nec-red) 0%, #8b0000 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-play-circle text-white" style="font-size: 4rem; opacity: 0.8; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));"></i>
                            <span class="position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded small" style="background: rgba(0,0,0,0.7); color: #fff;">{{ $video['duration'] }}</span>
                        </div>
                    </a>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">{{ $video['title'] }}</h6>
                        <p class="small text-muted mb-2">{{ $video['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ date('d M Y', strtotime($video['published_at'])) }}</small>
                            <small class="text-muted"><i class="fas fa-eye me-1"></i>{{ number_format($video['views']) }}</small>
                        </div>
                        <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                            <button class="btn btn-sm btn-like d-inline-flex align-items-center gap-1" data-id="{{ $video['id'] }}" style="border:none;background:none;font-size:0.8rem;color:#6c757d;padding:2px 8px;border-radius:6px;transition:all 0.15s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background=''" data-likes="{{ $video['likes'] }}">
                                <i class="fas fa-thumbs-up"></i> <span class="like-count">{{ number_format($video['likes']) }}</span>
                            </button>
                            <small class="text-muted"><i class="fas fa-video me-1"></i>{{ $video['duration'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-like').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var countSpan = this.querySelector('.like-count');
            var icon = this.querySelector('i');
            var self = this;
            fetch('{{ url("api/v1/like") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                body: 'id=' + id
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    countSpan.textContent = data.likes.toLocaleString();
                    self.setAttribute('data-likes', data.likes);
                    icon.style.color = '#00914c';
                    self.style.color = '#00914c';
                    self.style.pointerEvents = 'none';
                    self.style.opacity = '0.7';
                }
            })
            .catch(function() {});
        });
    });
});
</script>
@endsection
