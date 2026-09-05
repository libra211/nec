@extends('layouts.app', ['title' => 'Boundary Commission - NEC South Sudan', 'active_page' => 'about', 'meta_description' => 'The Electoral Boundary Commission ensures fair representation through scientific delimitation of electoral constituencies across South Sudan.'])

@section('extra_head')
<style>
.boundary-stat-tile{border-radius:14px;background:#fff;border:1px solid rgba(0,0,0,0.05);box-shadow:var(--nec-shadow-xs);padding:16px 18px;}
.boundary-stat-tile .stat-ico{width:46px;height:46px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:1.05rem;flex-shrink:0;}
.boundary-stat-tile .num{font-size:1.5rem;font-weight:800;line-height:1;color:var(--nec-black);}
.boundary-stat-tile .lbl{font-size:0.68rem;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;font-weight:600;}
.criterion-card{transition:transform 0.2s,box-shadow 0.2s;border-radius:14px;}
.criterion-card:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,0.08);}
.table-success th{font-size:0.75rem;text-transform:uppercase;letter-spacing:0.6px;font-weight:700!important;color:#14532d!important;border-bottom:2px solid rgba(46,139,87,0.25)!important;}
</style>
@endsection

@section('hero')
<section class="page-header" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('about.mandate') }}" class="text-white opacity-75">About NEC</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Boundary Commission</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold text-white mb-2">Boundary Commission</h1>
                <p class="text-white opacity-90 lead mb-0">Ensuring fair representation through scientific delimitation of electoral constituencies.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-block text-center px-4 py-2 rounded-3" style="background:rgba(255,255,255,0.15);">
                    <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;font-size:0.65rem;">Coverage</div>
                    <div class="text-white fw-bold" style="font-size:1.15rem;">{{ $totalStates }} States &middot; {{ $totalConstituencies }} Constituencies</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
    $sidebar_section = 'About NEC';
    $sidebar_links = [
        ['url' => route('about.mandate'), 'label' => 'Our Mandate', 'icon' => 'fas fa-gavel'],
        ['url' => route('about.leadership'), 'label' => 'Leadership', 'icon' => 'fas fa-users'],
        ['url' => route('about.commissioners'), 'label' => 'Commissioners', 'icon' => 'fas fa-user-tie'],
        ['url' => route('about.state-committees'), 'label' => 'State Committees', 'icon' => 'fas fa-map-marker-alt'],
        ['url' => route('about.departments'), 'label' => 'Departments', 'icon' => 'fas fa-building'],
        ['url' => route('about.history'), 'label' => 'History', 'icon' => 'fas fa-history'],
        ['url' => route('about.legal-framework'), 'label' => 'Legal Framework', 'icon' => 'fas fa-file-contract'],
        ['url' => route('about.boundary-commission'), 'label' => 'Boundary Commission', 'icon' => 'fas fa-draw-polygon', 'active' => true],
    ];

    $totalConstituencies = $totalConstituencies ?? 0;
    $totalStates = $totalStates ?? 0;
