@extends('layouts.app', ['title' => 'Constituencies of South Sudan', 'active_page' => 'constituencies'])

@php
$color_map = [
    'Central Equatoria' => '#0d6efd',
    'Eastern Equatoria' => '#198754',
    'Western Equatoria' => '#20c997',
    'Jonglei' => '#0dcaf0',
    'Greater Pibor' => '#0dcaf0',
    'Unity' => '#ffc107',
    'Ruweng' => '#ffc107',
    'Upper Nile' => '#fd7e14',
    'Lakes' => '#6f42c1',
    'Northern Bahr el Ghazal' => '#dc3545',
    'Western Bahr el Ghazal' => '#e83e8c',
    'Warrap' => '#6610f2',
    'Abyei' => '#6610f2',
];

$logo_by_code = [
    'CES' => 'Flag_of_Central_Equatoria (1).png',
    'EES' => 'Flag_of_Eastern_Equatoria.png',
    'WES' => 'Flag_of_Western_Equatoria (1).png',
    'JON' => 'Flag_of_Jonglei (1).png',
    'PIB' => 'Flag_of_Jonglei (1).png',
    'UNI' => 'Flag_of_Unity_State (1).png',
    'RWG' => 'Flag_of_Unity_State (1).png',
    'UPN' => 'Flag_of_Upper_Nile_State.png',
    'LAK' => 'Flag_of_Lakes_State (1).png',
    'NBG' => 'Flag_of_Northern_Bahr_el_Ghazal (1).png',
    'WBG' => 'Flag_of_Western_Bahr_el_Ghazal (1).png',
    'WRR' => 'Flag_of_Warrap_State (2).png',
];

$state_meta = [];
foreach ($states as $st) {
    $state_meta[$st->name] = [
        'code' => $st->code,
        'color' => $color_map[$st->name] ?? '#6c757d',
        'logo' => $logo_by_code[$st->code] ?? null,
    ];
}

$logo_base = asset('assets/images/logos/');
$totalStates = $states->where('type', 'state')->count();
$totalAdminAreas = $states->where('type', 'admin_area')->count();

