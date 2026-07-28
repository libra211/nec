@extends('admin.layouts.app')
@section('title', 'Edit News Article')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-newspaper text-primary me-2"></i>Edit News Article</h1>
    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.news.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $article->slug) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-select" required>
                        @foreach(['general','elections','voter_registration','announcements','press_release'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $cat)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $article->author) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Content *</label>
                    <textarea name="content" class="form-control" rows="12" required>{{ old('content', $article->content) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Featured Image</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
                    @if($article->image)
                        <div class="mt-2"><img src="{{ asset('storage/' . $article->image) }}" alt="" width="80" class="rounded"></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', $article->tags) }}" placeholder="Comma-separated">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Article</button>
            </div>
        </form>
    </div>
</div>
@endsection
