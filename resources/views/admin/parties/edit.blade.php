@extends('admin.layouts.app')
@section('title', 'Edit Political Party')
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
.form-control-color {
  padding: 0.25rem;
  cursor: pointer;
}
.color-swatch {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  cursor: pointer;
  border: 2.5px solid transparent;
  transition: var(--transition);
  flex-shrink: 0;
}
.color-swatch:hover { transform: scale(1.12); }
.color-swatch.active { border-color: #1a1a2e; box-shadow: 0 0 0 3px rgba(46,139,87,0.3); }
.drop-zone {
  border: 2px dashed #d1d5db;
  border-radius: var(--input-radius);
  padding: 1.5rem 1rem;
  text-align: center;
  cursor: pointer;
  transition: var(--transition);
  background: #fafbfc;
  position: relative;
}
.drop-zone:hover, .drop-zone.dragover {
  border-color: var(--nec-green);
  background: rgba(46,139,87,0.04);
}
.drop-zone.has-image {
  border-style: solid;
  border-color: var(--nec-green);
  padding: 0.5rem;
}
.drop-zone img {
  max-height: 80px;
  border-radius: 8px;
}
.preview-card {
  border-radius: var(--card-radius);
  overflow: hidden;
  transition: var(--transition);
}
.preview-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important;
}
.preview-avatar {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.2rem;
  flex-shrink: 0;
  transition: var(--transition);
}
.meta-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.85rem;
}
.meta-item:last-child { border-bottom: none; }
.meta-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f1f5f9;
  flex-shrink: 0;
}
.sticky-bar {
  position: sticky;
  bottom: 0;
  z-index: 1020;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(12px);
  border-top: 1px solid #e2e8f0;
  padding: 0.9rem 0;
  margin-top: 2rem;
}
.char-count {
  font-size: 0.72rem;
  color: #94a3b8;
  text-align: right;
  margin-top: 0.15rem;
}
.char-count.over { color: #ef4444; }
.animate-in {
  animation: fadeUp 0.35s ease both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
.animate-in-d4 { animation-delay: 0.2s; }
.form-label {
  font-size: 0.82rem;
  font-weight: 600;
  margin-bottom: 0.35rem;
  color: #334155;
}
.file-label {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.3rem 0.8rem;
  border-radius: 6px;
  font-size: 0.75rem;
  cursor: pointer;
  transition: var(--transition);
  border: 1px solid #e2e8f0;
  background: #fff;
}
.file-label:hover { border-color: var(--nec-green); color: var(--nec-green); }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-flag text-primary me-2"></i>Edit Political Party</h2>
        <p class="text-muted mb-0 small">Update party details, documents, and visibility</p>
    </div>
    <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back to Parties</a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-in animate-in-d1" style="border-radius:12px;">
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <strong>Please fix the errors below</strong>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    <ul class="mb-0 mt-2 small">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.parties.update', $party->id) }}" method="POST" enctype="multipart/form-data" id="partyForm">
@csrf
@method('PUT')
<div class="row g-4">
    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">
        {{-- Party Details --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d1" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-info-circle" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Party Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Party Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="fieldName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $party->name) }}" required maxlength="255" oninput="updatePreview();countChar(this,'nameCount')">
                        <div class="char-count" id="nameCount">{{ strlen(old('name', $party->name)) }}/255</div>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Acronym <span class="text-danger">*</span></label>
                        <input type="text" name="acronym" id="fieldAcronym" class="form-control @error('acronym') is-invalid @enderror" value="{{ old('acronym', $party->acronym) }}" required maxlength="20" oninput="updatePreview();countChar(this,'acroCount')">
                        <div class="char-count" id="acroCount">{{ strlen(old('acronym', $party->acronym)) }}/20</div>
                        @error('acronym') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Leader / Chairperson</label>
                        <input type="text" name="leader" id="fieldLeader" class="form-control @error('leader') is-invalid @enderror" value="{{ old('leader', $party->leader) }}" maxlength="255" oninput="updatePreview()">
                        @error('leader') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Year Founded</label>
                        <input type="number" name="founded" class="form-control @error('founded') is-invalid @enderror" value="{{ old('founded', $party->founded) }}" min="1900" max="{{ date('Y') }}" placeholder="e.g. 2001">
                        @error('founded') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Party Color</label>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <input type="color" name="color" id="fieldColor" class="form-control form-control-color @error('color') is-invalid @enderror" value="{{ old('color', $party->color ?? '#2E8B57') }}" style="height:44px;width:60px;padding:3px;" oninput="updatePreview()">
                            <div class="d-flex gap-1 flex-wrap" id="colorPalette">
                                @php $presets = ['#ED1B24','#E31B23','#2E8B57','#00914c','#0000FF','#000080','#FFA500','#FFFF00','#90EE90','#008000','#87CEEB','#ADD8E6','#FFFFFF','#000000','#800080','#FF6600','#004225','#FFC0CB','#A52A2A','#808080']; @endphp
                                @foreach($presets as $c)
                                <div class="color-swatch {{ old('color', $party->color ?? '') === $c ? 'active' : '' }}" style="background:{{ $c }};" data-color="{{ $c }}" onclick="pickColor(this,'{{ $c }}')"></div>
                                @endforeach
                            </div>
                        </div>
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Party Logo</label>
                        <div class="drop-zone" id="logoDrop" onclick="document.getElementById('logoInput').click()">
                            <input type="file" name="logo" id="logoInput" class="d-none" accept=".jpg,.jpeg,.png,.svg" onchange="handleLogoFile(this)">
                            <div id="logoPlaceholder">
                                <i class="fas fa-cloud-upload-alt" style="font-size:1.6rem;color:#94a3b8;"></i>
                                <div class="small text-muted mt-1">Drop logo here or click to browse</div>
                                <div class="small text-muted" style="font-size:0.7rem;">JPG, PNG, SVG (max 2MB)</div>
                            </div>
                            <div id="logoPreview" class="{{ $party->logo ? '' : 'd-none' }}">
                                <img id="logoImg" src="{{ $party->logo ? asset('storage/' . $party->logo) : '' }}" alt="Logo preview">
                                <div class="mt-1"><button type="button" class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:0.7rem;padding:0.15rem 0.6rem;" onclick="removeLogo(event)"><i class="fas fa-times me-1"></i>Remove</button></div>
                            </div>
                        </div>
                        <input type="hidden" name="remove_logo" id="removeLogoField" value="0">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">
        {{-- Live Preview --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-eye" style="color:#f59e0b;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Live Preview</h6>
                <span class="badge bg-warning text-dark ms-auto" style="font-size:0.6rem;border-radius:6px;"><i class="fas fa-sync-alt me-1"></i>Live</span>
            </div>
            <div class="card-body p-3">
                <div class="preview-card border" id="previewCard" style="background:{{ old('color', $party->color ?? '#2E8B57') }}08;">
                    <div class="p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="preview-avatar" id="previewAvatar" style="background:{{ old('color', $party->color ?? '#2E8B57') }};color:{{ old('color', $party->color ?? '#2E8B57') === '#FFFFFF' || old('color', $party->color ?? '') === '#FFFF00' || old('color', $party->color ?? '') === '#90EE90' || old('color', $party->color ?? '') === '#ADD8E6' || old('color', $party->color ?? '') === '#87CEEB' || old('color', $party->color ?? '') === '#FFC0CB' ? '#000' : '#fff' }};">
                                {{ old('acronym', $party->acronym) ?: substr(old('name', $party->name), 0, 2) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0" id="previewName" style="font-size:0.95rem;">{{ old('name', $party->name) }}</h6>
                                <span class="badge mt-1" id="previewBadge" style="background:{{ old('color', $party->color ?? '#2E8B57') }}20;color:{{ old('color', $party->color ?? '#2E8B57') }};font-size:0.6rem;">{{ old('acronym', $party->acronym) ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="mt-2 small text-muted" id="previewLeader"><i class="fas fa-user-tie me-1" style="font-size:0.65rem;"></i>{{ old('leader', $party->leader) ?: 'No leader set' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Visibility --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d3" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-eye" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Visibility</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold small">Show on Website</span>
                        <div class="text-muted small">Display this party publicly</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="statusToggle" style="width:2.5rem;height:1.25rem;cursor:pointer;" {{ old('status', $party->status) ? 'checked' : '' }} onchange="document.getElementById('statusField').value = this.checked ? '1' : '0'">
                    </div>
                </div>
                <input type="hidden" name="status" id="statusField" value="{{ old('status', $party->status ? 1 : 0) }}">
            </div>
        </div>

        {{-- Registration Document --}}
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d3" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-file-alt" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Registration Document</h6>
            </div>
            <div class="card-body p-4">
                @if($party->registration_document)
                <div class="d-flex align-items-center gap-2 p-2 rounded border bg-light mb-3" style="border-radius:8px;">
                    <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-file-pdf text-danger"></i></div>
                    <div class="flex-fill min-width-0">
                        <div class="small fw-semibold text-truncate">{{ basename($party->registration_document) }}</div>
                        <div class="small text-muted" style="font-size:0.65rem;">{{ number_format(Storage::disk('public')->exists($party->registration_document) ? Storage::disk('public')->size($party->registration_document) / 1024 : 0, 1) }} KB</div>
                    </div>
                    <a href="{{ asset('storage/' . $party->registration_document) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius:6px;padding:0.2rem 0.6rem;font-size:0.7rem;"><i class="fas fa-external-link-alt me-1"></i>View</a>
                </div>
                @endif
                <div>
                    <label class="form-label small">Replace Document</label>
                    <input type="file" name="registration_document" class="form-control @error('registration_document') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:0.82rem;padding:0.4rem 0.75rem;">
                    <div class="form-text" style="font-size:0.7rem;">Accepted: PDF, DOC, DOCX, JPG, PNG (max 5MB)</div>
                    @error('registration_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Metadata --}}
        <div class="card border-0 shadow-sm animate-in animate-in-d4" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(100,116,139,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-history" style="color: #64748b;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Metadata</h6>
            </div>
            <div class="card-body p-4">
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-fingerprint text-muted" style="font-size:0.75rem;"></i></div>
                    <div><div class="fw-semibold small">Party ID</div><div class="small text-muted">#{{ $party->id }}</div></div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-calendar-plus text-muted" style="font-size:0.75rem;"></i></div>
                    <div><div class="fw-semibold small">Created</div><div class="small text-muted">{{ $party->created_at ? $party->created_at->format('d M Y, g:i A') : '—' }}</div></div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-calendar-check text-muted" style="font-size:0.75rem;"></i></div>
                    <div><div class="fw-semibold small">Last Updated</div><div class="small text-muted">{{ $party->updated_at ? $party->updated_at->format('d M Y, g:i A') : '—' }}</div></div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-users text-muted" style="font-size:0.75rem;"></i></div>
                    <div><div class="fw-semibold small">Candidates</div><div class="small text-muted">{{ $party->candidates()->count() }} registered</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sticky Action Bar --}}
<div class="sticky-bar">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted" id="unsavedIndicator"><i class="fas fa-check-circle text-success me-1"></i> All changes saved</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.55rem 1.5rem;">Cancel</a>
            <button type="submit" class="btn btn-nec-green" style="border-radius:10px;padding:0.55rem 1.5rem;font-weight:600;" id="saveBtn"><i class="fas fa-save me-1"></i> Save Changes</button>
        </div>
    </div>
</div>
</form>
@endsection

@section('extra_scripts')
<script>
var formChanged = false;
var originalData = {};

function countChar(input, targetId) {
    var el = document.getElementById(targetId);
    if (!el) return;
    var len = input.value.length;
    var max = input.maxLength || 255;
    el.textContent = len + '/' + max;
    el.classList.toggle('over', len > max);
}

function updatePreview() {
    var name = document.getElementById('fieldName').value || 'Party Name';
    var acro = document.getElementById('fieldAcronym').value || '';
    var leader = document.getElementById('fieldLeader').value || '';
    var color = document.getElementById('fieldColor').value || '#2E8B57';

    document.getElementById('previewName').textContent = name;
    document.getElementById('previewBadge').textContent = acro || '—';
    document.getElementById('previewBadge').style.background = color + '20';
    document.getElementById('previewBadge').style.color = color;
    document.getElementById('previewLeader').innerHTML = '<i class="fas fa-user-tie me-1" style="font-size:0.65rem;"></i>' + (leader || 'No leader set');

    var avatar = document.getElementById('previewAvatar');
    avatar.style.background = color;
    var lightColors = ['#FFFFFF','#FFFF00','#90EE90','#ADD8E6','#87CEEB','#FFC0CB'];
    avatar.style.color = lightColors.includes(color.toUpperCase()) ? '#000' : '#fff';
    avatar.textContent = acro || name.substring(0, 2);

    document.getElementById('previewCard').style.background = color + '08';
    formChanged = true;
    document.getElementById('unsavedIndicator').innerHTML = '<i class="fas fa-circle text-warning me-1" style="font-size:0.5rem;"></i> Unsaved changes';
}

function pickColor(el, color) {
    document.querySelectorAll('.color-swatch').forEach(function(s) { s.classList.remove('active'); });
    el.classList.add('active');
    document.getElementById('fieldColor').value = color;
    updatePreview();
}

function handleLogoFile(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logoImg').src = e.target.result;
        document.getElementById('logoPlaceholder').classList.add('d-none');
        document.getElementById('logoPreview').classList.remove('d-none');
        document.getElementById('logoDrop').classList.add('has-image');
    };
    reader.readAsDataURL(file);
    formChanged = true;
    document.getElementById('unsavedIndicator').innerHTML = '<i class="fas fa-circle text-warning me-1" style="font-size:0.5rem;"></i> Unsaved changes';
}

function removeLogo(e) {
    e.stopPropagation();
    document.getElementById('logoInput').value = '';
    document.getElementById('logoPreview').classList.add('d-none');
    document.getElementById('logoPlaceholder').classList.remove('d-none');
    document.getElementById('logoDrop').classList.remove('has-image');
    document.getElementById('removeLogoField').value = '1';
    formChanged = true;
    document.getElementById('unsavedIndicator').innerHTML = '<i class="fas fa-circle text-warning me-1" style="font-size:0.5rem;"></i> Unsaved changes';
}

// Drag & drop for logo
(function() {
    var dz = document.getElementById('logoDrop');
    dz.addEventListener('dragover', function(e) { e.preventDefault(); dz.classList.add('dragover'); });
    dz.addEventListener('dragleave', function() { dz.classList.remove('dragover'); });
    dz.addEventListener('drop', function(e) {
        e.preventDefault();
        dz.classList.remove('dragover');
        var files = e.dataTransfer.files;
        if (files.length) {
            document.getElementById('logoInput').files = files;
            handleLogoFile(document.getElementById('logoInput'));
        }
    });
})();

// Unsaved changes warning on leave
document.querySelectorAll('#partyForm input, #partyForm select, #partyForm textarea').forEach(function(el) {
    el.addEventListener('change', function() {
        formChanged = true;
        document.getElementById('unsavedIndicator').innerHTML = '<i class="fas fa-circle text-warning me-1" style="font-size:0.5rem;"></i> Unsaved changes';
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    }
});

// Ctrl+S to save
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('saveBtn').click();
    }
});

// Mark as saved after form submit
document.getElementById('partyForm').addEventListener('submit', function() {
    formChanged = false;
    document.getElementById('unsavedIndicator').innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Saving...';
});
</script>
@endsection