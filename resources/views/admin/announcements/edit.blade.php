@extends('admin.layouts.app')
@section('title', 'Edit Announcement')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-bullhorn text-primary me-2"></i>Edit Announcement</h1>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $announcement->slug) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $announcement->excerpt) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Content *</label>
                    <textarea name="content" class="form-control" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select" required>
                        @foreach(['notice','general','urgent','press'] as $type)
                            <option value="{{ $type }}" {{ old('type', $announcement->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="published" {{ old('status', $announcement->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $announcement->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Published At</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $announcement->published_at ? \Carbon\Carbon::parse($announcement->published_at)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image', $announcement->featured_image) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $announcement->meta_description) }}" maxlength="500">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Announcement</button>
            </div>
        </form>
    </div>
</div>
@endsection
