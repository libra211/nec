@extends('admin.layouts.app')
@section('title', 'Add Candidate')
@section('extra_css')
<style>
:root {
  --card-radius: 16px;
  --input-radius: 10px;
  --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.form-control, .form-select {
  border-radius: var(--input-radius);
  padding: 0.65rem 1rem;
  font-size: 0.9rem;
  border: 1.5px solid #e2e8f0;
  transition: var(--transition);
}
.form-control:focus, .form-select:focus {
  border-color: var(--nec-green);
  box-shadow: 0 0 0 3px rgba(46,139,87,0.12);
}
.drop-zone {
  border: 2px dashed #d1d5db; border-radius: var(--input-radius);
  padding: 2rem 1rem; text-align: center; cursor: pointer;
  transition: var(--transition); background: #fafbfc; position: relative;
}
.drop-zone:hover, .drop-zone.dragover {
  border-color: var(--nec-green); background: rgba(46,139,87,0.04);
}
.drop-zone.has-image { border-style: solid; border-color: var(--nec-green); padding: 0.5rem; }
.drop-zone img { max-height: 80px; border-radius: 50%; object-fit: cover; }
.preview-card { border-radius: var(--card-radius); overflow: hidden; transition: var(--transition); }
.preview-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important; }
.preview-photo {
  width: 72px; height: 72px; border-radius: 50%;
  object-fit: cover; border: 2px solid #e2e8f0;
}
.preview-photo-placeholder {
  width: 72px; height: 72px; border-radius: 50%;
  background: linear-gradient(135deg,#e2e8f0,#cbd5e1);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.8rem; color: #94a3b8;
}
.animate-in { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
.form-label { font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; color: #334155; }
.section-title { font-size:0.85rem; font-weight:700; color:#1e293b; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:0.5rem; }
.sticky-bar {
  position: sticky; bottom: 0; z-index: 1020;
  background: rgba(255,255,255,0.92); backdrop-filter: blur(12px);
  border-top: 1px solid #e2e8f0; padding: 0.9rem 0; margin-top: 2rem;
}
.char-count { font-size: 0.72rem; color: #94a3b8; text-align: right; margin-top: 0.15rem; }
.char-count.over { color: #ef4444; }
.select-wrapper { position: relative; }
.select-wrapper.loading::after {
  content: ''; position: absolute; right: 2.5rem; top: 50%; transform: translateY(-50%);
  width: 14px; height: 14px; border: 2px solid #e2e8f0; border-top-color: var(--nec-green);
  border-radius: 50%; animation: spin 0.5s linear infinite;
}
@keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-user-plus text-primary me-2"></i>Add Candidate</h2>
        <p class="text-muted mb-0 small">Register a new candidate for the 2026 elections</p>
    </div>
    <a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-in animate-in-d1" style="border-radius:12px;">
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <strong>Please fix the errors below</strong>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" id="candidateForm">
@csrf
<div class="row g-4">
    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">
        {{-- Personal Info --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d1" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-user" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Personal Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="fieldName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255" oninput="updatePreview();countChar(this,'nameCount')">
                        <div class="char-count" id="nameCount">0/255</div>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="trash" {{ old('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position / Office <span class="text-danger">*</span></label>
                        <input type="text" name="position" id="fieldPosition" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" required maxlength="255" placeholder="e.g. Governor, MP, Commissioner" oninput="updatePreview()">
                        @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Political Party</label>
                        <select name="party_id" id="fieldParty" class="form-select @error('party_id') is-invalid @enderror" onchange="updatePreview()">
                            <option value="">-- Select Party --</option>
                            @foreach($parties as $party)
                            <option value="{{ $party->id }}" data-color="{{ $party->color ?? '' }}" data-acronym="{{ $party->acronym ?? '' }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>
                                {{ $party->name }} ({{ $party->acronym ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        @error('party_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Photo</label>
                        <div class="drop-zone" id="photoDrop" onclick="document.getElementById('photoInput').click()">
                            <input type="file" name="photo" id="photoInput" class="d-none" accept=".jpg,.jpeg,.png,.webp" onchange="handlePhotoFile(this)">
                            <div id="photoPlaceholder">
                                <i class="fas fa-cloud-upload-alt" style="font-size:1.8rem;color:#94a3b8;"></i>
                                <div class="small text-muted mt-2">Drop candidate photo here or click to browse</div>
                                <div class="small text-muted" style="font-size:0.7rem;">JPG, PNG, WebP (max 2MB)</div>
                            </div>
                            <div id="photoPreview" class="d-none">
                                <img id="photoImg" src="" alt="Photo preview">
                                <div class="mt-2"><button type="button" class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:0.7rem;padding:0.2rem 0.7rem;" onclick="removePhoto(event)"><i class="fas fa-times me-1"></i>Remove</button></div>
                            </div>
                        </div>
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Geographic Assignment --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-map-marker-alt" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Geographic Assignment</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Region</label>
                        <select id="fieldRegion" class="form-select" onchange="loadStates()">
                            <option value="">-- Select Region --</option>
                            @foreach($regions as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <div class="select-wrapper" id="stateWrapper">
                            <select name="state" id="fieldState" class="form-select @error('state') is-invalid @enderror" required onchange="loadCounties();updatePreview()">
                                <option value="">-- Select Region First --</option>
                            </select>
                        </div>
                        @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">County</label>
                        <div class="select-wrapper" id="countyWrapper">
                            <select id="fieldCounty" class="form-select" onchange="loadConstituencies()">
                                <option value="">-- Select State First --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Constituency</label>
                        <div class="select-wrapper" id="constituencyWrapper">
                            <select name="constituency" id="fieldConstituency" class="form-select @error('constituency') is-invalid @enderror" onchange="updatePreview()">
                                <option value="">-- Select County First --</option>
                            </select>
                        </div>
                        @error('constituency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Bio --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(139,92,246,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-align-left" style="color:#8b5cf6;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Biography</h6>
            </div>
            <div class="card-body p-4">
                <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="5" maxlength="2000" placeholder="Brief background, education, experience, and platform highlights..." oninput="countChar(this,'bioCount')">{{ old('bio') }}</textarea>
                <div class="char-count" id="bioCount">0/2000</div>
                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-eye" style="color:#f59e0b;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Live Preview</h6>
                <span class="badge bg-warning text-dark ms-auto" style="font-size:0.6rem;border-radius:6px;"><i class="fas fa-sync-alt me-1"></i>Live</span>
            </div>
            <div class="card-body p-0">
                <div class="preview-card border-0" id="previewCard">
                    <div style="background:linear-gradient(135deg,#1e293b,#334155);padding:1.5rem;text-align:center;">
                        <div id="previewPhotoContainer" style="display:inline-block;">
                            <img id="previewPhotoImg" class="preview-photo d-none" src="" alt="">
                            <div id="previewPhotoPlaceholder" class="preview-photo-placeholder" style="margin:0 auto;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h5 class="text-white mt-3 mb-1 fw-bold" id="previewName" style="font-size:1rem;">Candidate Name</h5>
                        <div class="text-white-50 small" id="previewPosition" style="font-size:0.8rem;">Position</div>
                        <div class="mt-2">
                            <span class="badge" id="previewPartyBadge" style="background:rgba(255,255,255,0.15);color:#fff;font-size:0.65rem;padding:0.35rem 0.7rem;">No Party</span>
                        </div>
                    </div>
                    <div style="padding:1rem 1.25rem;background:#f8fafc;">
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <i class="fas fa-map-marker-alt" style="font-size:0.7rem;"></i>
                            <span id="previewLocation">No location set</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm animate-in animate-in-d3" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-tasks" style="color:#10b981;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Quick Tips</h6>
            </div>
            <div class="card-body p-4">
                <ul class="small text-muted mb-0" style="padding-left:1rem;line-height:1.8;">
                    <li>Select a <strong>Region</strong> first to filter available States</li>
                    <li>Choose <strong>State</strong> to see its Counties</li>
                    <li>Pick a <strong>County</strong> to view Constituencies</li>
                    <li>Party color shows in the live preview</li>
                    <li>Upload a photo for better identification</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Sticky Action Bar --}}
<div class="sticky-bar">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted" id="unsavedIndicator"><i class="fas fa-info-circle text-muted me-1"></i> Fill in the required fields</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.55rem 1.5rem;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="border-radius:10px;padding:0.55rem 1.5rem;font-weight:600;" id="saveBtn"><i class="fas fa-save me-1"></i> Create Candidate</button>
        </div>
    </div>
</div>
</form>
@endsection

@section('extra_scripts')
<script>
var geoData = @json($geoData);

function countChar(input, targetId) {
    var el = document.getElementById(targetId);
    if (!el) return;
    var len = input.value.length;
    var max = input.maxLength || 255;
    el.textContent = len + '/' + max;
    el.classList.toggle('over', len > max);
}

function updatePreview() {
    var name = document.getElementById('fieldName').value || 'Candidate Name';
    var position = document.getElementById('fieldPosition').value || 'Position';
    var partyId = document.getElementById('fieldParty').value;
    var stateEl = document.getElementById('fieldState');
    var state = stateEl.options[stateEl.selectedIndex]?.text || '';
    var constEl = document.getElementById('fieldConstituency');
    var constituency = constEl.options[constEl.selectedIndex]?.text || '';

    document.getElementById('previewName').textContent = name;
    document.getElementById('previewPosition').textContent = position;

    var badge = document.getElementById('previewPartyBadge');
    if (partyId && partiesData[partyId]) {
        var data = partiesData[partyId];
        var color = data.color || '#6c757d';
        badge.textContent = data.acronym || data.name;
        badge.style.background = color;
        badge.style.color = '#fff';
    } else {
        badge.textContent = 'No Party';
        badge.style.background = 'rgba(255,255,255,0.15)';
        badge.style.color = '#fff';
    }

    var loc = [];
    if (constituency && constituency !== '-- Select County First --' && constituency !== '') loc.push(constituency);
    if (state && state !== '-- Select Region First --' && state !== '-- Select State First --' && state !== '') loc.push(state);
    document.getElementById('previewLocation').textContent = loc.length ? loc.join(', ') : 'No location set';
}

function loadStates() {
    var regionId = document.getElementById('fieldRegion').value;
    var stateSel = document.getElementById('fieldState');
    var countySel = document.getElementById('fieldCounty');
    var constSel = document.getElementById('fieldConstituency');

    stateSel.innerHTML = '<option value="">-- Select State --</option>';
    countySel.innerHTML = '<option value="">-- Select State First --</option>';
    constSel.innerHTML = '<option value="">-- Select County First --</option>';

    if (!regionId) {
        stateSel.innerHTML = '<option value="">-- Select Region First --</option>';
        stateSel.disabled = true;
        countySel.disabled = true;
        constSel.disabled = true;
        return;
    }

    var states = geoData.states.filter(function(s) { return s.region_id == regionId; });
    states.forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.name;
        opt.textContent = s.name;
        stateSel.appendChild(opt);
    });
    stateSel.disabled = false;
    updatePreview();
}

function loadCounties() {
    var stateName = document.getElementById('fieldState').value;
    var countySel = document.getElementById('fieldCounty');
    var constSel = document.getElementById('fieldConstituency');

    countySel.innerHTML = '<option value="">-- Select County --</option>';
    constSel.innerHTML = '<option value="">-- Select County First --</option>';

    if (!stateName) {
        countySel.innerHTML = '<option value="">-- Select State First --</option>';
        countySel.disabled = true;
        constSel.disabled = true;
        return;
    }

    var stateId = null;
    for (var i = 0; i < geoData.states.length; i++) {
        if (geoData.states[i].name === stateName) { stateId = geoData.states[i].id; break; }
    }

    if (!stateId) { countySel.disabled = true; constSel.disabled = true; return; }

    var counties = geoData.counties.filter(function(c) { return c.state_id == stateId; });
    if (counties.length === 0) {
        countySel.innerHTML = '<option value="">No counties for this state</option>';
        countySel.disabled = true;
        constSel.disabled = true;
        return;
    }
    counties.forEach(function(c) {
        var opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        countySel.appendChild(opt);
    });
    countySel.disabled = false;
    constSel.disabled = true;
    updatePreview();
}

function loadConstituencies() {
    var countyId = document.getElementById('fieldCounty').value;
    var constSel = document.getElementById('fieldConstituency');

    constSel.innerHTML = '<option value="">-- Select Constituency --</option>';

    if (!countyId) {
        constSel.innerHTML = '<option value="">-- Select County First --</option>';
        constSel.disabled = true;
        updatePreview();
        return;
    }

    var constituencies = geoData.constituencies.filter(function(c) { return c.county_id == countyId; });
    if (constituencies.length === 0) {
        constSel.innerHTML = '<option value="">No constituencies for this county</option>';
        constSel.disabled = true;
        updatePreview();
        return;
    }
    constituencies.forEach(function(c) {
        var opt = document.createElement('option');
        opt.value = c.name;
        opt.textContent = c.name;
        constSel.appendChild(opt);
    });
    constSel.disabled = false;
    updatePreview();
}

var partiesData = {};
@foreach($parties as $party)
partiesData[{{ $party->id }}] = { color: '{{ $party->color ?? "#6c757d" }}', acronym: '{{ $party->acronym ?? "" }}', name: '{{ $party->name }}' };
@endforeach

function handlePhotoFile(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('previewPhotoImg');
        img.src = e.target.result;
        img.classList.remove('d-none');
        document.getElementById('previewPhotoPlaceholder').classList.add('d-none');
        document.getElementById('photoImg').src = e.target.result;
        document.getElementById('photoPlaceholder').classList.add('d-none');
        document.getElementById('photoPreview').classList.remove('d-none');
        document.getElementById('photoDrop').classList.add('has-image');
    };
    reader.readAsDataURL(file);
}

function removePhoto(e) {
    e.stopPropagation();
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreview').classList.add('d-none');
    document.getElementById('photoPlaceholder').classList.remove('d-none');
    document.getElementById('photoDrop').classList.remove('has-image');
    document.getElementById('previewPhotoImg').classList.add('d-none');
    document.getElementById('previewPhotoPlaceholder').classList.remove('d-none');
}

(function() {
    var dz = document.getElementById('photoDrop');
    dz.addEventListener('dragover', function(e) { e.preventDefault(); dz.classList.add('dragover'); });
    dz.addEventListener('dragleave', function() { dz.classList.remove('dragover'); });
    dz.addEventListener('drop', function(e) {
        e.preventDefault(); dz.classList.remove('dragover');
        var files = e.dataTransfer.files;
        if (files.length) { document.getElementById('photoInput').files = files; handlePhotoFile(document.getElementById('photoInput')); }
    });
})();

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); document.getElementById('saveBtn').click(); }
});
</script>
@endsection