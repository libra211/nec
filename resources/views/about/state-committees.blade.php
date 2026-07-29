@extends('layouts.app', ['title' => 'State High Committees — Geographic Dashboard', 'active_page' => 'about', 'meta_description' => 'Interactive geographic dashboard of South Sudan electoral structure — states, counties, constituencies, and polling stations.'])

@push('styles')
<style>
.shc-hero{background:linear-gradient(135deg,#0a1628 0%,#1a3c8f 50%,#2E8B57 100%);position:relative;overflow:hidden;}
.shc-hero::before{content:'';position:absolute;top:-40%;right:-15%;width:500px;height:500px;border-radius:50%;background:rgba(212,175,55,0.06);}
.shc-hero::after{content:'';position:absolute;bottom:-30%;left:-10%;width:400px;height:400px;border-radius:50%;background:rgba(46,139,87,0.06);}
.shc-stats-bar{background:#fff;border-radius:16px;padding:1.5rem 2rem;margin-top:-50px;position:relative;z-index:10;box-shadow:0 20px 60px rgba(0,0,0,0.08);}
.shc-stat{text-align:center;padding:0.5rem;}
.shc-stat-num{font-size:1.8rem;font-weight:800;color:var(--nec-green);line-height:1;}
.shc-stat-label{font-size:0.7rem;color:#6c757d;text-transform:uppercase;letter-spacing:1px;margin-top:2px;font-weight:600;}
.state-card{background:#fff;border-radius:14px;border:1px solid #eee;overflow:hidden;transition:all 0.3s ease;cursor:pointer;height:100%;}
.state-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,0.1);border-color:var(--nec-green);}
.state-card.active{border-color:var(--nec-green);box-shadow:0 0 0 3px rgba(0,145,76,0.15);}
.state-card-header{padding:1rem 1.25rem 0.5rem;display:flex;align-items:center;gap:10px;}
.state-flag{width:40px;height:40px;border-radius:8px;object-fit:cover;border:1px solid #eee;}
.state-card-body{padding:0 1.25rem 1rem;}
.state-card-name{font-weight:700;font-size:0.95rem;color:var(--nec-black);margin-bottom:2px;}
.state-card-capital{font-size:0.78rem;color:#6c757d;}
.state-card-stats{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;}
.state-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:0.68rem;font-weight:600;background:#f0f7f4;color:var(--nec-green);}
.detail-panel{background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);overflow:hidden;position:sticky;top:90px;}
.detail-panel-header{background:linear-gradient(135deg,var(--nec-green),var(--nec-green-dark));padding:1.5rem;color:#fff;}
.detail-panel-body{padding:1.5rem;}
.detail-tab{border:none;color:#6c757d;font-weight:600;font-size:0.8rem;padding:0.5rem 1rem;border-bottom:3px solid transparent;transition:all 0.3s;}
.detail-tab.active{color:var(--nec-green);border-bottom-color:var(--nec-green);background:none;}
.detail-tab:hover:not(.active){color:var(--nec-black);}
.detail-tab-content{padding:1rem 0;}
.drill-item{padding:10px 14px;border-radius:10px;border:1px solid #f0f0f0;transition:all 0.2s;cursor:pointer;margin-bottom:6px;}
.drill-item:hover{background:#f0f7f4;border-color:var(--nec-green);}
.drill-item-name{font-weight:600;font-size:0.88rem;color:var(--nec-black);}
.drill-item-meta{font-size:0.75rem;color:#6c757d;}
#shcMap{border-radius:12px;}
.breadcrumb-sm{font-size:0.8rem;}
.breadcrumb-sm a{color:var(--nec-green);text-decoration:none;}
.breadcrumb-sm a:hover{text-decoration:underline;}
.loading-shimmer{background:linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px;height:20px;}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
.stat-ring{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#fff;flex-shrink:0;}
</style>
@endpush

@section('hero')
<section class="shc-hero py-5">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center py-4">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2" style="font-size:2.6rem;">State High Committees</h1>
                <p class="text-white-50 mb-3" style="font-size:1.05rem;max-width:600px;">Interactive geographic dashboard — explore South Sudan's electoral structure from regions down to polling stations.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 breadcrumb-sm">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">State High Committees</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-map-marked-alt text-white" style="font-size:4rem;opacity:0.15;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">

        {{-- Stats Bar --}}
        <div class="shc-stats-bar" data-aos="fade-up">
            <div class="row">
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ $totals['states'] }}</div>
                        <div class="shc-stat-label">States</div>
                    </div>
                </div>
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ number_format($totals['counties']) }}</div>
                        <div class="shc-stat-label">Counties</div>
                    </div>
                </div>
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ number_format($totals['constituencies']) }}</div>
                        <div class="shc-stat-label">Constituencies</div>
                    </div>
                </div>
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ number_format($totals['polling_stations']) }}</div>
                        <div class="shc-stat-label">Polling Stations</div>
                    </div>
                </div>
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ number_format($totals['payams']) }}</div>
                        <div class="shc-stat-label">Payams</div>
                    </div>
                </div>
                <div class="col-4 col-md">
                    <div class="shc-stat">
                        <div class="shc-stat-num">{{ number_format($totals['bomas']) }}</div>
                        <div class="shc-stat-label">Bomas</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="mt-4 mb-3" data-aos="fade-up">
            <div class="input-group" style="max-width:500px;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="stateSearch" class="form-control border-start-0" placeholder="Search states, counties, constituencies..." style="border-left:0;">
            </div>
        </div>

        <div class="row g-4">

            {{-- Left: State Cards --}}
            <div class="col-lg-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0" style="color:var(--nec-black);">States & Areas</h5>
                    <span class="badge bg-success" style="font-size:0.7rem;">{{ $totals['states'] }} active</span>
                </div>

                @foreach($regions as $region)
                <div class="mb-3">
                    <h6 class="fw-bold text-uppercase" style="font-size:0.72rem;letter-spacing:1.5px;color:var(--nec-green);margin-bottom:8px;">
                        <i class="fas fa-globe-africa me-1"></i> {{ $region->name }}
                    </h6>
                    <div class="row g-2">
                        @foreach($states->where('region_id', $region->id) as $state)
                        <div class="col-12">
                            <div class="state-card" id="stateCard{{ $state->id }}" onclick="loadState({{ $state->id }}, this)" data-name="{{ strtolower($state->name) }}">
                                <div class="state-card-header">
                                    <div class="stat-ring" style="background:linear-gradient(135deg,#1a3c8f,#2E8B57);width:36px;height:36px;font-size:0.75rem;">
                                        {{ $state->code }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="state-card-name">{{ $state->name }}</div>
                                        <div class="state-card-capital">Capital: {{ $state->capital }}</div>
                                    </div>
                                </div>
                                <div class="state-card-body">
                                    <div class="state-card-stats">
                                        <span class="state-chip"><i class="fas fa-building"></i> {{ $state->county_count }} counties</span>
                                        <span class="state-chip"><i class="fas fa-vote-yea"></i> {{ $state->constituency_count }} constituencies</span>
                                        <span class="state-chip"><i class="fas fa-map-pin"></i> {{ $state->polling_station_count }} stations</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right: Detail Panel --}}
            <div class="col-lg-7">
                <div class="detail-panel" id="detailPanel">
                    {{-- Default Map View --}}
                    <div id="defaultView">
                        <div class="detail-panel-header">
                            <h5 class="fw-bold mb-1"><i class="fas fa-map me-2"></i>Electoral Map</h5>
                            <p class="mb-0 opacity-75" style="font-size:0.85rem;">Click a state on the left to explore its details</p>
                        </div>
                        <div class="p-3">
                            <div id="shcMap" style="height:420px;"></div>
                        </div>
                    </div>

                    {{-- State Detail View --}}
                    <div id="stateDetailView" style="display:none;">
                        <div class="detail-panel-header d-flex justify-content-between align-items-start">
                            <div>
                                <button class="btn btn-sm btn-outline-light mb-2" onclick="backToMap()" style="font-size:0.75rem;">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Map
                                </button>
                                <h4 class="fw-bold mb-0" id="detailStateName"></h4>
                                <p class="mb-0 opacity-75" style="font-size:0.85rem;" id="detailStateCapital"></p>
                            </div>
                            <div class="text-end" id="detailStatRings"></div>
                        </div>

                        <div class="detail-panel-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item"><a class="nav-link detail-tab active" data-bs-toggle="tab" href="#tabOverview" role="tab">Overview</a></li>
                                <li class="nav-item"><a class="nav-link detail-tab" data-bs-toggle="tab" href="#tabCounties" role="tab">Counties</a></li>
                                <li class="nav-item"><a class="nav-link detail-tab" data-bs-toggle="tab" href="#tabConstituencies" role="tab">Constituencies</a></li>
                                <li class="nav-item"><a class="nav-link detail-tab" data-bs-toggle="tab" href="#tabStations" role="tab">Polling Stations</a></li>
                                <li class="nav-item"><a class="nav-link detail-tab" data-bs-toggle="tab" href="#tabMap" role="tab">Map</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tabOverview" role="tabpanel">
                                    <div id="overviewContent"><div class="loading-shimmer mb-2"></div><div class="loading-shimmer" style="width:60%;"></div></div>
                                </div>
                                <div class="tab-pane fade" id="tabCounties" role="tabpanel">
                                    <div id="countiesContent"><div class="loading-shimmer mb-2"></div></div>
                                </div>
                                <div class="tab-pane fade" id="tabConstituencies" role="tabpanel">
                                    <div id="constituenciesContent"><div class="loading-shimmer mb-2"></div></div>
                                </div>
                                <div class="tab-pane fade" id="tabStations" role="tabpanel">
                                    <div id="stationsContent"><div class="loading-shimmer mb-2"></div></div>
                                </div>
                                <div class="tab-pane fade" id="tabMap" role="tabpanel">
                                    <div id="stateMapContainer" style="height:350px;border-radius:12px;overflow:hidden;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SHC Role Section --}}
        <div class="mt-5" data-aos="fade-up">
            <h3 class="fw-bold mb-4" style="color:var(--nec-black);">Role of State High Committees</h3>
            <div class="row g-3">
                @php
                $roles = [
                    ['icon' => 'fa-clipboard-list', 'title' => 'Voter Registration', 'desc' => 'Oversee voter registration drives within the state'],
                    ['icon' => 'fa-school', 'title' => 'Polling Stations', 'desc' => 'Establish and manage polling stations across counties'],
                    ['icon' => 'fa-boxes', 'title' => 'Logistics', 'desc' => 'Coordinate distribution of election materials'],
                    ['icon' => 'fa-shield-alt', 'title' => 'Security', 'desc' => 'Coordinate with security agencies for safe elections'],
                    ['icon' => 'fa-chart-bar', 'title' => 'Result Collation', 'desc' => 'Collate and transmit results to NEC headquarters'],
                    ['icon' => 'fa-handshake', 'title' => 'Stakeholder Liaison', 'desc' => 'Engage with local stakeholders and political parties'],
                ];
                @endphp
                @foreach($roles as $r)
                <div class="col-md-4">
                    <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--nec-green);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas {{ $r['icon'] }} text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $r['title'] }}</h6>
                            <p class="small text-muted mb-0">{{ $r['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
var pollingStations = @json($pollingStations);
var stateCenters = {
    'Central Equatoria': {lat:4.85,lng:31.6,color:'#0d6efd'},
    'Eastern Equatoria': {lat:4.5,lng:33.0,color:'#198754'},
    'Western Equatoria': {lat:5.0,lng:29.5,color:'#20c997'},
    'Jonglei': {lat:7.0,lng:32.5,color:'#0dcaf0'},
    'Greater Pibor Administrative Area': {lat:6.5,lng:33.5,color:'#17a2b8'},
    'Unity': {lat:9.0,lng:29.8,color:'#ffc107'},
    'Ruweng Administrative Area': {lat:9.5,lng:29.5,color:'#fd7e14'},
    'Upper Nile': {lat:10.0,lng:32.8,color:'#fd7e14'},
    'Lakes': {lat:6.5,lng:29.5,color:'#6f42c1'},
    'Northern Bahr el Ghazal': {lat:8.7,lng:27.2,color:'#dc3545'},
    'Western Bahr el Ghazal': {lat:7.5,lng:26.0,color:'#e83e8c'},
    'Warrap': {lat:8.5,lng:28.0,color:'#6610f2'},
    'Abyei Special Administrative Area': {lat:9.6,lng:28.4,color:'#343a40'},
};

var shcMap = L.map('shcMap', {center:[7.5,30],zoom:6,scrollWheelZoom:true,zoomControl:true});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:18}).addTo(shcMap);

Object.keys(stateCenters).forEach(function(name){
    var c=stateCenters[name];
    var m=L.circleMarker([c.lat,c.lng],{radius:10,fillColor:c.color,color:'#fff',weight:2,opacity:1,fillOpacity:0.75}).addTo(shcMap);
    m.bindTooltip(name,{direction:'top',offset:L.point(0,-8)});
    m.on('click',function(){
        var card=document.querySelector('[data-name="'+name.toLowerCase()+'"]');
        if(card) card.click();
    });
});

function loadState(id, el){
    document.querySelectorAll('.state-card').forEach(function(c){c.classList.remove('active')});
    if(el) el.classList.add('active');

    document.getElementById('defaultView').style.display='none';
    document.getElementById('stateDetailView').style.display='block';

    document.getElementById('overviewContent').innerHTML='<div class="loading-shimmer mb-2"></div><div class="loading-shimmer" style="width:60%;"></div>';
    document.getElementById('countiesContent').innerHTML='<div class="loading-shimmer mb-2"></div>';
    document.getElementById('constituenciesContent').innerHTML='<div class="loading-shimmer mb-2"></div>';
    document.getElementById('stationsContent').innerHTML='<div class="loading-shimmer mb-2"></div>';

    fetch('/api/geo/state/'+id)
        .then(function(r){return r.json()})
        .then(function(data){
            var s=data.state;
            document.getElementById('detailStateName').textContent=s.name+' ('+s.code+')';
            document.getElementById('detailStateCapital').textContent='Capital: '+s.capital;

            var st=data.stats;
            document.getElementById('detailStatRings').innerHTML=
                '<div class="d-flex gap-2 flex-wrap justify-content-end">'+
                '<div class="stat-ring" style="background:#0d6efd;width:50px;height:50px;font-size:0.8rem;">'+st.counties+'</div>'+
                '<div class="stat-ring" style="background:#198754;width:50px;height:50px;font-size:0.8rem;">'+st.constituencies+'</div>'+
                '<div class="stat-ring" style="background:#ffc107;width:50px;height:50px;font-size:0.8rem;">'+st.polling_stations+'</div>'+
                '</div>';

            renderOverview(data);
            renderCounties(data.counties);
            renderConstituencies(data.constituencies);
            renderStations(data.polling_stations);
            renderStateMap(data);
        });
}

function renderOverview(data){
    var s=data.state,st=data.stats;
    var html='<div class="row g-3 mb-3">'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:var(--nec-green);">'+st.counties+'</span><small class="text-muted">Counties</small></div></div>'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:#0d6efd;">'+st.constituencies+'</span><small class="text-muted">Constituencies</small></div></div>'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:#ffc107;">'+st.polling_stations+'</span><small class="text-muted">Polling Stations</small></div></div>'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:#dc3545;">'+st.payams+'</span><small class="text-muted">Payams</small></div></div>'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:#6f42c1;">'+st.bomas+'</span><small class="text-muted">Bomas</small></div></div>'+
        '<div class="col-4"><div class="p-3 rounded-3 text-center" style="background:#f0f7f4;"><span class="fw-bold fs-4 d-block" style="color:#e83e8c;">'+Number(st.registered_voters).toLocaleString()+'</span><small class="text-muted">Reg. Voters</small></div></div>'+
        '</div>';

    html+='<h6 class="fw-bold mt-3 mb-2" style="color:var(--nec-black);">Counties Overview</h6>';
    html+='<div class="row g-2">';
    data.counties.forEach(function(c){
        html+='<div class="col-md-6"><div class="drill-item" onclick="drillCounty('+c.id+')">'+
            '<div class="d-flex justify-content-between align-items-center">'+
            '<div class="drill-item-name">'+c.name+'</div>'+
            '<i class="fas fa-chevron-right text-muted" style="font-size:0.7rem;"></i></div>'+
            '<div class="drill-item-meta">'+c.constituency_count+' constituencies · '+c.polling_station_count+' stations · '+Number(c.registered_voters||0).toLocaleString()+' voters</div></div></div>';
    });
    html+='</div>';
    document.getElementById('overviewContent').innerHTML=html;
}

function renderCounties(counties){
    var html='<div class="list-group list-group-flush">';
    counties.forEach(function(c){
        html+='<div class="list-group-item px-0 d-flex justify-content-between align-items-center" style="border-left:3px solid var(--nec-green);cursor:pointer;" onclick="drillCounty('+c.id+')">'+
            '<div><div class="fw-semibold" style="font-size:0.9rem;">'+c.name+'</div>'+
            '<small class="text-muted">'+c.constituency_count+' constituencies · '+Number(c.registered_voters||0).toLocaleString()+' voters</small></div>'+
            '<i class="fas fa-chevron-right text-muted"></i></div>';
    });
    html+='</div>';
    document.getElementById('countiesContent').innerHTML=html;
}

function renderConstituencies(constituencies){
    var html='<div style="max-height:400px;overflow-y:auto;">';
    constituencies.forEach(function(c,i){
        html+='<div class="drill-item"><div class="d-flex align-items-center gap-2">'+
            '<span class="badge" style="background:var(--nec-green);font-size:0.6rem;">'+c.code+'</span>'+
            '<div><div class="drill-item-name">'+c.name+'</div>'+
            '<div class="drill-item-meta">'+c.county+'</div></div></div></div>';
    });
    if(!constituencies.length) html='<p class="text-muted text-center py-3">No constituencies found</p>';
    html+='</div>';
    document.getElementById('constituenciesContent').innerHTML=html;
}

function renderStations(stations){
    var html='<div style="max-height:400px;overflow-y:auto;">';
    stations.forEach(function(s){
        html+='<div class="drill-item"><div class="d-flex justify-content-between align-items-center">'+
            '<div><div class="drill-item-name">'+s.name+'</div>'+
            '<div class="drill-item-meta">'+s.county+' · '+Number(s.registered_voters||0).toLocaleString()+' voters</div></div>'+
            (s.latitude?'<span class="badge bg-light text-dark" style="font-size:0.6rem;"><i class="fas fa-map-pin me-1"></i>GPS</span>':'')+
            '</div></div>';
    });
    if(!stations.length) html='<p class="text-muted text-center py-3">No polling stations found</p>';
    html+='</div>';
    document.getElementById('stationsContent').innerHTML=html;
}

var stateMapInstance=null;
function renderStateMap(data){
    var name=data.state.name;
    var center=stateCenters[name]||{lat:7.5,lng:30};
    if(stateMapInstance){stateMapInstance.remove();stateMapInstance=null;}
    stateMapInstance=L.map('stateMapContainer',{center:[center.lat,center.lng],zoom:8,scrollWheelZoom:true});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:18}).addTo(stateMapInstance);
    data.polling_stations.forEach(function(s){
        if(s.latitude&&s.longitude){
            L.circleMarker([s.latitude,s.longitude],{radius:7,fillColor:center.color||'#00914c',color:'#fff',weight:2,fillOpacity:0.85}).addTo(stateMapInstance)
                .bindTooltip(s.name+'<br>'+Number(s.registered_voters||0).toLocaleString()+' voters',{direction:'top'});
        }
    });
    setTimeout(function(){stateMapInstance.invalidateSize()},200);
}

