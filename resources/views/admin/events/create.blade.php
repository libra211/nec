@extends('admin.layouts.app', ['title' => 'Create Event'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-plus me-2"></i>Create Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="published" selected>Published</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" class="form-control" placeholder="Auto-generated if empty" value="{{ old('slug') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Event Type</label>
                    <select name="event_type" class="form-select">
                        <option value="public">Public</option>
                        <option value="internal">Internal</option>
                        <option value="press">Press Conference</option>
                        <option value="workshop">Workshop / Training</option>
                        <option value="ceremony">Ceremony</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Venue or virtual link">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Organizer</label>
                    <input type="text" name="organizer" class="form-control" value="{{ old('organizer') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date &amp; Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date &amp; Time</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}" maxlength="500">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="12">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-nec-green btn-lg"><i class="fas fa-save me-1"></i> Create Event</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
