@extends('admin.layouts.app', ['title' => 'Create Speech'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Create Speech</h2>
    <a href="{{ route('admin.speeches.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.speeches.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Speech Date</label>
                    <input type="date" name="speech_date" class="form-control" value="{{ old('speech_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Speaker</label>
                    <input type="text" name="speaker" class="form-control" value="{{ old('speaker') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Event Name</label>
                    <input type="text" name="event_name" class="form-control" value="{{ old('event_name') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="10">{{ old('content') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Document URL</label>
                    <input type="text" name="document_url" class="form-control" value="{{ old('document_url') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}" maxlength="500">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="trash" {{ old('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-1"></i> Save Speech</button>
            </div>
        </form>
    </div>
</div>
@endsection
