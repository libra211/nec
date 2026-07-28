@extends('admin.layouts.app')
@section('title', 'Add Download')
@section('content')
<a href="{{ route('admin.downloads.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Downloads</a>
<h2 class="mb-4"><i class="fas fa-plus-circle text-success me-2"></i>Add Download Resource</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.downloads.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="e.g. Forms, Reports...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">File Type</label>
                    <input type="text" name="file_type" class="form-control" value="{{ old('file_type') }}" placeholder="e.g. PDF">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">File Path/URL *</label>
                    <input type="text" name="file_path" class="form-control" value="{{ old('file_path') }}" required placeholder="e.g. documents/form.pdf or https://...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">File Size (bytes)</label>
                    <input type="number" name="file_size" class="form-control" value="{{ old('file_size') }}" min="0">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn px-4" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> Save Download</button>
                <a href="{{ route('admin.downloads.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
