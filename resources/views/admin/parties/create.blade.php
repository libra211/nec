@extends('admin.layouts.app')
@section('title', 'Add Political Party')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-flag text-primary me-2"></i>Add Political Party</h2>
        <p class="text-muted mb-0 small">Register a new political party</p>
    </div>
    <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-1"></i> Please fix the errors below
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.parties.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2" style="color:var(--nec-green)"></i>Party Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Party Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Acronym <span class="text-danger">*</span></label>
                        <input type="text" name="acronym" class="form-control @error('acronym') is-invalid @enderror" value="{{ old('acronym') }}" required>
                        @error('acronym') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Leader</label>
                        <input type="text" name="leader" class="form-control @error('leader') is-invalid @enderror" value="{{ old('leader') }}">
                        @error('leader') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Year Founded</label>
                        <input type="number" name="founded" class="form-control @error('founded') is-invalid @enderror" value="{{ old('founded') }}" min="1900" max="{{ date('Y') }}">
                        @error('founded') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.svg">
                        <div class="form-text">Accepted: JPG, PNG, SVG</div>
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Party Color</label>
                        <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" value="{{ old('color', '#000000') }}" style="height:44px;">
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-eye me-2" style="color:var(--nec-gold)"></i>Visibility</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between py-2">
                    <div>
                        <span class="fw-semibold small">Show on Website</span>
                        <div class="text-muted small">Display this party publicly</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="statusToggle" checked onchange="document.getElementById('statusField').value = this.checked ? '1' : '0'">
                    </div>
                </div>
                <input type="hidden" name="status" id="statusField" value="1">
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2" style="color:var(--nec-blue)"></i>Registration Document</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Upload Document</label>
                    <input type="file" name="registration_document" class="form-control @error('registration_document') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div class="form-text">Accepted: PDF, DOC, DOCX, JPG, PNG</div>
                    @error('registration_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="alert alert-info py-2 small mb-0">
                    <i class="fas fa-info-circle me-1"></i> Upload certificate of registration, constitution, or other official documents for verification.
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-4">
    <button type="submit" class="btn btn-nec-green"><i class="fas fa-save me-1"></i> Create Party</button>
</div>
</form>
@endsection
