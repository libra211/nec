@extends('admin.layouts.app', ['title' => 'Edit Gallery Album'])

@section('extra_css')
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
.meta-box { background:#fff; border:1px solid #dcdcde; border-radius:4px; margin-bottom:16px; }
.meta-box-header { padding:8px 12px; font-size:0.78rem; font-weight:600; color:#1d2327; border-bottom:1px solid #dcdcde; background:#f6f7f7; text-transform:uppercase; letter-spacing:0.3px; }
.meta-box-body { padding:12px; }
.meta-field { margin-bottom:10px; }
.meta-field label { font-size:0.75rem; font-weight:500; color:#50575e; margin-bottom:3px; display:block; text-transform:uppercase; letter-spacing:0.2px; }
.wp-image-placeholder { border:2px dashed #c3c4c7; border-radius:6px; padding:24px 12px; cursor:pointer; color:#787c82; transition:all .15s; display:flex; flex-direction:column; align-items:center; gap:6px; }
.wp-image-placeholder:hover { border-color:#2271b1; color:#2271b1; background:#f0f6fc; }
.wp-image-placeholder i { font-size:1.5rem; }
.wp-image-placeholder span { font-size:0.8rem; }
.upload-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:10px; margin-top:8px; }
.upload-grid .img-thumb { position:relative; border-radius:6px; overflow:hidden; border:1px solid #dcdcde; background:#fafafa; }
.upload-grid .img-thumb img { width:100%; height:90px; object-fit:cover; display:block; }
.upload-grid .img-thumb .thumb-actions { position:absolute; top:3px; right:3px; display:flex; gap:2px; }
.upload-grid .img-thumb .thumb-actions button { width:20px; height:20px; border-radius:50%; background:rgba(0,0,0,0.6); color:#fff; border:none; font-size:0.55rem; display:flex; align-items:center; justify-content:center; cursor:pointer; }
.upload-grid .img-thumb .thumb-label { padding:4px 6px; font-size:0.6rem; color:#50575e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sort-handle { cursor:grab; }
.sort-handle:active { cursor:grabbing; }
@media (max-width:768px) { #wp-editor-grid { flex-direction:column; } #wp-editor-sidebar { width:100%; } }
</style>
@endsection

@section('content')
<div id="wp-editor">
    <div id="wp-admin-bar">
        <div class="wp-bar-left">
            <a href="{{ route('admin.gallery.index') }}" class="wp-back-btn"><i class="fas fa-chevron-left"></i></a>
            <h1>Edit Album: {{ $album->title }}</h1>
            <span class="wp-status-badge {{ $album->status }}">{{ $album->status }}</span>
            <a href="{{ route('admin.gallery.index') }}" class="wp-exit-link">Exit editor</a>
        </div>
        <div class="wp-bar-right">
            <button type="submit" form="album-form" class="wp-btn-save" id="save-draft-btn"><i class="fas fa-save me-1"></i> Save Draft</button>
            <button type="submit" form="album-form" class="wp-btn-publish" id="publish-btn" name="status" value="published"><i class="fas fa-paper-plane me-1"></i> Publish</button>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger rounded-3 border-0 mx-3 mt-3">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form id="album-form" action="{{ route('admin.gallery.update', $album->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <input type="hidden" name="status" id="status-input" value="{{ $album->status }}">
        <input type="hidden" name="remove_featured" id="remove-featured" value="">

        <div id="wp-editor-grid">
            <div id="wp-editor-main">
                <div id="titlediv">
                    <input type="text" name="title" id="title" value="{{ old('title', $album->title) }}" placeholder="Album title" autocomplete="off" oninput="autoSlug()" required>
                </div>

                <div id="edit-slug-box">
                    <strong>Permalink:</strong>
                    <span class="slug-prefix">{{ url('/gallery') }}/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $album->slug) }}" placeholder="auto-generated" class="slug-field">
                    <button type="button" class="btn btn-sm btn-link" onclick="document.getElementById('slug').dataset.modified='1';document.getElementById('slug').focus()">Edit</button>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">
                        Images
                        <span class="badge bg-secondary ms-1" style="font-size:0.6rem;">{{ $album->images->count() }}</span>
                    </div>
                    <div class="meta-box-body">
                        <div id="existing-images">
                            @foreach($album->images()->orderBy('sort_order')->get() as $img)
                            <input type="hidden" name="existing_images[]" value="{{ $img->id }}">
                            <input type="hidden" name="sort_order[{{ $img->id }}]" value="{{ $img->sort_order }}" class="sort-input">
                            @endforeach
                        </div>

                        <div id="images-grid" class="upload-grid">
                            @foreach($album->images()->orderBy('sort_order')->get() as $img)
                            <div class="img-thumb" data-id="{{ $img->id }}">
                                <div class="thumb-actions">
                                    <button type="button" class="sort-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></button>
                                    <button type="button" onclick="removeExisting({{ $img->id }}, this)" title="Remove"><i class="fas fa-times"></i></button>
                                </div>
                                <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text }}">
                                <div class="thumb-label">{{ $img->alt_text }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label>Add More Images</label>
                            <input type="file" name="images[]" id="images-input" multiple accept=".jpg,.jpeg,.png,.webp,.gif" style="display:none;" onchange="previewNewImages(this)">
                            <div id="add-more-btn" class="wp-image-placeholder" style="padding:16px 12px;" onclick="document.getElementById('images-input').click()">
                                <i class="fas fa-plus" style="font-size:1rem;"></i>
                                <span style="font-size:0.8rem;">Add images to album</span>
                            </div>
                            <div id="new-images-preview" class="upload-grid mt-2"></div>
                        </div>
                    </div>
                </div>

                <div class="meta-box" style="border-color:#dcdcde;">
                    <div class="meta-box-header" style="background:#f6f7f7;">Description</div>
                    <div class="meta-box-body p-0" style="border:none;">
                        <textarea name="description" id="description" class="form-control" rows="8" style="border:none;border-radius:0;resize:vertical;padding:12px;">{{ old('description', $album->description) }}</textarea>
                    </div>
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
                                <option value="draft" @selected($album->status === 'draft')>Draft</option>
                                <option value="published" @selected($album->status === 'published')>Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">Album Cover</div>
                    <div class="meta-box-body text-center">
                        <div id="cover-preview" class="mb-2" @if(!$album->featured_image)style="display:none;"@endif>
                            <img id="cover-preview-img" src="{{ $album->featured_image ? asset($album->featured_image) : '' }}" alt="" style="max-width:100%;max-height:160px;border-radius:6px;">
                            <div class="d-flex gap-2 justify-content-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('cover-input').click()"><i class="fas fa-exchange-alt me-1"></i> Change</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearCover()"><i class="fas fa-trash me-1"></i> Remove</button>
                            </div>
                        </div>
                        <div id="cover-placeholder" class="wp-image-placeholder" @if($album->featured_image)style="display:none;"@endif onclick="document.getElementById('cover-input').click()">
                            <i class="fas fa-image"></i>
                            <span>Set cover image</span>
                        </div>
                        <input type="file" name="featured_image" id="cover-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;" onchange="previewCover(this)">
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">SEO</div>
                    <div class="meta-box-body">
                        <div class="meta-field">
                            <label>Meta Description</label>
                            <textarea name="meta_description" class="form-control form-control-sm" rows="3" maxlength="500" placeholder="Brief description for search engines...">{{ old('meta_description', $album->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_scripts')
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

    // Simple drag-to-reorder using native HTML5 drag
    var grid = document.getElementById('images-grid');
    new Sortable(grid, {
        animation: 150,
        handle: '.sort-handle',
        onUpdate: function() {
            updateSortOrder();
        }
    });
});

function updateSortOrder() {
    var grid = document.getElementById('images-grid');
    var thumbs = grid.querySelectorAll('.img-thumb');
    thumbs.forEach(function(thumb, index) {
        var id = thumb.dataset.id;
        if (id) {
            var input = document.querySelector('input[name="sort_order[' + id + ']"]');
            if (input) input.value = index;
        }
    });
}

let fileIndex = 0;
function previewNewImages(input) {
    if (!input.files) return;
    var container = document.getElementById('new-images-preview');
    for (var i = 0; i < input.files.length; i++) {
        var file = input.files[i];
        var reader = new FileReader();
        reader.onload = (function(f) {
            return function(e) {
                var idx = fileIndex++;
                var div = document.createElement('div');
                div.className = 'img-thumb';
                div.innerHTML = '<img src="' + e.target.result + '" alt="' + f.name + '">'
                    + '<div class="thumb-label">' + f.name + '</div>';
                container.appendChild(div);
            };
        })(file);
        reader.readAsDataURL(file);
    }
    if (input.files.length > 0) {
        document.getElementById('add-more-btn').style.display = 'none';
    }
}

function removeExisting(id, btn) {
    if (!confirm('Remove this image from the album?')) return;
    var thumb = btn.closest('.img-thumb');
    thumb.style.transition = 'opacity 0.3s';
    thumb.style.opacity = '0.3';
    // Remove hidden inputs
    var existingInput = document.querySelector('input[name="existing_images[]"][value="' + id + '"]');
    if (existingInput) existingInput.remove();
    var sortInput = document.querySelector('input[name="sort_order[' + id + ']"]');
    if (sortInput) sortInput.remove();
    thumb.remove();
}

function previewCover(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview').style.display = 'block';
            document.getElementById('cover-preview-img').src = e.target.result;
            document.getElementById('cover-placeholder').style.display = 'none';
            document.getElementById('remove-featured').value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearCover() {
    document.getElementById('cover-preview').style.display = 'none';
    document.getElementById('cover-placeholder').style.display = 'flex';
    document.getElementById('cover-input').value = '';
    document.getElementById('remove-featured').value = '1';
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endsection