@extends('admin.layouts.app', ['title' => 'Add New Gallery Album'])

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
.upload-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(100px,1fr)); gap:8px; margin-top:8px; }
.upload-grid .img-thumb { position:relative; border-radius:6px; overflow:hidden; border:1px solid #dcdcde; }
.upload-grid .img-thumb img { width:100%; height:80px; object-fit:cover; display:block; }
.upload-grid .img-thumb .remove-btn { position:absolute; top:2px; right:2px; width:20px; height:20px; border-radius:50%; background:rgba(0,0,0,0.6); color:#fff; border:none; font-size:0.6rem; display:flex; align-items:center; justify-content:center; cursor:pointer; }
@media (max-width:768px) { #wp-editor-grid { flex-direction:column; } #wp-editor-sidebar { width:100%; } }
</style>
@endsection

@section('content')
<div id="wp-editor">
    <div id="wp-admin-bar">
        <div class="wp-bar-left">
            <a href="{{ route('admin.gallery.index') }}" class="wp-back-btn"><i class="fas fa-chevron-left"></i></a>
            <h1>Add New Gallery Album</h1>
            <span class="wp-status-badge draft">Draft</span>
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

    <form id="album-form" action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="status-input" value="draft">

        <div id="wp-editor-grid">
            <div id="wp-editor-main">
                <div id="titlediv">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Album title" autocomplete="off" oninput="autoSlug()" required>
                </div>

                <div id="edit-slug-box">
                    <strong>Permalink:</strong>
                    <span class="slug-prefix">{{ url('/gallery') }}/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="auto-generated" class="slug-field">
                    <button type="button" class="btn btn-sm btn-link" onclick="document.getElementById('slug').dataset.modified='1';document.getElementById('slug').focus()">Edit</button>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">Album Images</div>
                    <div class="meta-box-body">
                        <div class="meta-field">
                            <label>Upload Images</label>
                            <input type="file" name="images[]" id="images-input" multiple accept=".jpg,.jpeg,.png,.webp,.gif" style="display:none;" onchange="previewImages(this)">
                            <div id="images-placeholder" class="wp-image-placeholder" onclick="document.getElementById('images-input').click()">
                                <i class="fas fa-images"></i>
                                <span>Click to select images</span>
                                <small style="font-size:0.65rem;color:#9ca0a4;">JPG, PNG, WebP, GIF — max 5MB each</small>
                            </div>
                            <div id="images-preview" class="upload-grid"></div>
                        </div>
                    </div>
                </div>

                <div class="meta-box" style="border-color:#dcdcde;">
                    <div class="meta-box-header" style="background:#f6f7f7;">Description</div>
                    <div class="meta-box-body p-0" style="border:none;">
                        <textarea name="description" id="description" class="form-control" rows="8" style="border:none;border-radius:0;resize:vertical;padding:12px;">{{ old('description') }}</textarea>
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
                                <option value="draft" selected>Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="meta-box">
                    <div class="meta-box-header">Album Cover</div>
                    <div class="meta-box-body text-center">
                        <div id="cover-preview" class="mb-2" style="display:none;">
                            <img id="cover-preview-img" src="" alt="" style="max-width:100%;max-height:160px;border-radius:6px;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearCover()"><i class="fas fa-trash me-1"></i> Remove</button>
                        </div>
                        <div id="cover-placeholder" class="wp-image-placeholder" onclick="document.getElementById('cover-input').click()">
                            <i class="fas fa-image"></i>
                            <span>Set cover image</span>
                        </div>
                        <input type="file" name="featured_image" id="cover-input" accept=".jpg,.jpeg,.png,.webp" style="display:none;" onchange="previewCover(this)">
                        <div class="mt-2 small text-muted">First image is used as cover if not set</div>
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
});
let fileIndex = 0;
function previewImages(input) {
    if (!input.files) return;
    var container = document.getElementById('images-preview');
    for (var i = 0; i < input.files.length; i++) {
        var file = input.files[i];
        var reader = new FileReader();
        reader.onload = (function(f) {
            return function(e) {
                var idx = fileIndex++;
                var div = document.createElement('div');
                div.className = 'img-thumb';
                div.innerHTML = '<img src="' + e.target.result + '" alt="' + f.name + '">'
                    + '<button type="button" class="remove-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
                container.appendChild(div);
            };
        })(file);
        reader.readAsDataURL(file);
    }
    if (input.files.length > 0) {
        document.getElementById('images-placeholder').style.display = 'none';
    }
}
function previewCover(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview').style.display = 'block';
            document.getElementById('cover-preview-img').src = e.target.result;
            document.getElementById('cover-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearCover() {
    document.getElementById('cover-preview').style.display = 'none';
    document.getElementById('cover-placeholder').style.display = 'flex';
    document.getElementById('cover-input').value = '';
}
</script>
@endsection