@extends('layouts.app', ['title' => 'Voter Statistics by Location', 'active_page' => 'reports', 'meta_description' => 'Official NEC voter registration statistics broken down by state, county, constituency, and polling station.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Voter Statistics by Location</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.annual') }}" class="text-white-50">Reports</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Voter Statistics</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-chart-bar text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container py-5">
    <div class="row g-4">

        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-2" style="color:var(--nec-green-dark)">Filter by Location</h5>
                    <p class="small text-muted">Select a location level to drill into the voters' register.</p>
                    <form method="GET" action="{{ route('reports.voter-stats') }}">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">State</label>
                            <select name="state" class="form-select" onchange="this.form.submit()">
                                <option value="">All States</option>
                                @foreach($states as $s)
                                <option value="{{ $s }}" @selected(request('state') === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(count($counties))
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">County</label>
                            <select name="county" class="form-select" onchange="this.form.submit()">
                                <option value="">All Counties</option>
                                @foreach($counties as $c)
                                <option value="{{ $c }}" @selected(request('county') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(count($constituencies))
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Constituency</label>
                            <select name="constituency" class="form-select" onchange="this.form.submit()">
                                <option value="">All Constituencies</option>
                                @foreach($constituencies as $c)
                                <option value="{{ $c }}" @selected(request('constituency') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(request()->query())
                        <a href="{{ route('reports.voter-stats') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-times me-1"></i> Clear Filters</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color:var(--nec-green-dark)">
                        <i class="fas fa-download me-2"></i>Download
                    </h5>
                    <a href="{{ route('reports.voter-stats.export', request()->query()) }}" class="btn btn-success w-100 rounded-3">
                        <i class="fas fa-file-csv me-1"></i> Export Statistics (CSV)
                    </a>
                    <p class="small text-muted mt-3 mb-0">CSV contains aggregated counts only — no personal data.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            @php
                $totals = [
                    'registered' => $rows->sum('total_registered'),
                    'eligible' => $rows->sum('eligible'),
                    'pre_registered' => $rows->sum('pre_registered'),
                    'deceased' => $rows->sum('deceased'),
                ];
                $groupLabel = ['state' => 'State', 'county' => 'County', 'constituency' => 'Constituency', 'polling_station' => 'Polling Station'][$groupColumn];
            @endphp

            <div class="row g-3 mb-4">
                @php $tiles = [
                    ['label' => 'Registered Voters', 'value' => number_format($totals['registered']), 'icon' => 'fas fa-users', 'style' => 'var(--nec-green)'],
                    ['label' => 'Eligible to Vote', 'value' => number_format($totals['eligible']), 'icon' => 'fas fa-check-circle', 'style' => '#166534'],
                    ['label' => 'Pre-Registered', 'value' => number_format($totals['pre_registered']), 'icon' => 'fas fa-user-clock', 'style' => '#b45309'],
                    ['label' => 'Deceased Records', 'value' => number_format($totals['deceased']), 'icon' => 'fas fa-heart-crack', 'style' => '#6b7280'],
                ]; @endphp
                @foreach($tiles as $tile)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div style="width:42px;height:42px;border-radius:10px;background:{{ $tile['style'] }}22;color:{{ $tile['style'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="{{ $tile['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:1.25rem;line-height:1.1;">{{ $tile['value'] }}</div>
                                <div class="small text-muted">{{ $tile['label'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold" style="color:var(--nec-green-dark)">
                        <i class="fas fa-map-marked-alt me-2"></i>{{ $groupLabel }} Breakdown
                        <span class="badge bg-light text-secondary ms-2">{{ number_format(count($rows)) }} {{ Str::plural('row', count($rows)) }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(count($rows))
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:180px">{{ $groupLabel }}</th>
                                    <th class="text-end">Registered</th>
                                    <th class="text-end">Eligible</th>
                                    <th class="text-end">Pre-Reg.</th>
                                    <th class="text-end">Pending</th>
                                    <th class="text-end">Deceased</th>
                                    <th class="text-end">Male</th>
                                    <th class="text-end">Female</th>
                                    <th class="text-end">Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row->name }}</td>
                                    <td class="text-end">{{ number_format($row->total_registered) }}</td>
                                    <td class="text-end" style="color:#166534;font-weight:600;">{{ number_format($row->eligible) }}</td>
                                    <td class="text-end" style="color:#b45309;">{{ number_format($row->pre_registered) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row->pending) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row->deceased) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row->male) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row->female) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row->agent_registered) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-pie text-muted" style="font-size:2.5rem;opacity:.4;"></i>
                        <p class="text-muted mt-3 mb-0">No registered voters match the selected location.</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Eligible</strong> = aged 18+ on election day and not deceased &middot;
                    <strong>Pre-Registered</strong> = under 18, accepted for the next electoral cycle &middot;
                    <strong>Deceased</strong> = vital-records removals (excluded from all totals above the table).
                </div>
            </div>
        </div>

    </div>
</div>
@endsection