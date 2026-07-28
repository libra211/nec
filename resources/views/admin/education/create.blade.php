@extends('admin.layouts.app', ['title' => 'Create Education Material'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Create Education Material</h2>
    <a href="{{ route('admin.education.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.education.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Content Type</label>
                    <select name="content_type" class="form-select">
                        @foreach(['document','video','infographic','poster','presentation','other'] as $type)
                            <option value="{{ $type }}" {{ old('content_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Path</label>
                    <input type="text" name="file_path" class="form-control" value="{{ old('file_path') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control" value="{{ old('language', 'English') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Target Audience</label>
                    <input type="text" name="target_audience" class="form-control" value="{{ old('target_audience', 'general') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="trash" {{ old('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-nec-green btn-lg"><i class="fas fa-save me-1"></i> Save Material</button>
            </div>
        </form>
    </div>
</div>
@endsection
