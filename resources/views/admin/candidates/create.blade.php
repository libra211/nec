@extends('admin.layouts.app', ['title' => 'Create Candidate'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Create Candidate</h2>
    <a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.candidates.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Position *</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Political Party</label>
                    <select name="party_id" class="form-select">
                        <option value="">-- Select Party --</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                        @endforeach
                    </select>
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
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo URL</label>
                    <input type="text" name="photo" class="form-control" value="{{ old('photo') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
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
                <button type="submit" class="btn btn-nec-green btn-lg"><i class="fas fa-save me-1"></i> Save Candidate</button>
            </div>
        </form>
    </div>
</div>
@endsection
