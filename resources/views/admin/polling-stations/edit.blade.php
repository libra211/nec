@extends('admin.layouts.app', ['title' => 'Edit Polling Station'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Polling Station</h2>
    <a href="{{ route('admin.polling-stations.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.polling-stations.update', $pollingStation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $pollingStation->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $pollingStation->code) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $pollingStation->state) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control" value="{{ old('county', $pollingStation->county) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Payam</label>
                    <input type="text" name="payam" class="form-control" value="{{ old('payam', $pollingStation->payam) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Constituency</label>
                    <select name="constituency" class="form-select">
                        <option value="">-- Select Constituency --</option>
                        @foreach($constituencies as $c)
                            <option value="{{ $c->name }}" {{ old('constituency', $pollingStation->constituency) === $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registered Voters</label>
                    <input type="number" name="registered_voters" class="form-control" value="{{ old('registered_voters', $pollingStation->registered_voters) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="number" name="latitude" class="form-control" value="{{ old('latitude', $pollingStation->latitude) }}" step="0.0000001">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="number" name="longitude" class="form-control" value="{{ old('longitude', $pollingStation->longitude) }}" step="0.0000001">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['active','inactive','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $pollingStation->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Polling Station</button>
            </div>
        </form>
    </div>
</div>
@endsection