@endphp

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3" style="color:var(--nec-black);">Electoral Boundary Commission</h2>
                    <p class="text-muted">The Electoral Boundary Commission is a specialized body within the National Elections Commission responsible for the delimitation of electoral constituencies across South Sudan. The Commission ensures that each constituency contains approximately equal populations, providing fair and equitable representation in the National Assembly.</p>

                    <div class="row g-3 my-4">
                        <div class="col-md-4">
                            <div class="boundary-stat-tile d-flex align-items-center gap-3 h-100">
                                <span class="stat-ico" style="background:linear-gradient(135deg,#2E8B57,#1f6b41);"><i class="fas fa-location-dot"></i></span>
                                <div>
                                    <div class="num">{{ number_format($totalConstituencies) }}</div>
                                    <div class="lbl">Constituencies</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="boundary-stat-tile d-flex align-items-center gap-3 h-100">
                                <span class="stat-ico" style="background:linear-gradient(135deg,#1a3c8f,#142c66);"><i class="fas fa-map"></i></span>
                                <div>
                                    <div class="num">{{ number_format($totalStates) }}</div>
                                    <div class="lbl">Electoral Areas</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="boundary-stat-tile d-flex align-items-center gap-3 h-100">
                                <span class="stat-ico" style="background:linear-gradient(135deg,#D4AF37,#b8912a);"><i class="fas fa-users"></i></span>
                                <div>
                                    <div class="num">~80K</div>
                                    <div class="lbl">Avg Voters / Constituency</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3" style="color:var(--nec-black);">Delimitation Criteria</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="criterion-card card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1" style="color:var(--nec-green);"><i class="fas fa-users"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Population Equality</h6>
                                            <small class="text-muted">Constituencies shall have approximately equal numbers of inhabitants, based on the most recent national census.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="criterion-card card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1" style="color:#1a3c8f;"><i class="fas fa-globe-africa"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Geographical Contiguity</h6>
                                            <small class="text-muted">Constituencies shall be contiguous land areas, respecting natural geographical boundaries.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="criterion-card card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1" style="color:var(--nec-gold);"><i class="fas fa-map-signs"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Administrative Boundaries</h6>
                                            <small class="text-muted">Constituency boundaries shall follow county and state administrative borders where possible.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="criterion-card card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1" style="color:#b91c1c;"><i class="fas fa-scale-balanced"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Community of Interest</h6>
                                            <small class="text-muted">Consideration shall be given to ethnic, cultural, and economic communities of interest.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4" style="border-radius:14px;overflow:hidden;">
                <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 border-0">
                    <h5 class="mb-0 fw-bold" style="color:var(--nec-green);">
                        <i class="fas fa-table me-2"></i>State Constituency Breakdown
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="max-width:220px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:0.75rem;"></i></span>
                            <input type="text" class="form-control border-start-0" id="boundarySearch" placeholder="Search states..." style="font-size:0.82rem;">
                        </div>
                        <button class="btn btn-sm btn-outline-success" id="boundaryExportCsv" title="Export as CSV" style="font-size:0.78rem;">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="boundaryTable">
                            <thead class="table-success">
                                <tr>
                                    <th scope="col" class="ps-4" style="width:5%;">#</th>
                                    <th scope="col" style="width:34%;">State / Area</th>
                                    <th scope="col" class="text-center" style="width:14%;">Constituencies</th>
                                    <th scope="col" class="text-end" style="width:20%;">Est. Population</th>
                                    <th scope="col" class="text-end pe-4" style="width:22%;">Avg Voters / Constituency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($breakdownRows) > 0)
                                    @php $i = 1; @endphp
                                    @foreach($breakdownRows as $br)
                                        @php
                                            $isSpecial = str_contains($br->state_name, 'Abyei') || str_contains($br->state_name, 'Pibor') || str_contains($br->state_name, 'Ruweng');
                                        @endphp
                                        <tr @if($isSpecial) class="table-secondary" @endif>
                                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size:0.82rem;">{{ $i++ }}</td>
                                            <td class="fw-semibold" style="font-size:0.88rem;">{{ $br->state_name }}
                                                @if($isSpecial)<span class="badge rounded-pill ms-1" style="background:rgba(100,116,139,0.15);color:#475569;font-size:0.62rem;">Administrative Area</span>@endif
                                            </td>
                                            <td class="text-center"><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.72rem;font-weight:600;">{{ (int)$br->constituencies }}</span></td>
                                            <td class="text-end" style="font-size:0.85rem;">{{ number_format((int)$br->estimated_population) }}</td>
                                            <td class="text-end pe-4" style="font-size:0.85rem;">~{{ number_format((int)$br->avg_voters) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="text-center text-muted py-4">No constituency breakdown data available.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px;position:sticky;top:100px;">
                <div class="card-body p-0">
                    <div class="px-3 pt-3 pb-2" style="background:var(--nec-green);color:#fff;border-radius:12px 12px 0 0;">
                        <small class="fw-bold text-uppercase" style="font-size:0.65rem;letter-spacing:1px;opacity:0.8;">In this section</small>
                        <h6 class="fw-bold mb-0" style="color:#fff;font-size:0.95rem;">{{ $sidebar_section }}</h6>
                    </div>
                    <ul class="sidebar-nav p-2">
                        @foreach($sidebar_links as $sl)
                        <li class="sidebar-nav-item">
                            <a href="{{ $sl['url'] }}" class="sidebar-nav-link {{ ($sl['active'] ?? false) ? 'active' : '' }}">
                                <i class="{{ $sl['icon'] ?? 'fas fa-link' }}"></i>
                                {{ $sl['label'] }}
                                @isset($sl['badge'])
                                <span class="badge bg-{{ $sl['badge_color'] ?? 'success' }} ms-auto">{{ $sl['badge'] }}</span>
                                @endisset
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-draw-polygon fa-3x mb-3" style="color:var(--nec-blue);"></i>
                    <h6 class="fw-bold">Constituency Mapping</h6>
                    <p class="small text-muted">View detailed maps and information about all {{ number_format($totalConstituencies ?: 102) }} constituencies.</p>
                    <a href="{{ route('constituencies.index') }}" class="btn btn-outline-primary btn-sm w-100" style="border-radius:8px;">Explore Constituencies</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-history me-2 text-muted"></i>Review Timeline</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                            <span>Current Review</span>
                            <span class="badge bg-success">Ongoing</span>
                        </li>
                        <li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                            <span>2021 Delimitation</span>
                            <span class="badge bg-secondary">Completed</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>2015 Initial</span>
                            <span class="badge bg-secondary">Completed</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
(function($) {
    function initSearch() {
        var $input = $('#boundarySearch');
        var $table = $('#boundaryTable');
        if (!$input.length || !$table.length) return;
        $input.on('keyup', function() {
            var q = $(this).val().toLowerCase();
            $table.find('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(q) > -1);
            });
        });
    }
    function initExportCsv() {
        var $btn = $('#boundaryExportCsv');
        if (!$btn.length) return;
        $btn.on('click', function(e) {
            e.preventDefault();
            var rows = [];
            $('#boundaryTable thead tr').each(function() {
                var cols = [];
                $(this).find('th').each(function() { cols.push('"' + $(this).text().trim() + '"'); });
                rows.push(cols.join(','));
            });
            $('#boundaryTable tbody tr').each(function() {
                var cols = [];
                $(this).find('td').each(function() { cols.push('"' + $(this).text().trim() + '"'); });
                rows.push(cols.join(','));
            });
            var blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'NEC_State_Constituency_Breakdown.csv';
            link.click();
        });
    }
    $(document).ready(function() {
        initSearch();
        initExportCsv();
    });
})(jQuery);
</script>
@endsection