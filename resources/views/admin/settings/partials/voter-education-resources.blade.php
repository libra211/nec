@php
$cveResources = [
    ['key' => 'baseline_en',   'title' => 'Baseline Survey',              'lang' => 'English', 'icon' => 'fa-chart-bar',      'hint' => 'Cover image for the Baseline Survey (English)'],
    ['key' => 'baseline_ar',   'title' => 'Baseline Survey',              'lang' => 'Arabic',  'icon' => 'fa-chart-bar',      'hint' => 'Cover image for the Baseline Survey (Arabic)'],
    ['key' => 'strategy',      'title' => 'Civic Voter Education Strategy','lang' => 'English', 'icon' => 'fa-route',          'hint' => 'Cover image for the Civic Voter Education Strategy'],
    ['key' => 'curriculum',    'title' => 'Civic Voter Education Curriculum','lang' => 'English', 'icon' => 'fa-book-open',    'hint' => 'Cover image for the Civic Voter Education Curriculum'],
    ['key' => 'manual_en',     'title' => 'Training Manual',              'lang' => 'English', 'icon' => 'fa-chalkboard-teacher', 'hint' => 'Cover image for the Training Manual (English)'],
    ['key' => 'manual_ar',     'title' => 'Training Manual',              'lang' => 'Arabic',  'icon' => 'fa-chalkboard-teacher', 'hint' => 'Cover image for the Training Manual (Arabic)'],
    ['key' => 'booklet_en',    'title' => 'Civic Education Booklet',      'lang' => 'English', 'icon' => 'fa-book',           'hint' => 'Cover image for the Civic Education Booklet (English)'],
    ['key' => 'booklet_ar',    'title' => 'Civic Education Booklet',      'lang' => 'Arabic',  'icon' => 'fa-book',           'hint' => 'Cover image for the Civic Education Booklet (Arabic)'],
];
@endphp

@foreach($cveResources as $res)
@php
    $prefix = 'cve_' . $res['key'];
    $img = $settings[$prefix . '_image']->value ?? '';
    $title = $settings[$prefix . '_title']->value ?? '';
    $desc = $settings[$prefix . '_desc']->value ?? '';
@endphp
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    @method('PUT')
    <input type="hidden" name="_tab" value="voter-education">
    <input type="hidden" name="_section" value="{{ $res['key'] }}">

    <div class="settings-block">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="settings-block-title mb-0">
                <i class="fas {{ $res['icon'] }} text-primary me-1"></i>{{ $res['title'] }}
                <span class="badge bg-light text-muted fw-normal ms-1" style="font-size:10px;">{{ $res['lang'] }}</span>
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fas fa-save me-1"></i> Save Section</button>
        </div>
        <div class="settings-block-desc">{{ $res['hint'] }}</div>
        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="settings-field">
                    <label class="settings-field-label">Section Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="settings-field-hint">JPG, PNG, WebP (max 2MB). Recommended: 640&times;400px</div>
                    @if($img)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ $img }}" alt="{{ $res['title'] }}" class="border rounded" style="height:60px;object-fit:cover;">
                        <label class="form-check small text-muted mb-0">
                            <input type="checkbox" name="remove_image" value="1" class="form-check-input"> Remove
                        </label>
                    </div>
                    @else
                    <div class="mt-2 text-muted" style="font-size:12px;"><i class="fas fa-image me-1"></i>No image uploaded yet</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="settings-field">
                    <label class="settings-field-label">Card Title (optional)</label>
                    <input type="text" name="title" class="form-control" value="{{ $title }}" maxlength="200" placeholder="e.g. {{ $res['title'] }}">
                </div>
            </div>
            <div class="col-12">
                <div class="settings-field">
                    <label class="settings-field-label">Card Description (optional)</label>
                    <textarea name="desc" class="form-control" rows="2" maxlength="500" placeholder="Short description shown under the image">{{ $desc }}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>
@endforeach
