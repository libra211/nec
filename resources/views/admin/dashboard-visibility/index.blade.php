@extends('admin.layouts.app', ['title' => 'Dashboard Visibility'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-0 fw-bold" style="font-size:20px;"><i class="fas fa-gauge-high me-2 text-primary"></i>Dashboard Visibility</h2>
        <p class="text-muted small mb-0 mt-1">Choose which stats and sections each role sees on their dashboard</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.dashboard-visibility.update') }}">
    @csrf
    @method('PUT')

    <div class="accordion" id="visAccordion">
    @foreach($catalog as $role => $items)
        <div class="accordion-item mb-2 border rounded-3" style="overflow:hidden;">
            <h2 class="accordion-header" id="h-{{ $role }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ $role }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="c-{{ $role }}">
                    <i class="fas fa-user-shield me-2" style="color:var(--nec-blue);"></i>
                    <span class="fw-semibold">{{ \App\Helpers\DashboardItems::roleLabel($role) }}</span>
                    <span class="badge bg-light text-dark ms-2">{{ count($items) }} sections</span>
                </button>
            </h2>
            <div id="c-{{ $role }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="h-{{ $role }}" data-bs-parent="#visAccordion">
                <div class="accordion-body">
                    <div class="form-check form-switch d-flex align-items-center mb-2 ps-0">
                        <input class="form-check-input me-2" type="checkbox" id="all-{{ $role }}"
                               onchange="toggleAll('{{ $role }}', this.checked)">
                        <label class="form-check-label fw-semibold text-muted small" for="all-{{ $role }}">Enable all</label>
                    </div>
                    <div class="row g-2">
                    @foreach($items as $key => $label)
                        @php $checked = $enabledByRole[$role][$key] ?? true; @endphp
                        <div class="col-md-6">
                            <div class="border rounded-3 p-2 d-flex align-items-center" style="background:#fbfbfc;">
                                <label class="form-check-label flex-grow-1" for="{{ $role }}-{{ $key }}" style="font-size:0.85rem;">
                                    <i class="fas fa-check-circle me-1" style="color:{{ $checked ? 'var(--nec-green)' : '#c0c0c0' }};"></i>{{ $label }}
                                </label>
                                <div class="form-check form-switch mb-0 ms-2">
                                    <input class="form-check-input role-toggle-{{ $role }}" type="checkbox"
                                           id="{{ $role }}-{{ $key }}" name="roles[{{ $role }}][{{ $key }}]"
                                           value="1" {{ $checked ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Visibility Settings</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

@section('extra_scripts')
<script>
    function toggleAll(role, checked) {
        document.querySelectorAll('.role-toggle-' + role).forEach(function (el) {
            el.checked = checked;
        });
    }
</script>
@endsection
