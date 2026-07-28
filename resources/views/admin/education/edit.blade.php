@extends('admin.layouts.app', ['title' => 'Edit Education Material'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Education Material</h2>
    <a href="{{ route('admin.education.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.education.update', $material->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $material->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Content Type</label>
                    <select name="content_type" class="form-select">
                        @foreach(['document','video','infographic','poster','presentation','other'] as $type)
                            <option value="{{ $type }}" {{ old('content_type', $material->content_type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $material->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Path</label>
                    <input type="text" name="file_path" class="form-control" value="{{ old('file_path', $material->file_path) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control" value="{{ old('language', $material->language) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Target Audience</label>
                    <input type="text" name="target_audience" class="form-control" value="{{ old('target_audience', $material->target_audience) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['published','draft','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $material->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Material</button>
            </div>
        </form>
    </div>
</div>
@endsection
