@extends('layouts.app', ['title' => 'News & Updates', 'active_page' => 'media', 'meta_description' => 'Latest news and updates from the National Elections Commission of South Sudan.'])

@section('extra_head')
<style>
:root{--news-green:#0f5e3a;--news-gold:#d4a843;}
.news-hero{background:linear-gradient(135deg,var(--news-green) 0%,#083d25 100%);position:relative;overflow:hidden;}
.news-hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");pointer-events:none;}
.news-card{transition:transform 0.2s,box-shadow 0.2s;border-radius:12px;overflow:hidden;}
.news-card:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,0.1)!important;}
.news-card .card-img-top{height:180px;object-fit:cover;}
.news-card .news-cat{position:absolute;top:12px;left:12px;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;padding:4px 10px;border-radius:20px;z-index:2;}
.news-card .news-date{font-size:0.75rem;color:#94a3b8;}
.news-card .news-meta{font-size:0.72rem;color:#94a3b8;}
.news-card .news-meta i{width:14px;text-align:center;}
.news-card .news-title{font-size:1rem;font-weight:700;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.news-card .news-excerpt{font-size:0.82rem;color:#64748b;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.news-featured-card{min-height:380px;border-radius:14px;overflow:hidden;}
.news-featured-card .card-img-overlay{background:linear-gradient(transparent 30%,rgba(0,0,0,0.85));display:flex;flex-direction:column;justify-content:flex-end;}
.category-pill{font-size:0.75rem;padding:5px 14px;border-radius:20px;border:1.5px solid #e2e8f0;color:#64748b;font-weight:500;transition:all 0.15s;background:#fff;text-decoration:none;}
.category-pill:hover,.category-pill.active{background:var(--news-green);border-color:var(--news-green);color:#fff;}
.sidebar-card{border-radius:12px;border:1px solid #eef2f6;}
.sidebar-title{font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--news-green);padding-bottom:10px;border-bottom:2px solid rgba(15,94,58,0.1);margin-bottom:14px;}
.recent-item{transition:background 0.15s;border-radius:8px;padding:8px;}
.recent-item:hover{background:#f8fafc;}
.stat-slim.newsletter-card{background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;}
</style>
@endsection

@section('hero')
<section class="news-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 position-relative" style="z-index:1;">
                <h1 class="text-white fw-bold mb-2" style="font-size:2.2rem;">News & Updates</h1>
                <p class="text-white-50 mb-2" style="font-size:1.05rem;">Latest news, press releases, and updates from the National Elections Commission</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('media.news') }}" class="text-white-50">Media</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">News & Updates</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 position-relative" style="z-index:1;">
                <i class="fas fa-newspaper text-white-50" style="font-size:4rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
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

$newsItems = [];
if (isset($articles) && $articles->count()) {
    foreach ($articles as $item) {
        $newsItems[] = [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'excerpt' => $item->excerpt ?? '',
            'category' => $item->category ?? 'Elections',
            'image_url' => $item->image ?? '',
            'published_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
            'views' => $item->views ?? 0,
            'featured' => false,
        ];
    }
}
if (empty($newsItems)) {
    $newsItems = [
        ['id' => 1, 'title' => 'NEC Announces Voter Registration Dates for 2026 General Elections', 'slug' => 'voter-registration-2026', 'excerpt' => 'The National Elections Commission has officially announced the dates for the upcoming voter registration exercise ahead of the 2026 general elections, scheduled to begin on 1 June 2026.', 'category' => 'Elections', 'image_url' => '', 'published_at' => '2026-03-15 09:30:00', 'views' => 1247, 'featured' => true],
        ['id' => 2, 'title' => 'Commission Holds Training for Electoral Officers Across All States', 'slug' => 'electoral-officer-training', 'excerpt' => 'NEC conducted comprehensive training for electoral officers from all ten states to ensure efficient management of the electoral process.', 'category' => 'Training', 'image_url' => '', 'published_at' => '2026-03-10 14:15:00', 'views' => 892, 'featured' => true],
        ['id' => 3, 'title' => 'Political Parties Submit Candidate Lists for Parliamentary Elections', 'slug' => 'candidate-lists-submitted', 'excerpt' => 'Political parties have submitted their candidate lists for the upcoming parliamentary elections within the stipulated deadline.', 'category' => 'Political Parties', 'image_url' => '', 'published_at' => '2026-03-05 11:00:00', 'views' => 1563, 'featured' => false],
        ['id' => 4, 'title' => 'NEC Launches Mobile Voter Registration Units in Hard-to-Reach Areas', 'slug' => 'mobile-voter-registration', 'excerpt' => 'The Commission has deployed mobile registration units to ensure citizens in remote and hard-to-reach areas can register to vote.', 'category' => 'Voter Registration', 'image_url' => '', 'published_at' => '2026-02-28 08:45:00', 'views' => 2104, 'featured' => true],
        ['id' => 5, 'title' => 'Observer Accreditation Process Opens for Domestic and International Observers', 'slug' => 'observer-accreditation-open', 'excerpt' => 'The NEC has opened the accreditation process for domestic and international election observers for the 2026 elections.', 'category' => 'Observers', 'image_url' => '', 'published_at' => '2026-02-20 10:30:00', 'views' => 678, 'featured' => false],
        ['id' => 6, 'title' => 'NEC Signs Memorandum of Understanding with Security Agencies', 'slug' => 'mou-security-agencies', 'excerpt' => 'The Commission has signed an MOU with security agencies to ensure the safety and security of the electoral process throughout the 2026 election cycle.', 'category' => 'Security', 'image_url' => '', 'published_at' => '2026-02-15 16:20:00', 'views' => 945, 'featured' => false],
        ['id' => 7, 'title' => 'Digital Voter Register Now Available for Public Inspection', 'slug' => 'digital-voter-register', 'excerpt' => 'The Commission has published the provisional digital voter register for public inspection at all NEC offices and online.', 'category' => 'Voter Registration', 'image_url' => '', 'published_at' => '2026-02-10 13:00:00', 'views' => 1823, 'featured' => false],
        ['id' => 8, 'title' => 'NEC Chairperson Addresses Regional Election Management Forum', 'slug' => 'chairperson-addresses-forum', 'excerpt' => 'The NEC Chairperson delivered a keynote address at the Regional Election Management Bodies Forum held in Nairobi, Kenya.', 'category' => 'Elections', 'image_url' => '', 'published_at' => '2026-02-05 07:00:00', 'views' => 756, 'featured' => false],
        ['id' => 9, 'title' => 'Civic and Voter Education Campaign Launched Nationwide', 'slug' => 'civic-education-campaign', 'excerpt' => 'A nationwide civic and voter education campaign has been launched to increase public awareness about the electoral process.', 'category' => 'Elections', 'image_url' => '', 'published_at' => '2026-01-25 09:15:00', 'views' => 1120, 'featured' => false],
    ];
}

$featured = array_values(array_filter($newsItems, fn($n) => isset($n['featured']) && $n['featured']));
$regular = array_values(array_filter($newsItems, fn($n) => !isset($n['featured']) || !$n['featured']));

$selectedCat = request('category', '');
$page = max(1, intval(request('page', 1)));

function time_ago($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j', strtotime($datetime));
}
function reading_time($text) {
    $words = str_word_count(strip_tags($text));
    return max(1, ceil($words / 200));
}
@endphp

<section class="py-4">
    <div class="container">

        <!-- Stats Bar -->
        @php $total_views = array_sum(array_column($newsItems, 'views')); @endphp
        <div class="stat-grid mb-4" style="grid-template-columns:repeat(4,1fr);">
            <div class="stat-slim green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                    <div><div class="stat-value">{{ count($newsItems) }}</div><div class="stat-label">Articles</div></div>
                </div>
            </div>
            <div class="stat-slim blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-tags"></i></div>
                    <div><div class="stat-value">{{ count($categories) }}</div><div class="stat-label">Categories</div></div>
                </div>
            </div>
            <div class="stat-slim orange">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-eye"></i></div>
                    <div><div class="stat-value">{{ number_format($total_views) }}</div><div class="stat-label">Total Views</div></div>
                </div>
            </div>
            <div class="stat-slim purple">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div><div class="stat-value">{{ time_ago($newsItems[0]['published_at'] ?? date('Y-m-d H:i:s')) }}</div><div class="stat-label">Latest Update</div></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">

                <!-- Featured Hero -->
                @if (!empty($featured) && empty($selectedCat))
                @php $hero = $featured[0]; @endphp
                <a href="{{ route('news.article', $hero['slug']) }}" class="text-decoration-none d-block mb-4">
                    <div class="card border-0 shadow-sm news-featured-card position-relative" style="background:linear-gradient(135deg,#1a3c8f 0%,#0f5e3a 100%);">
                        <div class="card-body p-4 d-flex flex-column" style="min-height:320px;">
                            <div class="mt-auto">
                                <span class="badge mb-2" style="background:{{ $cat_colors[$hero['category']] ?? '#6c757d' }};font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;padding:4px 12px;border-radius:20px;">
                                    <i class="fas {{ $cat_icons[$hero['category']] ?? 'fa-tag' }} me-1"></i>{{ $hero['category'] }}
                                </span>
                                <h3 class="text-white fw-bold mb-2" style="font-size:1.5rem;line-height:1.3;">{{ $hero['title'] }}</h3>
                                <p class="text-white-50 mb-3" style="font-size:0.9rem;">{{ $hero['excerpt'] }}</p>
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <small class="text-white-50"><i class="far fa-calendar-alt me-1"></i>{{ date('d M Y', strtotime($hero['published_at'])) }}</small>
                                    <small class="text-white-50"><i class="far fa-clock me-1"></i>{{ date('H:i', strtotime($hero['published_at'])) }}</small>
                                    <small class="text-white-50"><i class="fas fa-eye me-1"></i>{{ number_format($hero['views']) }} views</small>
                                    <small class="text-white-50"><i class="fas fa-book-open me-1"></i>{{ reading_time($hero['excerpt']) }} min read</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                <!-- Category Pills -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="?category=" class="category-pill {{ empty($selectedCat) ? 'active' : '' }}">All</a>
                    @foreach ($categories as $cat)
                    <a href="?category={{ urlencode($cat) }}" class="category-pill {{ $selectedCat === $cat ? 'active' : '' }}">
                        <i class="fas {{ $cat_icons[$cat] ?? 'fa-tag' }} me-1"></i>{{ $cat }}
                        <span class="ms-1 fw-bold" style="opacity:0.7;">{{ count(array_filter($newsItems, fn($n) => $n['category'] === $cat)) }}</span>
                    </a>
                    @endforeach
                </div>

                <!-- News Grid -->
                <div class="row g-3">
                    @php
                    $display = $selectedCat ? array_values(array_filter($newsItems, fn($n) => $n['category'] === $selectedCat)) : $regular;
                    $itemsPerPage = 6;
                    $totalItems = count($display);
                    $totalPages = max(1, ceil($totalItems / $itemsPerPage));
                    $page = min($page, $totalPages);
                    $offset = ($page - 1) * $itemsPerPage;
                    $paginated = array_slice($display, $offset, $itemsPerPage);
                    @endphp
                    @if (empty($paginated))
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-radius:12px;">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
                                <h5 class="fw-bold">No News Found</h5>
                                <p class="text-muted">Check back later for updates in this category.</p>
                            </div>
                        </div>
                    </div>
                    @else
                        @foreach ($paginated as $item)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 news-card position-relative">
                                <div class="position-relative" style="height:160px;background:linear-gradient(135deg,{{ $cat_colors[$item['category']] ?? '#0f5e3a' }},rgba(0,0,0,0.6));overflow:hidden;">
                                    <i class="fas {{ $cat_icons[$item['category']] ?? 'fa-newspaper' }} text-white position-absolute top-50 start-50 translate-middle" style="font-size:2.5rem;opacity:0.2;"></i>
                                    <span class="news-cat" style="background:{{ $cat_colors[$item['category']] ?? '#6c757d' }};color:#fff;">
                                        <i class="fas {{ $cat_icons[$item['category']] ?? 'fa-tag' }} me-1"></i>{{ $item['category'] }}
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h6 class="news-title mb-2">{{ $item['title'] }}</h6>
                                    <p class="news-excerpt mb-3 flex-grow-1">{{ $item['excerpt'] }}</p>
                                    <div class="d-flex flex-wrap gap-2 news-meta mb-2">
                                        <span><i class="far fa-calendar-alt me-1"></i>{{ date('d M Y', strtotime($item['published_at'])) }}</span>
                                        <span><i class="far fa-clock me-1"></i>{{ date('H:i', strtotime($item['published_at'])) }}</span>
                                        <span><i class="fas fa-eye me-1"></i>{{ number_format($item['views']) }}</span>
                                        <span><i class="fas fa-book-open me-1"></i>{{ reading_time($item['excerpt']) }} min</span>
                                    </div>
                                    <a href="{{ route('news.article', $item['slug']) }}" class="btn btn-sm btn-outline-success mt-1" style="border-radius:8px;font-size:0.8rem;">
                                        Read Full Article <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                @if ($totalPages > 1)
                @php
                $qs = $selectedCat ? 'category=' . urlencode($selectedCat) . '&' : '';
                @endphp
                <nav class="mt-4">
                    <ul class="pagination pagination-sm justify-content-center">
                        <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="?{{ $qs }}page={{ $page - 1 }}" style="border-radius:8px 0 0 8px;"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $i === $page ? 'active' : '' }}">
                            <a class="page-link" href="?{{ $qs }}page={{ $i }}">{{ $i }}</a>
                        </li>
                        @endfor
                        <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                            <a class="page-link" href="?{{ $qs }}page={{ $page + 1 }}" style="border-radius:0 8px 8px 0;"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card sidebar-card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="sidebar-title"><i class="fas fa-tags me-2"></i>Categories</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach ($categories as $cat)
                                @php
                                $cnt = count(array_filter($newsItems, fn($n) => $n['category'] === $cat));
                                $pct = $cnt > 0 ? round($cnt / count($newsItems) * 100) : 0;
                                @endphp
                            <li class="mb-2">
                                <a href="?category={{ urlencode($cat) }}" class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded" style="transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                    <span>
                                        <span class="d-inline-flex align-items-center justify-content-center me-2" style="width:28px;height:28px;border-radius:8px;background:{{ $cat_colors[$cat] }}15;color:{{ $cat_colors[$cat] }};font-size:0.75rem;">
                                            <i class="fas {{ $cat_icons[$cat] ?? 'fa-tag' }}"></i>
                                        </span>
                                        <span style="font-size:0.85rem;font-weight:500;color:#1e293b;">{{ $cat }}</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-2">
                                        <span style="width:40px;height:4px;border-radius:2px;background:{{ $cat_colors[$cat] }}25;overflow:hidden;display:inline-block;">
                                            <span style="display:block;height:100%;width:{{ $pct }}%;background:{{ $cat_colors[$cat] }};border-radius:2px;"></span>
                                        </span>
                                        <span class="badge" style="background:{{ $cat_colors[$cat] }}15;color:{{ $cat_colors[$cat] }};font-size:0.7rem;font-weight:700;">{{ $cnt }}</span>
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card sidebar-card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="sidebar-title"><i class="fas fa-fire me-2"></i>Popular Posts</h6>
                        @php
                        $popular = $newsItems;
                        usort($popular, fn($a, $b) => $b['views'] - $a['views']);
                        $popularSlice = array_slice($popular, 0, 4);
                        @endphp
                        @foreach ($popularSlice as $item)
                        <a href="{{ route('news.article', $item['slug']) }}" class="text-decoration-none recent-item d-flex gap-3 align-items-center mb-2">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:36px;height:36px;background:{{ $cat_colors[$item['category']] ?? '#0f5e3a' }}15;color:{{ $cat_colors[$item['category']] ?? '#0f5e3a' }};font-size:0.8rem;">
                                <i class="fas {{ $cat_icons[$item['category']] ?? 'fa-newspaper' }}"></i>
                            </div>
                            <div style="min-width:0;">
                                <div style="font-size:0.82rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['title'] }}</div>
                                <div style="font-size:0.7rem;color:#94a3b8;">
                                    <i class="fas fa-eye me-1"></i>{{ number_format($item['views']) }} views
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="card sidebar-card border-0 shadow-sm" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:56px;height:56px;background:#10b98120;color:#10b981;font-size:1.5rem;">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h6 class="fw-bold" style="color:#065f46;">Stay Updated</h6>
                        <p class="small mb-3" style="color:#047857;">Subscribe to receive news directly in your inbox.</p>
                        <form method="POST" action="{{ route('newsletter.subscribe') }}" id="newsletterSidebarForm">
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
    var nf = document.getElementById('newsletterSidebarForm');
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
