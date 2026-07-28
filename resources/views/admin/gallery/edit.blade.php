@extends('admin.layouts.app', ['title' => 'Edit Gallery Image'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Gallery Image</h2>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $gallery->title) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $gallery->slug) }}" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Album</label>
                    <input type="text" name="album" class="form-control" value="{{ old('album', $gallery->album) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $gallery->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image Path *</label>
                    <input type="text" name="image_path" class="form-control" value="{{ old('image_path', $gallery->image_path) }}" required>
                    @if($gallery->image_path)
                        <div class="mt-2"><img src="{{ asset('storage/' . $gallery->image_path) }}" alt="" width="80" class="rounded"></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image', $gallery->featured_image) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $gallery->meta_description) }}" maxlength="500">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['published','draft','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $gallery->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Image</button>
            </div>
        </form>
    </div>
</div>
@endsection
