@extends('layouts.app', ['title' => 'Electoral GIS Map — South Sudan', 'active_page' => 'gis-map', 'meta_description' => 'Interactive GIS map of South Sudan electoral infrastructure — states, constituencies, polling stations.'])

@section('hero')
<section class="py-4" style="background:linear-gradient(135deg,#0a1628,#1a3c8f);position:relative;">
    <div class="container">
        <div class="row align-items-center py-3">
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2" style="font-size:0.8rem;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white">GIS Map</li>
                    </ol>
                </nav>
                <h1 class="text-white fw-bold mb-1" style="font-size:2rem;">Electoral GIS Map</h1>
                <p class="text-white-50 mb-0" style="font-size:0.95rem;">Interactive map of South Sudan's electoral infrastructure</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <button class="btn btn-sm btn-outline-light me-2" onclick="toggleLayer('states')"><i class="fas fa-map-marker-alt me-1"></i>States</button>
                <button class="btn btn-sm btn-outline-light me-2" onclick="toggleLayer('stations')"><i class="fas fa-vote-yea me-1"></i>Stations</button>
                <button class="btn btn-sm btn-outline-light" onclick="resetView()"><i class="fas fa-expand me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4" style="background:#f8f9fa;">
    <div class="container-fluid px-4">
        <div class="row g-3">

            {{-- Sidebar --}}
            <div class="col-lg-3">
                <div class="bg-white rounded-3 p-3 shadow-sm" style="position:sticky;top:80px;">
                    <h6 class="fw-bold mb-3" style="font-size:0.85rem;"><i class="fas fa-filter me-1 text-success"></i> Filter Map</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Region</label>
                        <select id="filterRegion" class="form-select form-select-sm" onchange="filterStates()">
                            <option value="">All Regions</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">State</label>
                        <select id="filterState" class="form-select form-select-sm" onchange="loadMapStations()">
                            <option value="">All States</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Station Type</label>
                        <select id="filterType" class="form-select form-select-sm" onchange="loadMapStations()">
                            <option value="">All Types</option>
                            <option value="urban">Urban</option>
                            <option value="rural">Rural</option>
                            <option value="displacement">IDP</option>
                            <option value="refugee">Refugee</option>
                        </select>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-2" style="font-size:0.8rem;"><i class="fas fa-chart-pie me-1 text-success"></i> Map Stats</h6>
                    <div id="mapStats" class="small text-muted">
                        <div class="d-flex justify-content-between mb-1"><span>Stations shown:</span><span class="fw-semibold" id="stationCount">0</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>Total voters:</span><span class="fw-semibold" id="voterCount">0</span></div>
                        <div class="d-flex justify-content-between"><span>Zoom level:</span><span class="fw-semibold" id="zoomLevel">6</span></div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-2" style="font-size:0.8rem;"><i class="fas fa-layer-group me-1 text-success"></i> Legend</h6>
                    <div class="small">
                        <div class="d-flex align-items-center gap-2 mb-1"><span style="width:14px;height:14px;border-radius:50%;background:#00914c;border:2px solid #fff;box-shadow:0 0 0 1px #00914c;display:inline-block;"></span> State Capital</div>
                        <div class="d-flex align-items-center gap-2 mb-1"><span style="width:12px;height:12px;border-radius:50%;background:#0d6efd;border:2px solid #fff;box-shadow:0 0 0 1px #0d6efd;display:inline-block;"></span> Polling Station</div>
                        <div class="d-flex align-items-center gap-2 mb-1"><span style="width:12px;height:12px;border-radius:50%;background:#ffc107;border:2px solid #fff;box-shadow:0 0 0 1px #ffc107;display:inline-block;"></span> Urban Station</div>
                        <div class="d-flex align-items-center gap-2 mb-1"><span style="width:12px;height:12px;border-radius:50%;background:#dc3545;border:2px solid #fff;box-shadow:0 0 0 1px #dc3545;display:inline-block;"></span> IDP Station</div>
                        <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:50%;background:#6f42c1;border:2px solid #fff;box-shadow:0 0 0 1px #6f42c1;display:inline-block;"></span> Refugee Station</div>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="col-lg-9">
                <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                    <div id="gisMap" style="height:calc(100vh - 200px);min-height:500px;"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.info-popup .leaflet-popup-content-wrapper{border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,0.12);}
