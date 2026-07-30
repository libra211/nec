@extends('admin.layouts.app', ['title' => 'Edit Polling Station'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-map-marker-alt" style="color:#2E8B57;margin-right:10px;"></i> Edit Polling Station</h2>
        <p class="text-muted mb-0 small">Update polling station details and geographic hierarchy</p>
    </div>
    <a href="{{ route('admin.polling-stations.index') }}" class="btn btn-outline-secondary rounded-3 px-3"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-4">
        @if($errors->any())
        <div class="alert alert-danger rounded-3 border-0">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.polling-stations.update', $pollingStation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-12"><h6 class="fw-semibold text-muted" style="font-size:0.8rem;letter-spacing:0.5px;text-transform:uppercase;">Basic Information</h6></div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Polling Station Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $pollingStation->name) }}" placeholder="e.g. Juba City Central Primary School" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Unique Code</label>
                    <div class="input-group">
                        <input type="text" name="code" id="code" class="form-control rounded-3" value="{{ old('code', $pollingStation->code) }}" placeholder="Auto-generated" readonly style="background:#f8fafc;">
                        <button type="button" class="btn btn-outline-success rounded-3" onclick="generateCode()" title="Generate new code"><i class="fas fa-sync-alt me-1"></i> Generate</button>
                    </div>
                    <div class="form-text">Click "Generate" to create a new unique code.</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select rounded-3" required>
                        @foreach(['active','inactive','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $pollingStation->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12"><hr style="opacity:0.08;"><h6 class="fw-semibold text-muted" style="font-size:0.8rem;letter-spacing:0.5px;text-transform:uppercase;">Geographic Location</h6></div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Region</label>
                    <select name="region" class="form-select rounded-3" onchange="filterStates(this.value)">
                        <option value="">-- Select Region --</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->name }}" data-id="{{ $r->id }}" {{ old('region', $pollingStation->region) === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">State <span class="text-danger">*</span></label>
                    <select name="state" id="state" class="form-select rounded-3" onchange="filterCounties()" required>
                        <option value="">-- Select State --</option>
                        @foreach($states as $s)
                            <option value="{{ $s->name }}" data-id="{{ $s->id }}" data-region="{{ $s->region_id }}" {{ old('state', $pollingStation->state) === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">County</label>
                    <select name="county" id="county" class="form-select rounded-3" onchange="filterConstituencies()">
                        <option value="">-- Select County --</option>
                        @foreach($counties as $c)
                            <option value="{{ $c->name }}" data-id="{{ $c->id }}" data-state="{{ $c->state_id }}" {{ old('county', $pollingStation->county) === $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Constituency</label>
                    <select name="constituency" id="constituency" class="form-select rounded-3">
                        <option value="">-- Select Constituency --</option>
                        @foreach($constituencies as $c)
                            <option value="{{ $c->name }}" data-id="{{ $c->id }}" data-county="{{ $c->county_id }}" {{ old('constituency', $pollingStation->constituency) === $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Payam</label>
                    <input type="text" name="payam" class="form-control rounded-3" value="{{ old('payam', $pollingStation->payam) }}" placeholder="e.g. Juba Central Payam">
                </div>

                <div class="col-12"><hr style="opacity:0.08;"><h6 class="fw-semibold text-muted" style="font-size:0.8rem;letter-spacing:0.5px;text-transform:uppercase;">Additional Details</h6></div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Registered Voters</label>
                    <input type="number" name="registered_voters" class="form-control rounded-3" value="{{ old('registered_voters', $pollingStation->registered_voters) }}" min="0" placeholder="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Latitude</label>
                    <input type="number" name="latitude" class="form-control rounded-3" value="{{ old('latitude', $pollingStation->latitude) }}" step="0.0000001" placeholder="e.g. 4.851650">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Longitude</label>
                    <input type="number" name="longitude" class="form-control rounded-3" value="{{ old('longitude', $pollingStation->longitude) }}" step="0.0000001" placeholder="e.g. 31.582470">
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3"><i class="fas fa-save me-2"></i> Update Polling Station</button>
                <a href="{{ route('admin.polling-stations.index') }}" class="btn btn-outline-secondary rounded-3 px-4 ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
function generateCode() {
    fetch('{{ route('admin.polling-stations.generate-code') }}')
        .then(r => r.json())
        .then(d => document.getElementById('code').value = d.code);
}

var regionStateMap = {}, stateCountyMap = {}, countyConstituencyMap = {};

@foreach($states as $s)
    regionStateMap[{{ $s->region_id ?? 'null' }}] = regionStateMap[{{ $s->region_id ?? 'null' }}] || [];
    regionStateMap[{{ $s->region_id ?? 'null' }}].push('{{ $s->name }}');
@endforeach

@foreach($counties as $c)
    @if($c->state_id)
    stateCountyMap[{{ $c->state_id }}] = stateCountyMap[{{ $c->state_id }}] || [];
    stateCountyMap[{{ $c->state_id }}].push('{{ $c->name }}');
    @endif
@endforeach

@foreach($constituencies as $c)
    @if($c->county_id)
    countyConstituencyMap[{{ $c->county_id }}] = countyConstituencyMap[{{ $c->county_id }}] || [];
    countyConstituencyMap[{{ $c->county_id }}].push('{{ $c->name }}');
    @endif
@endforeach

var allStates = document.querySelectorAll('#state option');
var allCounties = document.querySelectorAll('#county option');
var allConstituencies = document.querySelectorAll('#constituency option');

function filterStates(regionName) {
    var regionId = null;
    document.querySelector('select[name="region"] option').forEach(function(o) {
        if (o.value === regionName) regionId = o.getAttribute('data-id');
    });
    var validStates = regionStateMap[regionId] || [];
    resetSelect('state', '-- Select State --');
    resetSelect('county', '-- Select County --');
    resetSelect('constituency', '-- Select Constituency --');
    allStates.forEach(function(o) {
        if (!o.value || validStates.includes(o.value)) o.style.display = '';
        else o.style.display = 'none';
    });
}

function filterCounties() {
    var stateName = document.getElementById('state').value;
    var stateId = null;
    allStates.forEach(function(o) {
        if (o.value === stateName) stateId = o.getAttribute('data-id');
    });
    if (!stateId) { resetSelect('county', '-- Select County --'); resetSelect('constituency', '-- Select Constituency --'); return; }
    var validCounties = stateCountyMap[stateId] || [];
    resetSelect('county', '-- Select County --');
    resetSelect('constituency', '-- Select Constituency --');
    allCounties.forEach(function(o) {
        if (!o.value || validCounties.includes(o.value)) o.style.display = '';
        else o.style.display = 'none';
    });
}

function filterConstituencies() {
    var countyName = document.getElementById('county').value;
    var countyId = null;
    allCounties.forEach(function(o) {
        if (o.value === countyName) countyId = o.getAttribute('data-id');
    });
    if (!countyId) { resetSelect('constituency', '-- Select Constituency --'); return; }
    var validConstituencies = countyConstituencyMap[countyId] || [];
    resetSelect('constituency', '-- Select Constituency --');
    allConstituencies.forEach(function(o) {
        if (!o.value || validConstituencies.includes(o.value)) o.style.display = '';
        else o.style.display = 'none';
    });
}

function resetSelect(id, placeholder) {
    var sel = document.getElementById(id);
    sel.value = '';
    sel.innerHTML = '<option value="">' + placeholder + '</option>';
    var src = id === 'state' ? allStates : (id === 'county' ? allCounties : allConstituencies);
    src.forEach(function(o) {
        if (o.value) sel.add(o.cloneNode(true));
    });
}
</script>
@endsection
