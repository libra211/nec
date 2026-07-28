@extends('admin.layouts.app', ['title' => 'Edit Video'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Video</h2>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $video->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['published','draft','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $video->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $video->description) }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Video URL *</label>
                    <input type="url" name="url" class="form-control" value="{{ old('url', $video->url) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Thumbnail URL</label>
                    <input type="text" name="thumbnail" class="form-control" value="{{ old('thumbnail', $video->thumbnail) }}">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Video</button>
            </div>
        </form>
    </div>
</div>
@endsection
