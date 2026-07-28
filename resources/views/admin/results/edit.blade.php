@extends('admin.layouts.app', ['title' => 'Edit Result'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Election Result</h2>
    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.results.update', $result->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Election Name *</label>
                    <input type="text" name="election_name" class="form-control" value="{{ old('election_name', $result->election_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Election Type *</label>
                    <input type="text" name="election_type" class="form-control" value="{{ old('election_type', $result->election_type) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Constituency</label>
                    <select name="constituency_id" class="form-select">
                        <option value="">-- Select Constituency --</option>
                        @foreach($constituencies as $c)
                            <option value="{{ $c->id }}" {{ old('constituency_id', $result->constituency_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Votes</label>
                    <input type="number" name="total_votes" class="form-control" value="{{ old('total_votes', $result->total_votes) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registered Voters</label>
                    <input type="number" name="registered_voters" class="form-control" value="{{ old('registered_voters', $result->registered_voters) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Turnout (%)</label>
                    <input type="number" name="turnout" class="form-control" value="{{ old('turnout', $result->turnout) }}" min="0" max="100" step="0.01">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['active','inactive','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $result->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Result</button>
            </div>
        </form>
    </div>
</div>
@endsection
