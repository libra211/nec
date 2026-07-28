@extends('admin.layouts.app', ['title' => 'Create News Article'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Create News Article</h2>
    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-select" required>
                        <option value="general">General</option>
                        <option value="elections">Elections</option>
                        <option value="voter_registration">Voter Registration</option>
                        <option value="announcements">Announcements</option>
                        <option value="press_release">Press Release</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Body *</label>
                    <textarea name="body" class="form-control" rows="12" required>{{ old('body') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Featured Image</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', Auth::user()->name ?? 'Admin') }}">
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mt-4">
                        <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" {{ old('is_published') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isPublished">Publish immediately</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-nec-green btn-lg"><i class="fas fa-save me-1"></i> Save Article</button>
            </div>
        </form>
    </div>
</div>
@endsection