$stateCenters = [
    'Central Equatoria' => [4.85, 31.6], 'Eastern Equatoria' => [4.5, 33.0],
    'Western Equatoria' => [5.0, 29.5], 'Jonglei' => [7.0, 32.5],
    'Unity' => [9.0, 29.8], 'Upper Nile' => [10.0, 32.8],
    'Lakes' => [6.5, 29.5], 'Northern Bahr el Ghazal' => [8.7, 27.2],
    'Western Bahr el Ghazal' => [7.5, 26.0], 'Warrap' => [8.5, 28.0],
];
$stateBounds = [
    'Central Equatoria' => [[3.8, 30.5], [5.3, 32.5]],
    'Eastern Equatoria' => [[4.0, 32.5], [5.0, 34.0]],
    'Western Equatoria' => [[4.5, 28.5], [5.5, 30.5]],
    'Jonglei' => [[6.0, 31.0], [8.5, 34.5]],
    'Unity' => [[8.5, 29.0], [9.8, 30.5]],
    'Upper Nile' => [[9.0, 31.5], [11.0, 34.5]],
    'Lakes' => [[5.8, 28.5], [7.2, 30.5]],
    'Northern Bahr el Ghazal' => [[8.0, 26.0], [9.5, 28.0]],
    'Western Bahr el Ghazal' => [[7.0, 24.5], [8.5, 27.0]],
    'Warrap' => [[8.0, 27.0], [10.0, 29.5]],
];
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Constituencies of South Sudan</h1>
                <p class="text-white-50 mb-2">{{ $totalConstituencies }} constituencies across {{ $statesWithData }} states</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Constituencies</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-map-marked-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4 bg-white">
    <div class="container">
        <div class="row g-4 mb-4">
            @foreach($byState as $s)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:{{ $state_meta[$s['name']]['color'] ?? '#6c757d' }}20;flex-shrink:0;overflow:hidden;">
                            @if(!empty($state_meta[$s['name']]['logo']))
                            <img src="{{ $logo_base . $state_meta[$s['name']]['logo'] }}" alt="{{ $s['name'] }}" style="width:44px;height:44px;object-fit:cover;">
                            @else
                            <i class="fas fa-flag" style="color:{{ $state_meta[$s['name']]['color'] ?? '#6c757d' }};"></i>
                            @endif
                        </div>
                        <div>
                            <small class="text-muted d-block lh-1">{{ $s['name'] }}</small>
                            <span class="fw-bold fs-5">{{ $s['constituencies'] }}</span>
                            <small class="text-muted"> constituencies</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            <select id="stateFilter" class="form-select form-select-sm" style="width:auto;min-width:200px;">
                                <option value="">All States / Admin Areas</option>
                                @foreach($byState as $s)
                                <option value="{{ $s['name'] }}">{{ $s['name'] }} ({{ $s['constituencies'] }})</option>
                                @endforeach
                            </select>
                            <input type="text" id="conSearch" class="form-control form-control-sm" placeholder="Search constituency..." style="width:200px;">
                        </div>
                        <small class="text-muted" id="resultCount">{{ $totalConstituencies }} constituencies</small>
                    </div>
                    <div class="card-body p-0" style="max-height:600px;overflow-y:auto;">
                        <table class="table mb-0" id="constituencyTable" style="font-size:0.9rem;">
                            <thead class="sticky-top" style="background:#fff;border-bottom:2px solid #e2e8f0;">
                                <tr>
                                    <th class="sort-col" data-sort="idx" style="width:45px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;">#</th>
                                    <th class="sort-col" data-sort="name" style="padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;">Constituency <i class="fas fa-sort ms-1" style="font-size:0.6rem;"></i></th>
                                    <th class="sort-col" data-sort="county" style="padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;">County / Main Area</th>
                                    <th class="sort-col" data-sort="state" style="padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;">State</th>
                                    <th class="sort-col text-center" data-sort="ps" style="width:70px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;" title="Polling Stations"><i class="fas fa-map-marker-alt me-1"></i>PS</th>
                                    <th class="sort-col text-center" data-sort="cand" style="width:70px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;" title="Candidates"><i class="fas fa-user-tie me-1"></i>Cand</th>
                                    <th class="sort-col text-center" data-sort="voters" style="width:90px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;cursor:pointer;" title="Registered Voters"><i class="fas fa-users me-1"></i>Voters</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($constituencies as $idx => $c)
                                @php
                                $meta = $state_meta[$c->state] ?? null;
                                $sc = $meta['color'] ?? '#6c757d';
                                @endphp
                                <tr data-state="{{ $c->state }}" data-name="{{ strtolower($c->name) }}" data-code="{{ $c->code }}" data-county="{{ strtolower($c->county ?? '') }}" data-ps="{{ $c->polling_stations_count }}" data-cand="{{ $c->candidates_count }}" data-voters="{{ $c->registered_voters }}" style="border-left:3px solid {{ $sc }};transition:background 0.15s;" class="constituency-row">
                                    <td class="align-middle text-muted" style="padding:10px 8px;font-size:0.8rem;">{{ $idx + 1 }}</td>
                                    <td class="align-middle fw-semibold" style="padding:10px 8px;">
                                        <span class="badge me-1" style="background:{{ $sc }};font-size:0.6rem;letter-spacing:0.3px;font-weight:600;">{{ $c->code }}</span>
                                        {{ $c->name }}
                                    </td>
                                    <td class="align-middle text-muted" style="padding:10px 8px;font-size:0.85rem;">{{ $c->county }}</td>
                                    <td class="align-middle" style="padding:10px 8px;">
                                        @if(!empty($meta['logo']))
                                            <img src="{{ $logo_base . $meta['logo'] }}" alt="" style="width:18px;height:18px;object-fit:cover;border-radius:50%;margin-right:8px;vertical-align:middle;">
                                        @endif
                                        <span style="color:{{ $sc }};font-weight:500;font-size:0.85rem;">{{ $c->state }}</span>
                                    </td>
                                    <td class="align-middle text-center" style="padding:10px 8px;">
                                        <span class="badge rounded-pill {{ $c->polling_stations_count ? 'bg-success-subtle text-success' : 'bg-light text-muted' }}" style="font-size:0.75rem;">{{ $c->polling_stations_count }}</span>
                                    </td>
                                    <td class="align-middle text-center" style="padding:10px 8px;">
                                        <span class="badge rounded-pill {{ $c->candidates_count ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }}" style="font-size:0.75rem;">{{ $c->candidates_count }}</span>
                                    </td>
                                    <td class="align-middle text-center text-muted" style="padding:10px 8px;font-size:0.8rem;">{{ $c->registered_voters ? number_format($c->registered_voters) : '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0" style="color:var(--nec-black);"><i class="fas fa-map me-2" style="color:var(--nec-green);"></i>Electoral Map</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="ssdMap" style="height:400px;width:100%;"></div>
                    </div>
                    <div class="card-footer bg-white py-2 text-center">
                        <small class="text-muted">Click a marker to see constituency details</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0" style="color:var(--nec-black);"><i class="fas fa-info-circle me-2" style="color:var(--nec-gold);"></i>Constituency Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="constituencyDetail">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-mouse-pointer fs-2 mb-2 d-block"></i>
                                <small>Click a row above or a map marker to view details</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0" style="color:var(--nec-black);"><i class="fas fa-info-circle me-2" style="color:var(--nec-green);"></i>About Electoral Constituencies</h5>
                    </div>
                    <div class="card-body p-4">
                        <p>South Sudan is divided into <strong>{{ $totalConstituencies }} constituencies</strong> across <strong>{{ $totalStates }} states</strong> and <strong>{{ $totalAdminAreas }} administrative areas</strong>. Each constituency elects one representative to the National Legislative Assembly.</p>
                        <p class="mb-0">The constituencies are delimited by the National Elections Commission based on population distribution, geographical size, and administrative boundaries as defined by the NEC Boundary Delimitation Committee.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0" style="color:var(--nec-black);"><i class="fas fa-database me-2" style="color:var(--nec-gold);"></i>Quick Statistics</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:var(--nec-green);">{{ $totalConstituencies }}</span>
                                    <small class="text-muted">Constituencies</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:var(--nec-gold);">{{ $totalStates }}</span>
                                    <small class="text-muted">States</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#0d6efd;">{{ $totalAdminAreas }}</span>
                                    <small class="text-muted">Admin Areas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#198754;">{{ $totalPollingStations }}</span>
                                    <small class="text-muted">Polling Stations</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#dc3545;">{{ $totalCandidates }}</span>
                                    <small class="text-muted">Candidates</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#6f42c1;">{{ number_format($totalVoters) }}</span>
                                    <small class="text-muted">Registered Voters</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
#constituencyTable tbody tr{cursor:pointer;transition:background 0.15s;}
#constituencyTable tbody tr:hover{background:#f8fafc !important;}
#constituencyTable tbody tr:nth-child(even){background:#fcfcfd;}
#constituencyTable tbody tr:nth-child(even):hover{background:#f1f5f9 !important;}
#stateFilter,#conSearch{border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;padding:6px 12px;}
#stateFilter:focus,#conSearch:focus{border-color:var(--nec-green,#10b981);box-shadow:0 0 0 3px rgba(16,185,129,0.1);outline:none;}
.sort-col .fa-sort{opacity:0.4;}
.sort-col.active .fa-sort{opacity:1;color:var(--nec-green,#10b981);}
</style>
@endsection

@section('extra_scripts')
<script>
var stateData = @json($state_meta);

$(document).ready(function() {
    var sortKey = 'state';
    var sortDir = 1;

    function applyFilters() {
        var q = $('#conSearch').val().toLowerCase();
        var state = $('#stateFilter').val();
        $('#constituencyTable tbody tr').each(function() {
            var $tr = $(this);
            var name = $tr.data('name');
            var code = $tr.data('code').toLowerCase();
            var county = $tr.data('county');
            var matches = name.indexOf(q) > -1 || code.indexOf(q) > -1 || county.indexOf(q) > -1;
            var stateMatch = !state || $tr.data('state') === state;
            $tr.toggle(matches && stateMatch);
        });
        updateCount();
    }

    function sortRows() {
        var $rows = $('#constituencyTable tbody tr').get();
        $rows.sort(function(a, b) {
            var $a = $(a), $b = $(b);
            var va, vb;
            if (sortKey === 'ps' || sortKey === 'cand' || sortKey === 'voters') {
                va = +$a.data(sortKey); vb = +$b.data(sortKey);
                return (va - vb) * sortDir;
            }
            if (sortKey === 'idx') {
                va = +$a.data('code').replace(/\D/g, '') || 0;
                vb = +$b.data('code').replace(/\D/g, '') || 0;
                return (va - vb) * sortDir;
            }
            va = ('' + $a.data(sortKey)).toLowerCase();
            vb = ('' + $b.data(sortKey)).toLowerCase();
            return va.localeCompare(vb) * sortDir;
        });
        $.each($rows, function(i, row) { $('#constituencyTable tbody').append(row); });
    }

    function updateCount() {
        var vis = $('#constituencyTable tbody tr:visible').length;
        $('#resultCount').text(vis + ' constituency' + (vis !== 1 ? 'ies' : 'y'));
    }

    $('#stateFilter').change(applyFilters);
    $('#conSearch').on('keyup', applyFilters);

    $('#constituencyTable thead th.sort-col').on('click', function() {
        var k = $(this).data('sort');
        if (k === sortKey) { sortDir = -sortDir; } else { sortKey = k; sortDir = 1; }
        $('#constituencyTable thead th.sort-col').removeClass('active');
        $(this).addClass('active');
        sortRows();
        applyFilters();
    });

    $('#constituencyTable tbody').on('click', 'tr', function() {
        var $tr = $(this);
        var code = $tr.data('code');
        var name = $tr.find('td:eq(1)').contents().last().text().trim();
        var county = $tr.find('td:eq(2)').text().trim();
        var state = $tr.data('state');
        var ps = +$tr.data('ps');
        var cand = +$tr.data('cand');
        var voters = +$tr.data('voters');
        var meta = stateData[state] || {};
        var detailHtml = '<div class="detail-card">'
            + '<h6 class="fw-bold mb-0" style="color:var(--nec-green);"><span class="badge me-1" style="background:' + (meta.color || '#10b981') + ';">' + code + '</span> ' + name + '</h6>'
            + '<hr class="my-2">'
            + '<div class="small">'
            + '<div class="mb-2"><strong>County:</strong> ' + county + '</div>'
            + '<div class="mb-2"><strong>State/Area:</strong> ' + state + '</div>'
            + '<div class="mb-2"><strong>Constituency Code:</strong> ' + code + '</div>'
            + '<div class="mb-2"><strong>Polling Stations:</strong> ' + ps + '</div>'
            + '<div class="mb-2"><strong>Candidates:</strong> ' + cand + '</div>'
            + '<div class="mb-0"><strong>Registered Voters:</strong> ' + (voters ? voters.toLocaleString() : '—') + '</div>'
            + '</div></div>';
        $('#constituencyDetail').html(detailHtml).addClass('highlight-flash');
        setTimeout(function() { $('#constituencyDetail').removeClass('highlight-flash'); }, 1000);
    });

    sortRows();
    applyFilters();
});

var ssdMap = L.map('ssdMap', {
    center: [7.5, 30.0],
    zoom: 6,
    scrollWheelZoom: true,
    zoomControl: true
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 18
}).addTo(ssdMap);

var stateCenters = {!! json_encode($stateCenters) !!};
var stateBounds = {!! json_encode($stateBounds) !!};

Object.keys(stateCenters).forEach(function(state) {
    var c = stateCenters[state];
    var meta = stateData[state] || {};
    var color = meta.color || '#6c757d';
    var bounds = stateBounds[state];
    var marker = L.circleMarker([c[0], c[1]], {
        radius: 12,
        fillColor: color,
        color: '#fff',
        weight: 2,
        opacity: 1,
        fillOpacity: 0.7
    }).addTo(ssdMap);

    marker.bindTooltip(state, { direction: 'top', offset: L.point(0, -10) });

    if (bounds) {
        L.rectangle(bounds, {
            color: color,
            weight: 2,
            fillColor: color,
            fillOpacity: 0.08,
            dashArray: '5, 5'
        }).addTo(ssdMap);
    }

    marker.on('click', function() {
        $('#stateFilter').val(state).trigger('change');
        var count = $('#constituencyTable tbody tr[data-state="' + state + '"]:visible').length;
        $('#constituencyDetail').html(
            '<div class="detail-card">'
            + '<h6 class="fw-bold mb-0" style="color:' + color + ';">' + state + '</h6>'
            + '<hr class="my-2">'
            + '<div class="small">'
            + '<div class="mb-2"><strong>Constituencies:</strong> ' + count + '</div>'
            + '<div class="mb-0"><strong>Region:</strong> ' + (state.includes('Equatoria') ? 'Equatoria' : state.includes('Bahr') ? 'Bahr el Ghazal' : 'Greater Upper Nile') + '</div>'
            + '</div></div>'
        );
    });
});
</script>
@endsection
