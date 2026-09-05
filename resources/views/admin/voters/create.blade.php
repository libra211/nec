@extends('admin.layouts.app')
@section('title', 'Register Voter')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-user-plus text-primary me-2"></i>Register Voter</h1>
    <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.voters.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Voter ID *</label>
                    <input type="text" name="voter_id" class="form-control" value="{{ old('voter_id') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth *</label>
                    <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">National ID</label>
                    <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State *</label>
                    <select name="state" class="form-select" required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->name }}" {{ old('state') === $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control" value="{{ old('county') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Constituency</label>
                    <input type="text" name="constituency" class="form-control" value="{{ old('constituency') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Polling Station</label>
                    <select name="polling_station_id" class="form-select">
                        <option value="">-- Select Polling Station --</option>
                        @foreach($pollingStations->groupBy('state') as $stName => $group)
                        <optgroup label="{{ $stName ?? 'N/A' }}">
                            @foreach($group as $ps)
                            <option value="{{ $ps->id }}" {{ old('polling_station_id') == $ps->id ? 'selected' : '' }}>{{ $ps->code }} — {{ $ps->name }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Register Voter</button>
            </div>
        </form>
    </div>
</div>
@endsection
