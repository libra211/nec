@extends('admin.layouts.app', ['title' => $state->name . ' — Geographic Management', 'active_page' => 'geographic'])

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.geographic.index') }}" class="text-success text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> All States</a>
                <span class="text-muted small">/</span>
                <span class="text-muted small">{{ $state->region->name }}</span>
            </div>
            <h1 class="h3 fw-bold mb-0">{{ $state->name }} <span class="text-muted fw-normal" style="font-size:0.7em;">{{ $state->code }}</span>
                @if(($state->type ?? '') === 'admin_area') <span class="badge bg-warning text-dark ms-2" style="font-size:0.5em;">ADMINISTRATIVE AREA</span> @endif
            </h1>
            <p class="text-muted mb-0">Capital: {{ $state->capital }} · {{ $state->region->name }}</p>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editStateModal">
                <i class="fas fa-edit me-1"></i> Edit State
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
        $stateStats = [
            ['label' => 'Counties', 'value' => $totals['counties'], 'color' => '#0d6efd', 'icon' => 'fa-building'],
            ['label' => 'Constituencies', 'value' => $totals['constituencies'], 'color' => '#198754', 'icon' => 'fa-vote-yea'],
            ['label' => 'Payams', 'value' => $totals['payams'], 'color' => '#ffc107', 'icon' => 'fa-sitemap'],
            ['label' => 'Bomas', 'value' => $totals['bomas'], 'color' => '#fd7e14', 'icon' => 'fa-home'],
            ['label' => 'Polling Stations', 'value' => $totals['polling_stations'], 'color' => '#dc3545', 'icon' => 'fa-map-pin'],
            ['label' => 'Reg. Voters', 'value' => number_format($totals['registered_voters']), 'color' => '#6f42c1', 'icon' => 'fa-users'],
        ];
        @endphp
        @foreach($stateStats as $s)
        <div class="col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <i class="fas {{ $s['icon'] }} fa-lg mb-2" style="color:{{ $s['color'] }};"></i>
                    <div class="fw-bold fs-4" style="color:{{ $s['color'] }};">{{ $s['value'] }}</div>
                    <div class="small text-muted">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- Counties --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Counties ({{ $counties->count() }})</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addCountyModal">
                        <i class="fas fa-plus me-1"></i> Add
                    </button>
                </div>
                <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                    @forelse($counties as $county)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $county->name }}</div>
                            <small class="text-muted">{{ $county->constituencies_count ?? 0 }} constituencies · {{ $county->polling_stations_count ?? 0 }} stations</small>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="editCounty({{ $county->id }}, '{{ $county->name }}', '{{ $county->capital }}', '{{ $county->latitude }}', '{{ $county->longitude }}', '{{ $county->registered_voters }}')">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.geographic.county.destroy', $county->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this county?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">No counties found</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Constituencies --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Constituencies ({{ $constituencies->count() }})</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addConstituencyModal">
                        <i class="fas fa-plus me-1"></i> Add
                    </button>
                </div>
                <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                    @forelse($constituencies as $c)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $c->name }} <span class="text-muted">{{ $c->code }}</span></div>
                            <small class="text-muted">{{ $c->polling_stations_count ?? 0 }} polling stations</small>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="editConstituency({{ $c->id }}, '{{ $c->name }}', '{{ $c->code }}', '{{ $c->latitude }}', '{{ $c->longitude }}')">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.geographic.constituency.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this constituency?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">No constituencies found</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Polling Stations --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">Polling Stations ({{ $pollingStations->total() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">STATION</th>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">CONSTITUENCY</th>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">TYPE</th>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">VOTERS</th>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">GPS</th>
                                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pollingStations as $station)
                                <tr style="border-bottom:1px solid #f1f3f5;">
                                    <td style="padding:10px 8px 10px 16px;color:#1e293b;font-weight:600;">{{ $station->name }}</td>
                                    <td style="padding:10px 12px;color:#475569;">{{ $station->constituency->name ?? '-' }}</td>
                                    <td style="padding:10px 12px;color:#475569;text-align:center;"><span class="badge bg-light text-dark">{{ $station->station_type ?? 'Standard' }}</span></td>
                                    <td style="padding:10px 12px;color:#475569;text-align:center;">{{ number_format($station->registered_voters ?? 0) }}</td>
                                    <td style="padding:10px 12px;color:#475569;text-align:center;">
                                        @if($station->latitude && $station->longitude)
                                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                        @else
                                        <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 16px 10px 12px;text-align:right;">
                                        <a href="{{ route('admin.polling-stations.edit', $station->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(220,53,69,0.08);">
                                                <i class="fas fa-map-pin" style="color:#dc3545;font-size:1.25rem;"></i>
                                            </div>
                                            <p class="text-muted mb-0">No polling stations found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pollingStations->hasPages() || $pollingStations->total() > 0)
                    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
                        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $pollingStations->firstItem() }} to {{ $pollingStations->lastItem() }} of {{ $pollingStations->total() }} polling stations</span>
                        {{ $pollingStations->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit State Modal --}}
<div class="modal fade" id="editStateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.geographic.state.update', $state->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit {{ $state->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">State Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $state->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Capital</label>
                        <input type="text" name="capital" class="form-control" value="{{ $state->capital }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control" value="{{ $state->latitude }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control" value="{{ $state->longitude }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add County Modal --}}
<div class="modal fade" id="addCountyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.geographic.county.store') }}" method="POST">
                @csrf
                <input type="hidden" name="state_id" value="{{ $state->id }}">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add County to {{ $state->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">County Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Registered Voters</label>
                        <input type="number" name="registered_voters" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create County</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit County Modal --}}
<div class="modal fade" id="editCountyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCountyForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit County</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">County Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editCountyName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Capital</label>
                        <input type="text" name="capital" id="editCountyCapital" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" id="editCountyLat" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" id="editCountyLng" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Registered Voters</label>
                        <input type="number" name="registered_voters" id="editCountyVoters" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Constituency Modal --}}
<div class="modal fade" id="addConstituencyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.geographic.constituency.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Constituency to {{ $state->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">County <span class="text-danger">*</span></label>
                        <select name="county_id" class="form-select" required>
                            <option value="">Select County</option>
                            @foreach($counties as $county)
                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Constituency Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" name="code" class="form-control" maxlength="20">
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
                    <button type="submit" class="btn btn-success">Create Constituency</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Constituency Modal --}}
<div class="modal fade" id="editConstituencyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editConstituencyForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Constituency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Constituency Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editConstituencyName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" name="code" id="editConstituencyCode" class="form-control" maxlength="20">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" id="editConstituencyLat" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" id="editConstituencyLng" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCounty(id, name, capital, lat, lng, voters) {
    document.getElementById('editCountyForm').action = '/admin/geographic/counties/' + id;
    document.getElementById('editCountyName').value = name;
    document.getElementById('editCountyCapital').value = capital || '';
    document.getElementById('editCountyLat').value = lat || '';
    document.getElementById('editCountyLng').value = lng || '';
    document.getElementById('editCountyVoters').value = voters || '';
    new bootstrap.Modal(document.getElementById('editCountyModal')).show();
}

function editConstituency(id, name, code, lat, lng) {
    document.getElementById('editConstituencyForm').action = '/admin/geographic/constituencies/' + id;
    document.getElementById('editConstituencyName').value = name;
    document.getElementById('editConstituencyCode').value = code || '';
    document.getElementById('editConstituencyLat').value = lat || '';
    document.getElementById('editConstituencyLng').value = lng || '';
    new bootstrap.Modal(document.getElementById('editConstituencyModal')).show();
}
</script>
@endpush
