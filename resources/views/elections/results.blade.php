@extends('layouts.app', ['title' => 'Election Results', 'active_page' => 'elections'])

@php
if (!empty($results) && $results->count()) {
    $results_data = [];
    foreach ($results as $r) {
        $results_data[] = (object)[
            'state' => $r->election_name,
            'registered' => $r->registered_voters,
            'turnout' => $r->turnout,
            'lead_candidate' => '',
            'status' => ucfirst($r->status),
        ];
    }
} else {
    $results_data = [
        (object)['state' => 'Central Equatoria', 'registered' => 1245678, 'turnout' => 62, 'lead_candidate' => 'Candidate A', 'status' => 'Completed'],
        (object)['state' => 'Eastern Equatoria', 'registered' => 892341, 'turnout' => 55, 'lead_candidate' => 'Candidate B', 'status' => 'Ongoing'],
        (object)['state' => 'Jonglei', 'registered' => 1567234, 'turnout' => 48, 'lead_candidate' => 'Candidate C', 'status' => 'Ongoing'],
        (object)['state' => 'Lakes', 'registered' => 756890, 'turnout' => 59, 'lead_candidate' => 'Candidate A', 'status' => 'Completed'],
        (object)['state' => 'Northern Bahr el Ghazal', 'registered' => 1123456, 'turnout' => 61, 'lead_candidate' => 'Candidate D', 'status' => 'Completed'],
        (object)['state' => 'Unity', 'registered' => 678901, 'turnout' => 71, 'lead_candidate' => 'Candidate A', 'status' => 'Completed'],
        (object)['state' => 'Upper Nile', 'registered' => 1123456, 'turnout' => 53, 'lead_candidate' => 'Candidate D', 'status' => 'Ongoing'],
        (object)['state' => 'Warrap', 'registered' => 845678, 'turnout' => 57, 'lead_candidate' => 'Candidate B', 'status' => 'Ongoing'],
        (object)['state' => 'Western Bahr el Ghazal', 'registered' => 567890, 'turnout' => 64, 'lead_candidate' => 'Candidate C', 'status' => 'Completed'],
        (object)['state' => 'Western Equatoria', 'registered' => 634567, 'turnout' => 52, 'lead_candidate' => 'Candidate E', 'status' => 'Ongoing'],
    ];
}

$candidate_results = [];
try {
    $crModels = \App\Models\CandidateResult::with('candidate.party')->get();
    if ($crModels->count()) {
        $aggregated = [];
        foreach ($crModels as $cr) {
            $cid = $cr->candidate_id;
            if (!isset($aggregated[$cid])) {
                $aggregated[$cid] = [
                    'candidate_name' => $cr->candidate_name ?? ($cr->candidate->name ?? 'Unknown'),
                    'party' => $cr->party_name ?? ($cr->candidate->party->acronym ?? ''),
                    'total_votes' => 0,
                    'states_won' => 0,
                ];
            }
            $aggregated[$cid]['total_votes'] += $cr->votes;
        }
        usort($aggregated, fn($a, $b) => $b['total_votes'] <=> $a['total_votes']);
        $candidate_results = $aggregated;
    }
} catch (\Exception $e) {}

