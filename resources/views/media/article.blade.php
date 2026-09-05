@extends('layouts.app', ['title' => isset($article) ? e($article->title) . ' - NEC News' : 'Article', 'active_page' => 'media', 'meta_description' => isset($article) ? e($article->excerpt) : ''])

@section('extra_head')
<style>
.article-header{background:linear-gradient(135deg,#0f5e3a 0%,#083d25 100%);position:relative;overflow:hidden;}
.article-header::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");pointer-events:none;}
.article-body{font-size:1.05rem;line-height:1.8;color:#334155;}
.article-body p{margin-bottom:1.2rem;}
.article-body ul{margin-bottom:1.2rem;padding-left:1.5rem;}
.article-body li{margin-bottom:0.5rem;}
.share-btn{width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;transition:all 0.15s;text-decoration:none;}
.share-btn:hover{transform:translateY(-2px);}
</style>
@endsection

@section('hero')
@php
$cat_colors = [
    'Elections' => '#0d6efd',
    'Voter Registration' => '#10b981',
    'Political Parties' => '#8b5cf6',
    'Training' => '#f59e0b',
    'Security' => '#ef4444',
    'Observers' => '#06b6d4',
];
$cat_icons = [
    'Elections' => 'fa-vote-yea',
    'Voter Registration' => 'fa-clipboard-list',
    'Political Parties' => 'fa-flag',
    'Training' => 'fa-chalkboard-teacher',
    'Security' => 'fa-shield-alt',
    'Observers' => 'fa-binoculars',
];
$categories = array_keys($cat_colors);

if (isset($article)) {
    $articleData = [
        'id' => $article->id,
        'title' => $article->title ?? '',
        'slug' => $article->slug ?? '',
        'excerpt' => $article->excerpt ?? '',
        'body' => $article->body ?? $article->content ?? '',
        'category' => $article->category ?? 'Elections',
        'image_url' => $article->image ?? '',
        'published_at' => $article->created_at,
        'views' => $article->views ?? 0,
    ];
} else {
    $articleData = [
        'id' => 1,
        'title' => 'NEC Announces Voter Registration Dates for 2026 General Elections',
        'slug' => 'voter-registration-2026',
        'excerpt' => 'The National Elections Commission has officially announced the dates for the upcoming voter registration exercise.',
        'body' => '<p>The National Elections Commission (NEC) of South Sudan has officially announced the dates for the upcoming voter registration exercise ahead of the 2026 general elections. The registration period will begin on <strong>1 June 2026</strong> and run through <strong>31 August 2026</strong>.</p><p>In a statement issued by the Commission\'s headquarters in Juba, NEC Chairperson emphasized the importance of broad citizen participation in the electoral process. \'Every eligible citizen must be given the opportunity to register and exercise their constitutional right to vote,\' the statement read.</p><p>The Commission has deployed over 2,000 registration kits across all ten states and four administrative areas. Mobile registration units will also be deployed to reach citizens in remote and hard-to-reach areas.</p><p>To be eligible for registration, citizens must:</p><ul><li>Be at least 18 years old by Election Day</li><li>Possess a valid National ID or birth certificate</li><li>Be a resident of the constituency where they intend to register</li></ul><p>NEC encourages all eligible citizens to take advantage of this opportunity to participate in shaping the future of South Sudan through the ballot box.</p>',
        'category' => 'Elections',
        'image_url' => '',
        'published_at' => '2026-03-15 09:30:00',
        'views' => 1247,
    ];
}

if (!function_exists('reading_time')) { function reading_time($text) {
    $words = str_word_count(strip_tags($text));
    return max(1, ceil($words / 200));
} }
$cat_color = $cat_colors[$articleData['category']] ?? '#6c757d';
$cat_icon = $cat_icons[$articleData['category']] ?? 'fa-tag';
@endphp

<section class="article-header">
    <div class="container position-relative" style="z-index:1;">
        <div class="row py-5">
            <div class="col-lg-8">
                <a href="{{ route('media.news') }}" class="text-white-50 small mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to News</a>
                <div class="mb-3">
                    <span class="badge" style="background:{{ $cat_color }};font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;padding:5px 14px;border-radius:20px;">
                        <i class="fas {{ $cat_icon }} me-1"></i>{{ $articleData['category'] }}
                    </span>
                </div>
                <h1 class="text-white fw-bold mb-3" style="font-size:2rem;line-height:1.3;">{{ $articleData['title'] }}</h1>
                <p class="text-white-50 mb-3" style="font-size:1.05rem;">{{ $articleData['excerpt'] }}</p>
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <small class="text-white-50"><i class="far fa-calendar-alt me-1"></i>{{ date('d M Y', strtotime($articleData['published_at'])) }}</small>
                    <small class="text-white-50"><i class="far fa-clock me-1"></i>{{ date('H:i', strtotime($articleData['published_at'])) }}</small>
                    <small class="text-white-50"><i class="fas fa-eye me-1"></i>{{ number_format($articleData['views']) }} views</small>
                    <small class="text-white-50"><i class="fas fa-book-open me-1"></i>{{ reading_time($articleData['body']) }} min read</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    @if ($articleData['image_url'])
                    <img src="{{ $articleData['image_url'] }}" class="card-img-top" alt="{{ $articleData['title'] }}" style="height:400px;object-fit:cover;border-radius:14px 14px 0 0;">
                    @endif
                    <div class="card-body p-4 p-md-5 article-body">
                        {!! strip_tags($articleData['body'], '<p><br><b><strong><i><em><u><a><ul><ol><li><blockquote><h2><h3><h4><h5><h6><img><table><tr><td><th><thead><tbody><span><div><hr><pre><code>') !!}
                    </div>
                </div>

                <!-- Share -->
                <div class="d-flex align-items-center gap-3 mt-4 p-4 bg-white rounded-3 shadow-sm" style="border-radius:12px;">
                    <span class="fw-semibold small text-muted text-uppercase" style="font-size:0.75rem;letter-spacing:0.5px;">Share this article</span>
                    <a href="https://www.facebook.com/sharer/sharer?u={{ urlencode(route('news.article', $articleData['slug'])) }}" target="_blank" class="share-btn" style="background:#1877F215;color:#1877F2;" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($articleData['title']) }}&url={{ urlencode(route('news.article', $articleData['slug'])) }}" target="_blank" class="share-btn" style="background:#00000010;color:#000;" title="Share on X"><i class="fab fa-x-twitter"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($articleData['title'] . ' ' . route('news.article', $articleData['slug'])) }}" target="_blank" class="share-btn" style="background:#25D36615;color:#25D366;" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:?subject={{ urlencode($articleData['title']) }}&body={{ urlencode(route('news.article', $articleData['slug'])) }}" class="share-btn" style="background:#ea433515;color:#ea4335;" title="Share via Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;color:#0f5e3a;"><i class="fas fa-tags me-2"></i>Categories</h6>
                        <ul class="list-unstyled mb-0">
                            @php
                            $categoryCounts = [];
                            if (isset($article) && method_exists($article, 'newQuery')) {
                                try {
                                    $categoryCounts = \App\Models\News::where('status', 'published')
                                        ->selectRaw('category, COUNT(*) as cnt')
                                        ->groupBy('category')
                                        ->pluck('cnt', 'category')
                                        ->toArray();
                                } catch (\Exception $e) {}
                            }
                            if (empty($categoryCounts)) {
                                $categoryCounts = array_fill_keys($categories, 0);
                            }
                            @endphp
                            @foreach ($categories as $cat)
                            @php $cnt = $categoryCounts[$cat] ?? 0; @endphp
                            <li class="mb-2">
                                <a href="{{ route('media.news') }}?category={{ urlencode($cat) }}" class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded" style="transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                    <span style="font-size:0.85rem;color:#1e293b;">
                                        <span class="d-inline-flex align-items-center justify-content-center me-2" style="width:28px;height:28px;border-radius:8px;background:{{ $cat_colors[$cat] ?? '#6c757d' }}15;color:{{ $cat_colors[$cat] ?? '#6c757d' }};font-size:0.75rem;">
                                            <i class="fas {{ $cat_icons[$cat] ?? 'fa-tag' }}"></i>
                                        </span>
                                        {{ $cat }}
                                    </span>
                                    <span class="badge" style="background:{{ $cat_colors[$cat] ?? '#6c757d' }}15;color:{{ $cat_colors[$cat] ?? '#6c757d' }};font-size:0.7rem;font-weight:700;">{{ $cnt }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:12px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:56px;height:56px;background:#10b98120;color:#10b981;font-size:1.5rem;">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h6 class="fw-bold" style="color:#065f46;">Stay Updated</h6>
                        <p class="small mb-3" style="color:#047857;">Subscribe to receive news directly in your inbox.</p>
                        <form method="POST" action="{{ route('newsletter.subscribe') }}" id="newsletterForm">
                            @csrf
                            <input type="hidden" name="name" value="News Subscriber">
                            <input type="email" name="email" class="form-control form-control-sm mb-2" placeholder="Your email address" required style="border-radius:8px;border:1.5px solid #bbf7d0;">
                            <button type="submit" class="btn btn-sm w-100 text-white" style="background:#10b981;border-radius:8px;font-weight:600;">Subscribe</button>
                            <small class="newsletter-msg mt-1" style="display:block;font-size:0.75rem;"></small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var nf = document.getElementById('newsletterForm');
    if (!nf) return;
    nf.addEventListener('submit', function(e) {
        e.preventDefault();
        var fd = new FormData(this);
        var msg = this.querySelector('.newsletter-msg');
        var btn = this.querySelector('button');
        msg.innerHTML = '<span style="color:#666;"><i class="fas fa-spinner fa-spin"></i> Subscribing...</span>';
        btn.disabled = true;
        fetch(this.getAttribute('action'), { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            msg.innerHTML = data.success
                ? '<span style="color:#28a745;"><i class="fas fa-check-circle"></i> ' + data.message + '</span>'
                : '<span style="color:#dc3545;"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>';
            if (data.success) nf.reset();
        })
        .catch(function() {
            msg.innerHTML = '<span style="color:#dc3545;"><i class="fas fa-exclamation-circle"></i> Network error.</span>';
        })
        .finally(function() {
            btn.disabled = false;
            setTimeout(function() { msg.innerHTML = ''; }, 6000);
        });
    });
});
</script>
@endsection
