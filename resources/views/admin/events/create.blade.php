@extends('admin.layouts.app', ['title' => 'Add New Event'])

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<style>
#wp-editor { background:#f0f0f1; min-height:calc(100vh - 60px); margin:-1.5rem; padding-bottom:40px; }
#wp-admin-bar { background:#fff; border-bottom:1px solid #dcdcde; padding:10px 20px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; }
.wp-bar-left { display:flex; align-items:center; gap:12px; }
.wp-bar-left h1 { font-size:1.2rem; font-weight:600; margin:0; color:#1d2327; }
.wp-back-btn { width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%; color:#787c82; text-decoration:none; transition:all .15s; }
.wp-back-btn:hover { background:#f0f0f1; color:#1d2327; }
.wp-status-badge { font-size:0.7rem; font-weight:500; padding:2px 10px; border-radius:3px; text-transform:uppercase; letter-spacing:0.3px; }
.wp-status-badge.draft { background:#f0f6fc; color:#1d2327; border:1px solid #c3c4c7; }
.wp-status-badge.published { background:#edfaef; color:#008a20; border:1px solid #68de7c; }
.wp-exit-link { font-size:0.8rem; color:#787c82; text-decoration:none; margin-left:8px; }
.wp-exit-link:hover { color:#1d2327; text-decoration:underline; }
.wp-bar-right { display:flex; align-items:center; gap:8px; }
.wp-btn-save { background:#f6f7f7; border:1px solid #2271b1; color:#2271b1; padding:6px 16px; border-radius:3px; font-size:0.8rem; font-weight:500; cursor:pointer; transition:all .15s; }
.wp-btn-save:hover { background:#f0f0f1; }
.wp-btn-publish { background:#2271b1; border:1px solid #2271b1; color:#fff; padding:6px 16px; border-radius:3px; font-size:0.8rem; font-weight:500; cursor:pointer; transition:all .15s; }
.wp-btn-publish:hover { background:#135e96; border-color:#135e96; }
#wp-editor-grid { display:flex; gap:20px; padding:20px; max-width:1400px; margin:0 auto; }
#wp-editor-main { flex:1; min-width:0; }
#wp-editor-sidebar { width:300px; flex-shrink:0; }
#titlediv { background:#fff; border:1px solid #dcdcde; border-radius:4px; margin-bottom:8px; display:flex; }
#titlediv input { width:100%; padding:12px 14px; font-size:1.3rem; font-weight:600; border:none; outline:none; background:transparent; color:#1d2327; }
#titlediv input::placeholder { color:#9ca0a4; font-weight:400; }
#edit-slug-box { background:#fff; border:1px solid #dcdcde; border-top:none; border-radius:0 0 4px 4px; padding:8px 14px; margin-bottom:16px; display:flex; align-items:center; gap:4px; font-size:0.78rem; color:#50575e; margin-top:-1px; }
#edit-slug-box .slug-prefix { color:#646970; }
.slug-field { border:none; background:transparent; color:#2271b1; font-size:0.78rem; outline:none; flex:1; min-width:80px; padding:2px 0; }
.slug-field:focus { border-bottom:1px solid #2271b1; }
#wp-content-editor { background:#fff; border:1px solid #dcdcde; border-radius:4px; }
.meta-box { background:#fff; border:1px solid #dcdcde; border-radius:4px; margin-bottom:16px; }
.meta-box-header { padding:8px 12px; font-size:0.78rem; font-weight:600; color:#1d2327; border-bottom:1px solid #dcdcde; background:#f6f7f7; text-transform:uppercase; letter-spacing:0.3px; }
.meta-box-body { padding:12px; }
.meta-field { margin-bottom:10px; }
.meta-field label { font-size:0.75rem; font-weight:500; color:#50575e; margin-bottom:3px; display:block; text-transform:uppercase; letter-spacing:0.2px; }
.wp-image-placeholder { border:2px dashed #c3c4c7; border-radius:6px; padding:24px 12px; cursor:pointer; color:#787c82; transition:all .15s; display:flex; flex-direction:column; align-items:center; gap:6px; }
.wp-image-placeholder:hover { border-color:#2271b1; color:#2271b1; background:#f0f6fc; }
.wp-image-placeholder i { font-size:1.5rem; }
.wp-image-placeholder span { font-size:0.8rem; }
@media (max-width:768px) { #wp-editor-grid { flex-direction:column; } #wp-editor-sidebar { width:100%; } }
</style>
@endsection

@section('content')
<div id="wp-editor">
    <div id="wp-admin-bar">
        <div class="wp-bar-left">
            <a href="{{ route('admin.events.index') }}" class="wp-back-btn"><i class="fas fa-chevron-left"></i></a>
            <h1>Add New Event</h1>
            <span class="wp-status-badge draft">Draft</span>
            <a href="{{ route('admin.events.index') }}" class="wp-exit-link">Exit editor</a>
        </div>
        <div class="wp-bar-right">
            <button type="submit" form="event-form" class="wp-btn-save" id="save-draft-btn"><i class="fas fa-save me-1"></i> Save Draft</button>
            <button type="submit" form="event-form" class="wp-btn-publish" id="publish-btn" name="status" value="published"><i class="fas fa-paper-plane me-1"></i> Publish</button>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger rounded-3 border-0 mx-3 mt-3">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form id="event-form" action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="status-input" value="draft">

        <div id="wp-editor-grid">
            <div id="wp-editor-main">
                <div id="titlediv">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Add title" autocomplete="off" oninput="autoSlug()" required>
                </div>

                <div id="edit-slug-box">
                    <strong>Permalink:</strong>
                    <span class="slug-prefix">{{ url('/events') }}/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="auto-generated" class="slug-field">
                    <button type="button" class="btn btn-sm btn-link" onclick="document.getElementById('slug').dataset.modified='1';document.getElementById('slug').focus()">Edit</button>
                </div>

                <div id="wp-content-editor">
                    <textarea name="description" id="editor" class="form-control" rows="12">{{ old('description') }}</textarea>
                </div>
            </div>

            <div id="wp-editor-sidebar">
                <div class="meta-box">
                    <div class="meta-box-header">Publish</div>
                    <div class="meta-box-body">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100 mb-2" onclick="document.getElementById('status-input').value='published'"><i class="fas fa-paper-plane me-1"></i> Publish</button>
                            <button type="submit" class="btn btn-outline-secondary w-100" onclick="document.getElementById('status-input').value='draft'"><i class="fas fa-save me-1"></i> Save Draft</button>
                        </div>
                        <div class="meta-field">
                            <label>Status</label>
                            <select name="status_display" class="form-select form-select-sm" onchange="document.getElementById('status-input').value=this.value">
                                <option value="draft" selected>Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        <div class="meta-field">
                            <label>Organizer</label>
                            <input type="text" name="organizer" class="form-control form-control-sm" value="{{ old('organizer') }}">
                        </div>
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">Event Details</div>
                    <div class="meta-box-body">
                        <div class="meta-field">
                            <label>Type</label>
                            <select name="event_type" class="form-select form-select-sm">
                                <option value="public">Public</option>
                                <option value="internal">Internal</option>
                                <option value="press">Press Conference</option>
                                <option value="workshop">Workshop / Training</option>
                                <option value="ceremony">Ceremony</option>
                            </select>
                        </div>
                        <div class="meta-field">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control form-control-sm" value="{{ old('location') }}" placeholder="Venue or virtual link">
                        </div>
                        <div class="meta-field">
                            <label>Start Date &amp; Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_date" class="form-control form-control-sm @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="meta-field">
                            <label>End Date &amp; Time</label>
                            <input type="datetime-local" name="end_date" class="form-control form-control-sm" value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">Featured Image</div>
                    <div class="meta-box-body text-center">
                        <div id="featured-image-preview" class="mb-2" style="display:none;">
                            <img id="image-preview-img" src="" alt="" style="max-width:100%;max-height:160px;border-radius:6px;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearFeaturedImage()"><i class="fas fa-trash me-1"></i> Remove</button>
                        </div>
                        <div id="featured-image-placeholder" class="wp-image-placeholder" onclick="document.getElementById('image-input').click()">
                            <i class="fas fa-image"></i>
                            <span>Set featured image</span>
                        </div>
                        <input type="file" name="featured_image" id="image-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;" onchange="previewFeaturedImage(this)">
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">SEO</div>
                    <div class="meta-box-body">
                        <div class="meta-field">
                            <label>Meta Description</label>
                            <textarea name="meta_description" class="form-control form-control-sm" rows="3" maxlength="500" placeholder="Brief description for search engines...">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
function autoSlug() {
    var title = document.getElementById('title').value;
    var slugField = document.getElementById('slug');
    if (!slugField.dataset.modified) {
        slugField.value = title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    }
}
$(document).ready(function () {
    document.getElementById('slug').addEventListener('input', function () {
        this.dataset.modified = this.value !== '' ? '1' : '';
    });
    $('#editor').summernote({
        height: 420,
        placeholder: 'Describe the event...',
        toolbar: [
            ['style', ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph', 'align']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr', 'symbol']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['height', ['height']]
        ],
        fontSizes: ['8','9','10','11','12','13','14','16','18','20','24','28','36','48'],
        callbacks: {
            onChange: function(contents) { document.getElementById('editor').value = contents; }
        }
    });
});
function previewFeaturedImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('featured-image-preview').style.display = 'block';
            document.getElementById('image-preview-img').src = e.target.result;
            document.getElementById('featured-image-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearFeaturedImage() {
    document.getElementById('featured-image-preview').style.display = 'none';
    document.getElementById('featured-image-placeholder').style.display = 'flex';
    document.getElementById('image-input').value = '';
}
</script>
@endsection
