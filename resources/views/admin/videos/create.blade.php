@extends('admin.layouts.app', ['title' => 'Add Video'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add Video</h2>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.videos.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="trash" {{ old('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Video URL *</label>
                    <input type="url" name="url" class="form-control" value="{{ old('url') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Thumbnail URL</label>
                    <input type="text" name="thumbnail" class="form-control" value="{{ old('thumbnail') }}">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-1"></i> Save Video</button>
            </div>
        </form>
    </div>
</div>
@endsection
