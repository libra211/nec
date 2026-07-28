@extends('layouts.app', ['title' => 'Constituencies of South Sudan', 'active_page' => 'constituencies'])

@php
$state_map = [
    'CES' => 'Central Equatoria',
    'EES' => 'Eastern Equatoria',
    'WES' => 'Western Equatoria',
    'JON' => 'Jonglei',
    'PIB' => 'Greater Pibor',
    'UNI' => 'Unity',
    'RWG' => 'Ruweng',
    'UPN' => 'Upper Nile',
    'LAK' => 'Lakes',
    'NBG' => 'Northern Bahr el Ghazal',
    'WBG' => 'Western Bahr el Ghazal',
    'WRR' => 'Warrap',
];

$color_map = [
    'CES' => '#0d6efd',
    'EES' => '#198754',
    'WES' => '#20c997',
    'JON' => '#0dcaf0',
    'PIB' => '#0dcaf0',
    'UNI' => '#ffc107',
    'RWG' => '#ffc107',
    'UPN' => '#fd7e14',
    'LAK' => '#6f42c1',
    'NBG' => '#dc3545',
    'WBG' => '#e83e8c',
    'WRR' => '#6610f2',
];

$logo_map = [
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

$display_groups = [
    'Central Equatoria' => ['CES'],
    'Eastern Equatoria' => ['EES'],
    'Western Equatoria' => ['WES'],
    'Jonglei + GPAA' => ['JON', 'PIB'],
    'Unity + Ruweng' => ['UNI', 'RWG'],
    'Upper Nile' => ['UPN'],
    'Lakes' => ['LAK'],
    'Northern Bahr el Ghazal' => ['NBG'],
    'Western Bahr el Ghazal' => ['WBG'],
    'Warrap + Abyei' => ['WRR'],
];

$state_colors = [];
$state_logos = [];
foreach ($display_groups as $dname => $codes) {
    $color = $color_map[$codes[0]];
    $logo = $logo_map[$codes[0]];
    $state_colors[$dname] = $color;
    $state_logos[$dname] = $logo;
}

$constituency_rows = [];
$js_data = [];
$all_records = [];

try {
    $all_records = \App\Models\Constituency::where('status', 'active')->orderBy('state')->orderBy('id')->get()->toArray();

    foreach ($all_records as $r) {
        $code = $r['state'];
        $dname = '';
        foreach ($display_groups as $dn => $codes) {
            if (in_array($code, $codes)) {
                $dname = $dn;
                break;
            }
        }
        $constituency_rows[] = ['record' => $r, 'group' => $dname];
        $js_data[$r['code']] = [
            'name' => $r['name'],
            'county' => $r['county'],
            'state' => $dname,
            'description' => $r['description'],
        ];
    }
} catch (\Exception $e) {}

$state_summary = [];
foreach ($display_groups as $dname => $codes) {
    $count = 0;
    foreach ($all_records as $r) {
        if (in_array($r['state'], $codes)) $count++;
    }
    $state_summary[] = [$dname, $count, $state_logos[$dname], $state_colors[$dname]];
}
$total_constituencies = count($all_records);
$unique_states_used = [];
foreach ($all_records as $r) {
    foreach ($display_groups as $dn => $codes) {
        if (in_array($r['state'], $codes)) {
            $unique_states_used[$dn] = true;
        }
    }
}
$num_groups = count($unique_states_used);
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Constituencies of South Sudan</h1>
                <p class="text-white-50 mb-2">{{ $total_constituencies }} constituencies across {{ $num_groups }} states and administrative areas</p>
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
            @foreach($state_summary as $s)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:10px;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba({{ hexdec(substr($s[3],1,2)) }},{{ hexdec(substr($s[3],3,2)) }},{{ hexdec(substr($s[3],5,2)) }},0.1);flex-shrink:0;overflow:hidden;">
                            <img src="{{ asset('assets/images/logos/' . $s[2]) }}" alt="{{ $s[0] }}" style="width:44px;height:44px;object-fit:cover;">
                        </div>
                        <div>
                            <small class="text-muted d-block lh-1">{{ $s[0] }}</small>
                            <span class="fw-bold fs-5">{{ $s[1] }}</span>
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
                                @foreach($state_summary as $s)
                                <option value="{{ $s[0] }}">{{ $s[0] }} ({{ $s[1] }})</option>
                                @endforeach
                            </select>
                            <input type="text" id="conSearch" class="form-control form-control-sm" placeholder="Search constituency..." style="width:200px;">
                        </div>
                        <small class="text-muted" id="resultCount">{{ $total_constituencies }} constituencies</small>
                    </div>
                    <div class="card-body p-0" style="max-height:600px;overflow-y:auto;">
                        <table class="table mb-0" id="constituencyTable" style="font-size:0.9rem;">
                            <thead class="sticky-top" style="background:#fff;border-bottom:2px solid #e2e8f0;">
                                <tr>
                                    <th style="width:45px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">#</th>
                                    <th style="padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Constituency</th>
                                    <th style="padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">County / Main Area</th>
                                    <th style="width:200px;padding:12px 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">State / Admin Area</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $idx = 1; @endphp
                            @foreach($constituency_rows as $cr)
                                @php
                                $r = $cr['record'];
                                $dname = $cr['group'];
                                $sc = $state_colors[$dname] ?? '#6c757d';
                                $sl = $state_logos[$dname] ?? '';
                                $logo_base = asset('assets/images/logos/');
                                @endphp
                                <tr data-state="{{ $dname }}" data-name="{{ strtolower($r['name']) }}" data-code="{{ $r['code'] }}" style="border-left:3px solid {{ $sc }};transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''" class="constituency-row">
                                    <td class="align-middle text-muted" style="padding:10px 8px;font-size:0.8rem;">{{ $idx }}</td>
                                    <td class="align-middle fw-semibold" style="padding:10px 8px;">
                                        <span class="badge me-1" style="background:{{ $sc }};font-size:0.6rem;letter-spacing:0.3px;font-weight:600;">{{ $r['code'] }}</span>
                                        {{ $r['name'] }}
                                    </td>
                                    <td class="align-middle text-muted" style="padding:10px 8px;font-size:0.85rem;">{{ $r['county'] }}</td>
                                    <td class="align-middle" style="padding:10px 8px;">
                                        @if($sl)
                                            <img src="{{ $logo_base . '/' . $sl }}" alt="" style="width:18px;height:18px;object-fit:cover;border-radius:50%;margin-right:8px;vertical-align:middle;">
                                        @endif
                                        <span style="color:{{ $sc }};font-weight:500;font-size:0.85rem;">{{ $dname }}</span>
                                    </td>
                                </tr>
                            @php $idx++; @endphp
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
                        <p>South Sudan is divided into <strong>{{ $total_constituencies }} constituencies</strong> across <strong>10 states</strong> and <strong>4 administrative areas</strong> (Abyei, Ruweng, Greater Pibor, and Ruweng Administrative Area). Each constituency elects one representative to the National Legislative Assembly.</p>
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
                                    <span class="fw-bold fs-4 d-block" style="color:var(--nec-green);">{{ $total_constituencies }}</span>
                                    <small class="text-muted">Total Constituencies</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:var(--nec-gold);">10</span>
                                    <small class="text-muted">States</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#0d6efd;">4</span>
                                    <small class="text-muted">Admin Areas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded text-center border" style="background:#fff;">
                                    <span class="fw-bold fs-4 d-block" style="color:#dc3545;">~8M</span>
                                    <small class="text-muted">Estimated Voters</small>
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
</style>
@endsection

@section('extra_scripts')
<script>
var constituencyData = @json($js_data);

$(document).ready(function() {
    $('#stateFilter').change(function() {
        var state = $(this).val();
        if (state) {
            $('#constituencyTable tbody tr').hide();
            $('#constituencyTable tbody tr[data-state="' + state + '"]').show();
        } else {
            $('#constituencyTable tbody tr').show();
        }
        updateCount();
    });

    $('#conSearch').on('keyup', function() {
        var q = $(this).val().toLowerCase();
        var state = $('#stateFilter').val();
        $('#constituencyTable tbody tr').each(function() {
            var name = $(this).data('name');
            var code = $(this).data('code').toLowerCase();
            var matches = name.indexOf(q) > -1 || code.indexOf(q) > -1;
            var stateMatch = !state || $(this).data('state') === state;
            $(this).toggle(matches && stateMatch);
        });
        updateCount();
    });

    function updateCount() {
        var vis = $('#constituencyTable tbody tr:visible').length;
        $('#resultCount').text(vis + ' constituency' + (vis !== 1 ? 'ies' : 'y'));
    }

    $('#constituencyTable tbody tr').click(function() {
        var code = $(this).data('code');
        var name = $(this).find('td:eq(1)').text().trim();
        var county = $(this).find('td:eq(2)').text().trim();
        var state = $(this).data('state');
        var desc = (constituencyData[code] && constituencyData[code].description) || '';
        var detailHtml = '<div class="detail-card">'
            + '<h6 class="fw-bold mb-0" style="color:var(--nec-green);"><span class="badge bg-success me-1">' + code + '</span> ' + name.replace(code, '').trim() + '</h6>'
            + '<hr class="my-2">'
            + '<div class="small">'
            + '<div class="mb-2"><strong>County:</strong> ' + county + '</div>'
            + '<div class="mb-2"><strong>State/Area:</strong> ' + state + '</div>'
            + '<div class="mb-2"><strong>Constituency Code:</strong> ' + code + '</div>'
            + (desc ? '<div class="mb-2"><strong>Description:</strong> ' + desc + '</div>' : '')
            + '<div class="mb-0"><strong>Level:</strong> National Legislative Assembly</div>'
            + '</div></div>';
        $('#constituencyDetail').html(detailHtml).addClass('highlight-flash');
        setTimeout(function() { $('#constituencyDetail').removeClass('highlight-flash'); }, 1000);
    });
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

var stateCenters = {!! json_encode([
    'Central Equatoria' => ['lat' => 4.85, 'lng' => 31.6, 'color' => '#0d6efd'],
    'Eastern Equatoria' => ['lat' => 4.5, 'lng' => 33.0, 'color' => '#198754'],
    'Western Equatoria' => ['lat' => 5.0, 'lng' => 29.5, 'color' => '#20c997'],
    'Jonglei + GPAA' => ['lat' => 7.0, 'lng' => 32.5, 'color' => '#0dcaf0'],
    'Unity + Ruweng' => ['lat' => 9.0, 'lng' => 29.8, 'color' => '#ffc107'],
    'Upper Nile' => ['lat' => 10.0, 'lng' => 32.8, 'color' => '#fd7e14'],
    'Lakes' => ['lat' => 6.5, 'lng' => 29.5, 'color' => '#6f42c1'],
    'Northern Bahr el Ghazal' => ['lat' => 8.7, 'lng' => 27.2, 'color' => '#dc3545'],
    'Western Bahr el Ghazal' => ['lat' => 7.5, 'lng' => 26.0, 'color' => '#e83e8c'],
    'Warrap + Abyei' => ['lat' => 8.5, 'lng' => 28.0, 'color' => '#6610f2'],
]) !!};

var stateBounds = {!! json_encode([
    'Central Equatoria' => [[3.8, 30.5], [5.3, 32.5]],
    'Eastern Equatoria' => [[4.0, 32.5], [5.0, 34.0]],
    'Western Equatoria' => [[4.5, 28.5], [5.5, 30.5]],
    'Jonglei + GPAA' => [[6.0, 31.0], [8.5, 34.5]],
    'Unity + Ruweng' => [[8.5, 29.0], [9.8, 30.5]],
    'Upper Nile' => [[9.0, 31.5], [11.0, 34.5]],
    'Lakes' => [[5.8, 28.5], [7.2, 30.5]],
    'Northern Bahr el Ghazal' => [[8.0, 26.0], [9.5, 28.0]],
    'Western Bahr el Ghazal' => [[7.0, 24.5], [8.5, 27.0]],
    'Warrap + Abyei' => [[8.0, 27.0], [10.0, 29.5]],
]) !!};

Object.keys(stateCenters).forEach(function(state) {
    var c = stateCenters[state];
    var bounds = stateBounds[state];
    var marker = L.circleMarker([c.lat, c.lng], {
        radius: 12,
        fillColor: c.color,
        color: '#fff',
        weight: 2,
        opacity: 1,
        fillOpacity: 0.7
    }).addTo(ssdMap);

    marker.bindTooltip(state, { direction: 'top', offset: L.point(0, -10) });

    if (bounds) {
        L.rectangle(bounds, {
            color: c.color,
            weight: 2,
            fillColor: c.color,
            fillOpacity: 0.08,
            dashArray: '5, 5'
        }).addTo(ssdMap);
    }

    marker.on('click', function() {
        $('#stateFilter').val(state).trigger('change');
        var count = $('#constituencyTable tbody tr[data-state="' + state + '"]').length;
        $('#constituencyDetail').html(
            '<div class="detail-card">'
            + '<h6 class="fw-bold mb-0" style="color:' + c.color + ';">' + state + '</h6>'
            + '<hr class="my-2">'
            + '<div class="small">'
            + '<div class="mb-2"><strong>Constituencies:</strong> ' + count + '</div>'
            + '<div class="mb-0"><strong>Region:</strong> ' + (state.includes('Equatoria') ? 'Equatoria' : state.includes('Bahr') ? 'Bahr el Ghazal' : state.includes('Jonglei') || state.includes('Upper Nile') || state.includes('Unity') || state.includes('Lakes') || state.includes('Warrap') ? 'Greater Upper Nile' : 'Bahr el Ghazal') + '</div>'
            + '</div></div>'
        );
    });
});
</script>
@endsection
