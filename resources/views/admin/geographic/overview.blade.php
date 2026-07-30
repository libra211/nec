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
            ['label' => 'Admin Areas', 'value' => $totals['admin_areas'], 'color' => '#fd7e14', 'icon' => 'fa-map'],
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
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">States ({{ $totals['states'] }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">STATE</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">REGION</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">COUNTIES</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">CONSTITUENCIES</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">PAYAMS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">BOMAS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">STATIONS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $state)
                        <tr style="border-bottom:1px solid #f1f3f5;">
                            <td style="padding:10px 8px 10px 16px;color:#1e293b;">
                                <div class="fw-semibold">{{ $state->name }}</div>
                                <small style="color:#64748b;">{{ $state->code }} · {{ $state->capital }}</small>
                            </td>
                            <td style="padding:10px 12px;color:#475569;"><span class="badge bg-light text-dark">{{ $state->region->name ?? '-' }}</span></td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $state->counties_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $state->constituencies_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $state->payams_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $state->bomas_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $state->polling_stations_count ?? 0 }}</td>
                            <td style="padding:10px 16px 10px 12px;text-align:right;">
                                <a href="{{ route('admin.geographic.state', $state->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Manage">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(108,117,125,0.08);">
                                        <i class="fas fa-map" style="color:#6c757d;font-size:1.25rem;"></i>
                                    </div>
                                    <p class="text-muted mb-0">No states found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Admin Areas Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">Administrative Areas ({{ $totals['admin_areas'] }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">ADMINISTRATIVE AREA</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">REGION</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">COUNTIES</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">CONSTITUENCIES</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">PAYAMS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">BOMAS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">STATIONS</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminAreas as $area)
                        <tr style="border-bottom:1px solid #f1f3f5;">
                            <td style="padding:10px 8px 10px 16px;color:#1e293b;">
                                <div class="fw-semibold">{{ $area->name }} <span class="badge bg-warning text-dark ms-1" style="font-size:8px;">ADMIN AREA</span></div>
                                <small style="color:#64748b;">{{ $area->code }} · {{ $area->capital }}</small>
                            </td>
                            <td style="padding:10px 12px;color:#475569;"><span class="badge bg-light text-dark">{{ $area->region->name ?? '-' }}</span></td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $area->counties_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $area->constituencies_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $area->payams_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $area->bomas_count ?? 0 }}</td>
                            <td style="padding:10px 12px;color:#475569;text-align:center;font-weight:600;">{{ $area->polling_stations_count ?? 0 }}</td>
                            <td style="padding:10px 16px 10px 12px;text-align:right;">
                                <a href="{{ route('admin.geographic.state', $area->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Manage">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(108,117,125,0.08);">
                                        <i class="fas fa-map" style="color:#6c757d;font-size:1.25rem;"></i>
                                    </div>
                                    <p class="text-muted mb-0">No administrative areas found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
