@extends('admin.layouts.app')
@section('title', 'Add Political Party')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-flag text-primary me-2"></i>Add Political Party</h1>
    <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.parties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Party Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Acronym *</label>
                    <input type="text" name="acronym" class="form-control" value="{{ old('acronym') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Leader</label>
                    <input type="text" name="leader" class="form-control" value="{{ old('leader') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Year Founded</label>
                    <input type="number" name="founded" class="form-control" value="{{ old('founded') }}" min="1900" max="{{ date('Y') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.svg">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Color</label>
                    <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#000000') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Create Party</button>
            </div>
        </form>
    </div>
</div>
@endsection