.info-popup .leaflet-popup-content{margin:10px 14px;font-size:0.82rem;line-height:1.4;}
.info-popup .popup-title{font-weight:700;font-size:0.9rem;color:#0a1628;margin-bottom:4px;}
.info-popup .popup-meta{color:#6c757d;font-size:0.75rem;}
.custom-marker{border-radius:50%;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);}
.leaflet-control-zoom a{width:32px!important;height:32px!important;line-height:32px!important;font-size:0.9rem!important;}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var gisMap=null,stationLayer=null,stateLayer=null;
var allStations=[],allStates=[];
var typeColors={urban:'#ffc107',rural:'#0d6efd',displacement:'#dc3545',refugee:'#6f42c1',default:'#0d6efd'};

document.addEventListener('DOMContentLoaded',function(){
    gisMap=L.map('gisMap',{center:[7.5,30],zoom:6,scrollWheelZoom:true,zoomControl:{position:'topleft'}});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap contributors',maxZoom:18,maxZoom:18}).addTo(gisMap);
    stationLayer=L.layerGroup().addTo(gisMap);
    stateLayer=L.layerGroup().addTo(gisMap);

    gisMap.on('zoomend',function(){document.getElementById('zoomLevel').textContent=gisMap.getZoom()});

    Promise.all([fetch('/api/geo/dashboard').then(function(r){return r.json()}),fetch('/api/geo/states').then(function(r){return r.json()})]).then(function(results){
        var dashboard=results[0],statesData=results[1];
        allStates=statesData.states||statesData;

        var regionSel=document.getElementById('filterRegion');
        (dashboard.regions||[]).forEach(function(r){
            var o=document.createElement('option');o.value=r.id;o.textContent=r.name;regionSel.appendChild(o);
        });

        drawStateMarkers(dashboard.states||[]);
        loadAllStations();
    });
});

function drawStateMarkers(states){
    stateLayer.clearLayers();
    states.forEach(function(s){
        if(!s.latitude||!s.longitude)return;
        var c=L.circleMarker([s.latitude,s.longitude],{radius:10,fillColor:'#00914c',color:'#fff',weight:3,opacity:1,fillOpacity:0.85,className:'custom-marker'});
        c.bindTooltip('<b>'+s.name+'</b><br>'+s.capital,{direction:'top',offset:L.point(0,-12)});
        c.bindPopup('<div class="popup-title">'+s.name+'</div><div class="popup-meta">Capital: '+s.capital+'</div><div class="popup-meta">'+(s.county_count||0)+' counties · '+(s.constituency_count||0)+' constituencies</div><div class="popup-meta">'+(s.polling_station_count||0)+' polling stations</div>');
        stateLayer.addLayer(c);
    });
}

function loadAllStations(){
    var stateId=document.getElementById('filterState').value;
    var url=stateId?'/api/geo/polling-stations?state_id='+stateId:'/api/geo/polling-stations';
    fetch(url).then(function(r){return r.json()}).then(function(data){
        allStations=data.stations||data||[];
        drawStations();
    });
}

function drawStations(){
    stationLayer.clearLayers();
    var type=document.getElementById('filterType').value;
    var count=0,totalVoters=0;
    var visible=type?allStations.filter(function(s){return s.station_type===type}):allStations;

    visible.forEach(function(s){
        if(!s.latitude||!s.longitude)return;
        var color=typeColors[s.station_type]||typeColors.default;
        var size=s.station_type==='rural'?6:7;
        var m=L.circleMarker([s.latitude,s.longitude],{radius:size,fillColor:color,color:'#fff',weight:2,opacity:1,fillOpacity:0.8});
        var popup='<div class="popup-title">'+s.name+'</div>'+
            '<div class="popup-meta">'+(s.county||'')+', '+(s.state||'')+'</div>'+
            '<div class="popup-meta">Type: '+(s.station_type||'Standard')+'</div>'+
            '<div class="popup-meta">Registered voters: <b>'+Number(s.registered_voters||0).toLocaleString()+'</b></div>';
        if(s.address) popup+='<div class="popup-meta"><i class="fas fa-map-marker-alt me-1"></i>'+s.address+'</div>';
        m.bindPopup(popup);
        m.bindTooltip(s.name,{direction:'top',offset:L.point(0,-8),opacity:0.9});
        stationLayer.addLayer(m);
        count++;totalVoters+=parseInt(s.registered_voters||0);
    });

    document.getElementById('stationCount').textContent=count;
    document.getElementById('voterCount').textContent=Number(totalVoters).toLocaleString();
}

function filterStates(){
    var regionId=document.getElementById('filterRegion').value;
    var stateSel=document.getElementById('filterState');
    stateSel.innerHTML='<option value="">All States</option>';
    var filtered=regionId?allStations.filter(function(){return true}):allStates;
    var useStates=regionId?allStates.filter(function(s){return s.region_id==regionId}):allStates;
    useStates.forEach(function(s){
        var o=document.createElement('option');o.value=s.id;o.textContent=s.name;stateSel.appendChild(o);
    });
    stateLayer.clearLayers();
    if(regionId) fetch('/api/geo/dashboard').then(function(r){return r.json()}).then(function(d){drawStateMarkers((d.states||[]).filter(function(s){return s.region_id==regionId}))});
    else fetch('/api/geo/dashboard').then(function(r){return r.json()}).then(function(d){drawStateMarkers(d.states||[])});
    loadMapStations();
}

function loadMapStations(){loadAllStations();}

function toggleLayer(type){
    if(type==='states'){
        if(gisMap.hasLayer(stateLayer)){gisMap.removeLayer(stateLayer)}
        else{gisMap.addLayer(stateLayer)}
    }else if(type==='stations'){
        if(gisMap.hasLayer(stationLayer)){gisMap.removeLayer(stationLayer)}
        else{gisMap.addLayer(stationLayer)}
    }
}

function resetView(){
    gisMap.setView([7.5,30],6);
    if(!gisMap.hasLayer(stateLayer)) gisMap.addLayer(stateLayer);
    if(!gisMap.hasLayer(stationLayer)) gisMap.addLayer(stationLayer);
    document.getElementById('filterRegion').value='';
    document.getElementById('filterState').value='';
    document.getElementById('filterType').value='';
    filterStates();
}
</script>
@endpush
