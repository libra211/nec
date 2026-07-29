@extends('admin.layouts.app', ['title' => 'Create Polling Station'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Create Polling Station</h2>
    <a href="{{ route('admin.polling-stations.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.polling-stations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control" value="{{ old('county') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Payam</label>
                    <input type="text" name="payam" class="form-control" value="{{ old('payam') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Constituency</label>
                    <select name="constituency" class="form-select">
                        <option value="">-- Select Constituency --</option>
                        @foreach($constituencies as $c)
                            <option value="{{ $c->name }}" {{ old('constituency') === $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registered Voters</label>
                    <input type="number" name="registered_voters" class="form-control" value="{{ old('registered_voters', 0) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="number" name="latitude" class="form-control" value="{{ old('latitude') }}" step="0.0000001">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="number" name="longitude" class="form-control" value="{{ old('longitude') }}" step="0.0000001">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="trash" {{ old('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-1"></i> Save Polling Station</button>
            </div>
        </form>
    </div>
</div>
@endsection
