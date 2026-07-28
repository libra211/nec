@extends('admin.layouts.app', ['title' => 'Edit Voter'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Edit Voter</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.voters.index') }}">Voters</a></li>
                <li class="breadcrumb-item active">Edit - {{ $voter->voter_id }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.voters.show', $voter->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.voters.update', $voter->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-green)"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Voter ID</label>
                    <input type="text" class="form-control" value="{{ $voter->voter_id }}" disabled>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $voter->full_name) }}" required>
                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="M" {{ old('gender', $voter->gender) === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('gender', $voter->gender) === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date of Birth *</label>
                    <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $voter->dob ? date('Y-m-d', strtotime($voter->dob)) : '') }}" required>
                    @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">National ID</label>
                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $voter->national_id) }}">
                    @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $voter->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $voter->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="inactive" {{ old('status', $voter->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $voter->phone) }}">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $voter->email) }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12 mt-4">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-blue)"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h5>
                </div>
                <div class="col-md-3">
                    <label class="form-label">State *</label>
                    <select name="state" class="form-select @error('state') is-invalid @enderror" required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->name }}" {{ old('state', $voter->state) === $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control @error('county') is-invalid @enderror" value="{{ old('county', $voter->county) }}">
                    @error('county') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Constituency</label>
                    <input type="text" name="constituency" class="form-control @error('constituency') is-invalid @enderror" value="{{ old('constituency', $voter->constituency) }}">
                    @error('constituency') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payam</label>
                    <input type="text" name="payam" class="form-control @error('payam') is-invalid @enderror" value="{{ old('payam', $voter->payam) }}">
                    @error('payam') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Boma</label>
                    <input type="text" name="boma" class="form-control @error('boma') is-invalid @enderror" value="{{ old('boma', $voter->boma) }}">
                    @error('boma') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Polling Station</label>
                    <input type="text" name="polling_station" class="form-control @error('polling_station') is-invalid @enderror" value="{{ old('polling_station', $voter->polling_station) }}">
                    @error('polling_station') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Registration Center</label>
                    <input type="text" name="registration_center" class="form-control @error('registration_center') is-invalid @enderror" value="{{ old('registration_center', $voter->registration_center) }}">
                    @error('registration_center') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Registration Type</label>
                    <input type="text" class="form-control" value="{{ $voter->registration_type === 'agent' ? 'Agent-Assisted' : 'Self-Registration' }}" disabled>
                </div>

                <div class="col-12 mt-4">
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
                            <i class="fas fa-save me-1"></i> Update Voter
                        </button>
                        <a href="{{ route('admin.voters.show', $voter->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
