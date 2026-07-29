@extends('admin.layouts.app', ['title' => 'Edit Event'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected($event->status === 'draft')>Draft</option>
                        <option value="published" @selected($event->status === 'published')>Published</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $event->slug) }}" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Event Type</label>
                    <select name="event_type" class="form-select">
                        <option value="public" @selected($event->event_type === 'public')>Public</option>
                        <option value="internal" @selected($event->event_type === 'internal')>Internal</option>
                        <option value="press" @selected($event->event_type === 'press')>Press Conference</option>
                        <option value="workshop" @selected($event->event_type === 'workshop')>Workshop / Training</option>
                        <option value="ceremony" @selected($event->event_type === 'ceremony')>Ceremony</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Organizer</label>
                    <input type="text" name="organizer" class="form-control" value="{{ old('organizer', $event->organizer) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date &amp; Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date &amp; Time</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image', $event->featured_image) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $event->meta_description) }}" maxlength="500">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="12">{{ old('description', $event->description) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-1"></i> Update Event</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