function drillCounty(id){
    fetch('/api/geo/county/'+id)
        .then(function(r){return r.json()})
        .then(function(data){
            var c=data.county;
            document.getElementById('constituenciesContent').innerHTML=
                '<h6 class="fw-bold mb-2" style="color:var(--nec-black);">'+c.name+' — Constituencies</h6>'+
                renderConstituencies(data.constituencies);
            document.getElementById('countiesContent').innerHTML=
                '<h6 class="fw-bold mb-2" style="color:var(--nec-black);">'+c.name+' — Payams</h6>'+
                renderPayams(data.payams);
            document.getElementById('stationsContent').innerHTML=
                '<h6 class="fw-bold mb-2" style="color:var(--nec-black);">'+c.name+' — Polling Stations</h6>'+
                renderStations(data.polling_stations);

            var tabs=document.querySelectorAll('.detail-tab');
            tabs[1].click();
        });
}

function renderPayams(payams){
    var html='<div class="list-group list-group-flush">';
    payams.forEach(function(p){
        html+='<div class="list-group-item px-0 d-flex justify-content-between align-items-center" style="border-left:3px solid #ffc107;">'+
            '<div><div class="fw-semibold" style="font-size:0.9rem;">'+p.name+'</div>'+
            '<small class="text-muted">'+p.boma_count+' bomas</small></div></div>';
    });
    if(!payams.length) html='<p class="text-muted text-center py-3">No payams found</p>';
    html+='</div>';
    return html;
}

function backToMap(){
    document.getElementById('defaultView').style.display='block';
    document.getElementById('stateDetailView').style.display='none';
    document.querySelectorAll('.state-card').forEach(function(c){c.classList.remove('active')});
    setTimeout(function(){shcMap.invalidateSize()},100);
}

document.getElementById('stateSearch').addEventListener('input',function(){
    var q=this.value.toLowerCase();
    document.querySelectorAll('.state-card').forEach(function(card){
        card.closest('.col-12').style.display=card.getAttribute('data-name').indexOf(q)>-1?'':'none';
    });
});
</script>
@endpush