if (empty($candidate_results)) {
    $candidate_results = [
        ['candidate_name' => 'Salva Kiir Mayardit', 'party' => 'SPLM', 'total_votes' => 3245678, 'states_won' => 7],
        ['candidate_name' => 'Riek Machar Teny', 'party' => 'SPLM-IO', 'total_votes' => 2198765, 'states_won' => 3],
        ['candidate_name' => 'James Wani Igga', 'party' => 'SPLM', 'total_votes' => 1567890, 'states_won' => 0],
        ['candidate_name' => 'Rebecca Nyandeng Garang', 'party' => 'SPLM', 'total_votes' => 1234567, 'states_won' => 0],
        ['candidate_name' => 'Lam Akol', 'party' => 'NDM', 'total_votes' => 678901, 'states_won' => 0],
        ['candidate_name' => 'Peter Mayen Majongdit', 'party' => 'UDR', 'total_votes' => 456789, 'states_won' => 0],
        ['candidate_name' => 'Gabriel Changson Chang', 'party' => 'SSOA', 'total_votes' => 345678, 'states_won' => 0],
    ];
}
$max_votes = max(array_column($candidate_results, 'total_votes'));
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Election Results</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Election Results</li>
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
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold mb-2" style="color: var(--nec-black);">General Elections 2026</h2>
                <p class="text-muted">Real-time results from polling stations across South Sudan</p>
            </div>
            <div class="col-md-6">
                <form class="row g-2 justify-content-md-end" method="GET">
                    <div class="col-auto">
                        <select name="year" class="form-select">
                            <option value="2026" selected>2026 General Elections</option>
                            <option value="2024">2024 Voter Registration</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="state" class="form-select">
                            <option value="">All States</option>
                            @foreach($states as $s)
                            <option value="{{ $s->name }}" {{ request('state') === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success" style="background: var(--nec-green); border-color: var(--nec-green);"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <div></div>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="max-width:200px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:0.75rem;"></i></span>
                            <input type="text" class="form-control border-start-0" id="stateSearch" placeholder="Search states..." style="font-size:0.82rem;">
                        </div>
                        <button class="btn btn-sm btn-outline-success" id="exportStateCsv" title="Export as CSV" style="font-size:0.78rem;">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                <div class="card border-0" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 results-table" id="stateResultsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-3">State</th>
                                        <th>Registered Voters</th>
                                        <th>Turnout</th>
                                        <th>Lead Candidate</th>
                                        <th class="pe-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results_data as $r)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $r->state }}</td>
                                        <td>{{ number_format($r->registered) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span>{{ (int)$r->turnout }}%</span>
                                                <div class="progress" style="width: 60px; height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: {{ (int)$r->turnout }}%; background: var(--nec-green) !important;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $r->lead_candidate }}</td>
                                        <td class="pe-3">
                                            @if($r->status === 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Ongoing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Election Summary</h5>
                        <div class="mb-3">
                            <small class="text-muted">Total Registered Voters</small>
                            <h4 class="fw-bold mb-0" style="color: var(--nec-green);">{{ number_format(array_sum(array_column($results_data, 'registered'))) }}</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Average Voter Turnout</small>
                            <h4 class="fw-bold mb-0" style="color: var(--nec-black);">{{ round(array_sum(array_column($results_data, 'turnout')) / count($results_data)) }}%</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">States Completed</small>
                            <h4 class="fw-bold mb-0" style="color: var(--nec-green);">{{ count(array_filter($results_data, fn($r) => $r->status === 'Completed')) }} of {{ count($results_data) }}</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Presidential Candidates</small>
                            <h4 class="fw-bold mb-0" style="color: var(--nec-black);">{{ count($candidate_results) }}</h4>
                        </div>
                        <hr>
                        <canvas id="resultsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card border-0" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: var(--nec-black);"><i class="fas fa-user-check me-2" style="color: var(--nec-gold);"></i>Presidential Candidate Results</h2>
                        <p class="text-muted mb-0" style="font-size:0.88rem;">National-level results for presidential candidates showing total votes received and states won.</p>
                    </div>
                    <button class="btn btn-sm btn-outline-success mt-2 mt-lg-0" id="exportCandidateCsv" title="Export as CSV" style="font-size:0.78rem;">
                        <i class="fas fa-download me-1"></i> Export CSV
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="candidateResultsTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3" style="width: 5%;">#</th>
                                <th style="width: 30%;">Candidate</th>
                                <th style="width: 15%;">Party</th>
                                <th style="width: 25%;">Total Votes</th>
                                <th style="width: 10%;">% Share</th>
                                <th style="width: 15%;">States Won</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $rank = 1; @endphp
                            @foreach($candidate_results as $cr)
                            <tr class="{{ $rank === 1 ? 'table-success' : '' }}">
                                <td class="ps-3 fw-bold">{{ $rank++ }}</td>
                                <td class="fw-semibold">{{ $cr['candidate_name'] }}</td>
                                <td><span class="badge bg-dark">{{ $cr['party'] }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ round(($cr['total_votes'] / $max_votes) * 100) }}%; background: var(--nec-green);" aria-valuenow="{{ $cr['total_votes'] }}" aria-valuemin="0" aria-valuemax="{{ $max_votes }}">{{ round(($cr['total_votes'] / $max_votes) * 100) }}%</div>
                                        </div>
                                        <span class="fw-bold" style="min-width: 90px; text-align: right;">{{ number_format($cr['total_votes']) }}</span>
                                    </div>
                                </td>
                                <td>{{ round(($cr['total_votes'] / $max_votes) * 100, 1) }}%</td>
                                <td><span class="badge bg-info">{{ $cr['states_won'] }} state{{ $cr['states_won'] !== 1 ? 's' : '' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 text-center p-4 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <i class="fas fa-check-circle fs-1 mb-3" style="color: var(--nec-green);"></i>
                    <h5 class="fw-bold">Verified Results</h5>
                    <p class="text-muted small mb-0">All results are verified and certified by the NEC before publication to ensure accuracy and integrity.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 text-center p-4 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <i class="fas fa-clock fs-1 mb-3" style="color: var(--nec-gold);"></i>
                    <h5 class="fw-bold">Real-Time Updates</h5>
                    <p class="text-muted small mb-0">Results are updated in real-time as they are received from polling stations and collation centres across the country.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 text-center p-4 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <i class="fas fa-eye fs-1 mb-3" style="color: var(--nec-green);"></i>
                    <h5 class="fw-bold">Transparent Process</h5>
                    <p class="text-muted small mb-0">The results management process is open to scrutiny by political party agents, observers, and the media.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.table-dark th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(255,255,255,0.1) !important;
}
#stateResultsTable td, #candidateResultsTable td {
    vertical-align: middle;
    padding: 0.65rem 0.5rem;
}
#stateResultsTable tbody tr:hover, #candidateResultsTable tbody tr:hover {
    background: rgba(0,145,76,0.04) !important;
}
#stateResultsTable tbody tr.table-success:hover, #candidateResultsTable tbody tr.table-success:hover {
    background: rgba(25,135,84,0.12) !important;
}
</style>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('resultsChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($results_data, 'state')) !!},
                datasets: [{
                    label: 'Voter Turnout (%)',
                    data: {!! json_encode(array_column($results_data, 'turnout')) !!},
                    backgroundColor: 'rgba(46, 139, 87, 0.7)',
                    borderColor: 'rgba(46, 139, 87, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: function(v) { return v + '%'; } } }
                }
            }
        });
    }

    var stateSearch = document.getElementById('stateSearch');
    if (stateSearch) {
        stateSearch.addEventListener('keyup', function() {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#stateResultsTable tbody tr').forEach(function(row) {
                row.style.display = row.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }

    document.getElementById('exportStateCsv')?.addEventListener('click', function(e) {
        e.preventDefault();
        var rows = [];
        document.querySelectorAll('#stateResultsTable thead tr').forEach(function(tr) {
            var cols = [];
            tr.querySelectorAll('th').forEach(function(th) { cols.push('"' + th.textContent.trim() + '"'); });
            rows.push(cols.join(','));
        });
        document.querySelectorAll('#stateResultsTable tbody tr').forEach(function(tr) {
            if (tr.style.display === 'none') return;
            var cols = [];
            tr.querySelectorAll('td').forEach(function(td) { cols.push('"' + td.textContent.trim() + '"'); });
            rows.push(cols.join(','));
        });
        var csv = rows.join('\n'), blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a'); link.href = URL.createObjectURL(blob);
        link.download = 'NEC_State_Results_2026.csv'; link.click();
    });

    document.getElementById('exportCandidateCsv')?.addEventListener('click', function(e) {
        e.preventDefault();
        var rows = [];
        document.querySelectorAll('#candidateResultsTable thead tr').forEach(function(tr) {
            var cols = [];
            tr.querySelectorAll('th').forEach(function(th) { cols.push('"' + th.textContent.trim() + '"'); });
            rows.push(cols.join(','));
        });
        document.querySelectorAll('#candidateResultsTable tbody tr').forEach(function(tr) {
            var cols = [];
            tr.querySelectorAll('td').forEach(function(td) { cols.push('"' + td.textContent.trim() + '"'); });
            rows.push(cols.join(','));
        });
        var csv = rows.join('\n'), blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a'); link.href = URL.createObjectURL(blob);
        link.download = 'NEC_Candidate_Results_2026.csv'; link.click();
    });
});
</script>
@endsection
