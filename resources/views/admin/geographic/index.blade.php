@extends('admin.layouts.app', ['title' => 'Geographic Management', 'active_page' => 'geographic'])

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Geographic Management</h1>
            <p class="text-muted mb-0">Manage South Sudan's electoral geographic hierarchy</p>
        </div>
        <div>
            <a href="{{ route('admin.geographic.overview') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-chart-bar me-1"></i> Overview Dashboard
            </a>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="row g-3 mb-4">
        @php
        $stats = [
            ['label' => 'Regions', 'value' => $totals['regions'], 'icon' => 'fa-globe-africa', 'color' => '#0d6efd'],
            ['label' => 'States', 'value' => $totals['states'], 'icon' => 'fa-map', 'color' => '#00914c'],
            ['label' => 'Administrative Areas', 'value' => $totals['admin_areas'], 'icon' => 'fa-map', 'color' => '#fd7e14'],
            ['label' => 'Counties', 'value' => $totals['counties'], 'icon' => 'fa-building', 'color' => '#198754'],
            ['label' => 'Constituencies', 'value' => $totals['constituencies'], 'icon' => 'fa-vote-yea', 'color' => '#20c997'],
            ['label' => 'Payams', 'value' => $totals['payams'], 'icon' => 'fa-sitemap', 'color' => '#ffc107'],
            ['label' => 'Bomas', 'value' => $totals['bomas'], 'icon' => 'fa-home', 'color' => '#fd7e14'],
            ['label' => 'Polling Stations', 'value' => $totals['polling_stations'], 'icon' => 'fa-map-pin', 'color' => '#dc3545'],
        ];
        @endphp
        @foreach($stats as $s)
        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid {{ $s['color'] }} !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:{{ $s['color'] }}15;display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4" style="color:{{ $s['color'] }};">{{ $s['value'] }}</div>
                            <div class="small text-muted">{{ $s['label'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Regions & States --}}
    <div class="row g-4">
        @foreach($regions as $region)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-globe-africa text-success me-2"></i>{{ $region->name }}
                    </h5>
                    <span class="badge bg-success" style="font-size:0.7rem;">{{ $region->states_count ?? 0 }} states</span>
                </div>
                <div class="card-body p-0">
                    @if($region->states && count($region->states) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="small fw-semibold">State</th>
                                    <th class="small fw-semibold text-center">Counties</th>
                                    <th class="small fw-semibold text-center">Constituencies</th>
                                    <th class="small fw-semibold text-center">Stations</th>
                                    <th class="small fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($region->states as $state)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $state->name }}
                                            @if(($state->type ?? '') === 'admin_area') <span class="badge bg-warning text-dark ms-1" style="font-size:8px;">ADMIN AREA</span> @endif
                                        </div>
                                        <small class="text-muted">{{ $state->code }} · {{ $state->capital }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $state->counties_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $state->constituencies_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">{{ $state->polling_stations_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.geographic.state', $state->id) }}" class="btn btn-sm btn-outline-success" title="Manage">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <div class="small">No states in this region</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Add State Button --}}
    <div class="text-center mt-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStateModal">
            <i class="fas fa-plus me-1"></i> Add New State
        </button>
    </div>
</div>

{{-- Add State Modal --}}
<div class="modal fade" id="addStateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.geographic.state.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New State</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
                        <select name="region_id" class="form-select" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">State Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" required maxlength="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Capital</label>
                        <input type="text" name="capital" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create State</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
