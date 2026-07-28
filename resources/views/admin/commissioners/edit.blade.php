@extends('admin.layouts.app')
@section('title', 'Edit Commissioner')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-user-edit text-primary me-2"></i>Edit Commissioner</h1>
    <a href="{{ route('admin.commissioners.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.commissioners.update', $commissioner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $commissioner->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Position *</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $commissioner->position) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Biography</label>
                    <textarea name="bio" class="form-control" rows="4">{{ old('bio', $commissioner->bio) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png">
                    @if($commissioner->photo)
                        <div class="mt-2"><img src="{{ asset('storage/' . $commissioner->photo) }}" alt="" width="60" class="rounded-circle"></div>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="form-label">Order</label>
                    <input type="number" name="order_num" class="form-control" value="{{ old('order_num', $commissioner->order_num) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $commissioner->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $commissioner->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Commissioner</button>
            </div>
        </form>
    </div>
</div>
@endsection
