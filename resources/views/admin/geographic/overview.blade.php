@extends('admin.layouts.app', ['title' => 'Geographic Overview', 'active_page' => 'geographic'])

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Geographic Overview</h1>
            <p class="text-muted mb-0">South Sudan electoral infrastructure at a glance</p>
        </div>
        <a href="{{ route('admin.geographic.index') }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-arrow-left me-1"></i> Back to Management
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        @php
        $cards = [
            ['label' => 'States', 'value' => $totals['states'], 'color' => '#00914c', 'icon' => 'fa-map'],
            ['label' => 'Counties', 'value' => $totals['counties'], 'color' => '#0d6efd', 'icon' => 'fa-building'],
            ['label' => 'Constituencies', 'value' => $totals['constituencies'], 'color' => '#198754', 'icon' => 'fa-vote-yea'],
            ['label' => 'Payams', 'value' => $totals['payams'], 'color' => '#ffc107', 'icon' => 'fa-sitemap'],
            ['label' => 'Bomas', 'value' => $totals['bomas'], 'color' => '#fd7e14', 'icon' => 'fa-home'],
            ['label' => 'Polling Stations', 'value' => $totals['polling_stations'], 'color' => '#dc3545', 'icon' => 'fa-map-pin'],
            ['label' => 'Registered Voters', 'value' => number_format($totals['registered_voters']), 'color' => '#6f42c1', 'icon' => 'fa-users'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <i class="fas {{ $c['icon'] }} fa-2x mb-2" style="color:{{ $c['color'] }};"></i>
                    <div class="fw-bold fs-3" style="color:{{ $c['color'] }};">{{ $c['value'] }}</div>
                    <div class="small text-muted">{{ $c['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- States Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">All States</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold">State</th>
                            <th class="small fw-semibold">Region</th>
                            <th class="small fw-semibold text-center">Counties</th>
                            <th class="small fw-semibold text-center">Constituencies</th>
                            <th class="small fw-semibold text-center">Payams</th>
                            <th class="small fw-semibold text-center">Bomas</th>
                            <th class="small fw-semibold text-center">Stations</th>
                            <th class="small fw-semibold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $state)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $state->name }}</div>
                                <small class="text-muted">{{ $state->code }} · {{ $state->capital }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $state->region->name ?? '-' }}</span></td>
                            <td class="text-center fw-semibold">{{ $state->counties_count ?? 0 }}</td>
                            <td class="text-center fw-semibold">{{ $state->constituencies_count ?? 0 }}</td>
                            <td class="text-center fw-semibold">{{ $state->payams_count ?? 0 }}</td>
                            <td class="text-center fw-semibold">{{ $state->bomas_count ?? 0 }}</td>
                            <td class="text-center fw-semibold">{{ $state->polling_stations_count ?? 0 }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.geographic.state', $state->id) }}" class="btn btn-sm btn-outline-success" title="Manage">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No states found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
