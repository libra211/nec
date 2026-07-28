@extends('admin.layouts.app', ['title' => 'Edit Registration Agent'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Registration Agent</h2>
    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Agents
    </a>
</div>

<form method="POST" action="{{ route('admin.agents.update', $agent) }}">
    @csrf
    @method('PUT')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background:var(--nec-green);color:#fff;">
            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $agent->first_name) }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $agent->last_name) }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $agent->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $agent->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">National ID</label>
                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $agent->national_id) }}">
                    @error('national_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $agent->title) }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background:var(--nec-blue);color:#fff;">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Location</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <select name="state" class="form-select @error('state') is-invalid @enderror">
                        <option value="">Select State</option>
                        @foreach(config('nec.regions') as $region)
                            @foreach($region['states'] as $state)
                                <option value="{{ $state }}" {{ old('state', $agent->state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control @error('county') is-invalid @enderror" value="{{ old('county', $agent->county) }}">
                    @error('county')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Constituency</label>
                    <input type="text" name="constituency" class="form-control @error('constituency') is-invalid @enderror" value="{{ old('constituency', $agent->constituency) }}">
                    @error('constituency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Payam</label>
                    <input type="text" name="payam" class="form-control @error('payam') is-invalid @enderror" value="{{ old('payam', $agent->payam) }}">
                    @error('payam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Boma</label>
                    <input type="text" name="boma" class="form-control @error('boma') is-invalid @enderror" value="{{ old('boma', $agent->boma) }}">
                    @error('boma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background:var(--nec-gold);color:#000;">
            <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Assignment</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Assigned State</label>
                    <select name="assigned_state" class="form-select @error('assigned_state') is-invalid @enderror">
                        <option value="">Select State</option>
                        @foreach(config('nec.regions') as $region)
                            @foreach($region['states'] as $state)
                                <option value="{{ $state }}" {{ old('assigned_state', $agent->assigned_state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('assigned_state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned County</label>
                    <input type="text" name="assigned_county" class="form-control @error('assigned_county') is-invalid @enderror" value="{{ old('assigned_county', $agent->assigned_county) }}">
                    @error('assigned_county')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned Constituency</label>
                    <input type="text" name="assigned_constituency" class="form-control @error('assigned_constituency') is-invalid @enderror" value="{{ old('assigned_constituency', $agent->assigned_constituency) }}">
                    @error('assigned_constituency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h5>
        </div>
        <div class="card-body">
            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes about this agent...">{{ old('notes', $agent->notes) }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
            <i class="fas fa-save me-1"></i> Update Agent
        </button>
    </div>
</form>
@endsection
