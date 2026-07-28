@extends('layouts.app', ['title' => 'Election Calendar - NEC South Sudan', 'active_page' => 'elections'])

@section('hero')
<section class="page-header" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('elections.index') }}" class="text-white opacity-75">Elections</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Election Calendar</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold text-white mb-2">Election Calendar</h1>
                <p class="text-white opacity-90 lead mb-0">Key dates and milestones for the 2026 General Elections</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-block text-center px-4 py-2 rounded-3" style="background:rgba(255,255,255,0.15);">
                    <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;font-size:0.65rem;">Election Day</div>
                    <div class="text-white fw-bold" style="font-size:1.15rem;">21 Dec 2026</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
if (!empty($events) && $events->count()) {
    $calendar = [];
    $statusMap = ['active' => 'Upcoming', 'inactive' => 'Inactive', 'trash' => 'Trash'];
    $classMap = ['active' => 'bg-success', 'inactive' => 'bg-secondary', 'trash' => 'bg-danger'];
    foreach ($events as $e) {
        $calendar[] = [
            'activity' => $e->title,
            'start' => date('d M Y', strtotime($e->start_date)),
            'end' => $e->end_date ? date('d M Y', strtotime($e->end_date)) : date('d M Y', strtotime($e->start_date)),
            'status' => $statusMap[$e->status] ?? ucfirst($e->status),
            'status_class' => $classMap[$e->status] ?? 'bg-secondary',
        ];
    }
} else {
    $calendar = [
        ['activity' => 'Voter Registration Update', 'start' => '12 Jul 2026', 'end' => '12 Aug 2026', 'status' => 'Upcoming', 'status_class' => 'bg-success'],
        ['activity' => 'Display of Provisional Voters Register', 'start' => '15 Aug 2026', 'end' => '30 Aug 2026', 'status' => 'Upcoming', 'status_class' => 'bg-info'],
        ['activity' => 'Objection and Claims Period', 'start' => '15 Aug 2026', 'end' => '30 Aug 2026', 'status' => 'Upcoming', 'status_class' => 'bg-warning text-dark'],
        ['activity' => 'Candidate Nomination Period', 'start' => '01 Sep 2026', 'end' => '15 Sep 2026', 'status' => 'Upcoming', 'status_class' => 'bg-primary'],
        ['activity' => 'Vetting of Candidates', 'start' => '16 Sep 2026', 'end' => '25 Sep 2026', 'status' => 'Upcoming', 'status_class' => 'bg-secondary'],
        ['activity' => 'Publication of Final Voters Register', 'start' => '01 Oct 2026', 'end' => '01 Oct 2026', 'status' => 'Pending', 'status_class' => 'bg-secondary'],
        ['activity' => 'Official Campaign Period', 'start' => '01 Oct 2026', 'end' => '18 Dec 2026', 'status' => 'Pending', 'status_class' => 'bg-warning text-dark'],
        ['activity' => 'Campaign Silence Period', 'start' => '19 Dec 2026', 'end' => '20 Dec 2026', 'status' => 'Pending', 'status_class' => 'bg-danger'],
        ['activity' => 'Election Day (Polling)', 'start' => '21 Dec 2026', 'end' => '21 Dec 2026', 'status' => 'Election Day', 'status_class' => 'bg-danger'],
        ['activity' => 'Results Collation and Announcement', 'start' => '22 Dec 2026', 'end' => '28 Dec 2026', 'status' => 'Pending', 'status_class' => 'bg-info'],
        ['activity' => 'Electoral Dispute Resolution Period', 'start' => '22 Dec 2026', 'end' => '05 Jan 2027', 'status' => 'Pending', 'status_class' => 'bg-secondary'],
    ];
}

$total_events = count($calendar);
$election_idx = null;
foreach ($calendar as $i => $ev) {
    if (stripos($ev['activity'], 'Election Day') !== false) {
        $election_idx = $i;
        break;
    }
}
$timeline_pct = $election_idx !== null ? round(($election_idx + 1) / $total_events * 100) : 50;
@endphp

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">

                <!-- Countdown Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid var(--nec-gold)!important;overflow:hidden;">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3"><i class="fas fa-hourglass-half me-2" style="color:var(--nec-gold);"></i>Countdown to Election Day</h5>
                        <div class="d-flex justify-content-center gap-3 mb-2" id="countdownDisplay">
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:1.8rem;color:var(--nec-green);line-height:1;" id="countdownDays">--</div>
                                <small class="text-muted text-uppercase" style="font-size:0.6rem;letter-spacing:1px;">Days</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:1.8rem;color:var(--nec-green);line-height:1;" id="countdownHours">--</div>
                                <small class="text-muted text-uppercase" style="font-size:0.6rem;letter-spacing:1px;">Hrs</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:1.8rem;color:var(--nec-green);line-height:1;" id="countdownMinutes">--</div>
                                <small class="text-muted text-uppercase" style="font-size:0.6rem;letter-spacing:1px;">Min</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold" style="font-size:1.8rem;color:var(--nec-green);line-height:1;" id="countdownSeconds">--</div>
                                <small class="text-muted text-uppercase" style="font-size:0.6rem;letter-spacing:1px;">Sec</small>
                            </div>
                        </div>
                        <small class="text-muted d-block">21 December 2026</small>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid var(--nec-gold)!important;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2" style="color:var(--nec-gold);"></i>Quick Info</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 pb-2 border-bottom d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Type:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">General Elections 2026</span>
                            </li>
                            <li class="mb-2 pb-2 border-bottom d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Positions:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">President, MPs, State Assembly</span>
                            </li>
                            <li class="mb-2 pb-2 border-bottom d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Registered Voters:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">~8.4 million</span>
                            </li>
                            <li class="mb-2 pb-2 border-bottom d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Polling Stations:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">3,284</span>
                            </li>
                            <li class="mb-2 pb-2 border-bottom d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Constituencies:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">80</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--nec-green);width:8px;height:8px;padding:0;"></span>
                                <strong style="font-size:0.85rem;">Political Parties:</strong>
                                <span class="text-muted" style="font-size:0.85rem;">29</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Downloads -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0" style="font-size:0.95rem;"><i class="fas fa-download me-2" style="color:var(--nec-gold);"></i>Downloads</h5>
                            <span class="badge bg-success total-downloads-badge" style="font-size:0.65rem;background:var(--nec-green)!important;">
                                <i class="fas fa-download me-1"></i><span class="total-dl-count">0</span>
                            </span>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-2 border-bottom" data-track-download="calendar-pdf-2026" data-label="Full Calendar PDF 2026">
                                <i class="fas fa-file-pdf" style="color:#dc3545;font-size:1.1rem;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">Full Calendar PDF</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.6rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-2 border-bottom" data-track-download="calendar-csv-2026" data-label="Calendar CSV 2026">
                                <i class="fas fa-file-excel" style="color:var(--nec-green);font-size:1.1rem;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">Calendar (CSV)</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.6rem;font-weight:500;">0</span>
                            </a>
                            <a href="{{ route('downloads.index') }}" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-2">
                                <i class="fas fa-folder" style="color:var(--nec-gold);font-size:1.1rem;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">All Resources</small></div>
                                <i class="fas fa-arrow-right text-muted" style="font-size:0.75rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Links -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="font-size:0.95rem;color:var(--nec-black);"><i class="fas fa-link me-2" style="color:var(--nec-gold);"></i>Related Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('elections.types') }}" class="list-group-item list-group-item-action px-0 border-bottom d-flex align-items-center gap-2">
                                <i class="fas fa-check-circle" style="color:var(--nec-green);font-size:0.75rem;"></i>
                                <small>Types of Elections</small>
                            </a>
                            <a href="{{ route('elections.results') }}" class="list-group-item list-group-item-action px-0 border-bottom d-flex align-items-center gap-2">
                                <i class="fas fa-check-circle" style="color:var(--nec-green);font-size:0.75rem;"></i>
                                <small>Election Results</small>
                            </a>
                            <a href="{{ route('voter.register') }}" class="list-group-item list-group-item-action px-0 border-bottom d-flex align-items-center gap-2">
                                <i class="fas fa-check-circle" style="color:var(--nec-green);font-size:0.75rem;"></i>
                                <small>Register to Vote</small>
                            </a>
                            <a href="{{ route('about.legal-framework') }}" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-2">
                                <i class="fas fa-check-circle" style="color:var(--nec-green);font-size:0.75rem;"></i>
                                <small>Legal Framework</small>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Key Milestones -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="font-size:0.95rem;color:var(--nec-black);"><i class="fas fa-star me-2" style="color:var(--nec-gold);"></i>Key Milestones</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge border px-3 py-2" style="font-size:0.72rem;font-weight:500;color:var(--nec-gray-600);border-color:var(--nec-gray-300)!important;">
                                <span class="fw-bold d-block" style="color:var(--nec-green);">12 Jul</span>
                                Reg. Opens
                            </span>
                            <span class="badge border px-3 py-2" style="font-size:0.72rem;font-weight:500;color:var(--nec-gray-600);border-color:var(--nec-gray-300)!important;">
                                <span class="fw-bold d-block" style="color:var(--nec-green);">01 Sep</span>
                                Nominations
                            </span>
                            <span class="badge border px-3 py-2" style="font-size:0.72rem;font-weight:500;color:var(--nec-gray-600);border-color:var(--nec-gray-300)!important;">
                                <span class="fw-bold d-block" style="color:var(--nec-green);">01 Oct</span>
                                Campaign
                            </span>
                            <span class="badge border px-3 py-2" style="font-size:0.72rem;font-weight:500;color:var(--nec-gray-600);border-color:var(--nec-gray-300)!important;">
                                <span class="fw-bold d-block" style="color:var(--nec-red);">21 Dec</span>
                                Election Day
                            </span>
                            <span class="badge border px-3 py-2" style="font-size:0.72rem;font-weight:500;color:var(--nec-gray-600);border-color:var(--nec-gray-300)!important;">
                                <span class="fw-bold d-block" style="color:var(--nec-green);">22 Dec</span>
                                Results
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-8">

                <!-- Timeline Progress -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0" style="color:var(--nec-black);">
                                <i class="fas fa-calendar-check me-2" style="color:var(--nec-green);"></i>Election Timeline
                            </h5>
                            <span class="badge bg-success" style="font-size:0.7rem;">{{ $total_events }} Milestones</span>
                        </div>
                        <div class="progress" style="height:8px;border-radius:4px;background:var(--nec-gray-200);">
                            <div class="progress-bar" style="width:{{ $timeline_pct }}%;background:linear-gradient(90deg,var(--nec-green),var(--nec-gold));border-radius:4px;transition:width 1s ease;" role="progressbar" aria-valuenow="{{ $timeline_pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">Jul 2026</small>
                            <small class="text-muted fw-semibold" style="color:var(--nec-green);">Election Day — 21 Dec 2026</small>
                            <small class="text-muted">Jan 2027</small>
                        </div>
                    </div>
                </div>

                <!-- Table Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <h2 class="fw-bold mb-0" style="color: var(--nec-green); font-size:1.35rem;">
                        <i class="fas fa-clock me-2"></i>2026 General Elections Calendar
                    </h2>
                    <div class="d-flex gap-2 mt-2 mt-sm-0">
                        <div class="input-group input-group-sm" style="max-width:220px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:0.75rem;"></i></span>
                            <input type="text" class="form-control border-start-0" id="calendarSearch" placeholder="Search activities..." style="font-size:0.82rem;">
                        </div>
                        <button class="btn btn-sm btn-outline-success" id="exportCsvBtn" title="Export as CSV" style="font-size:0.78rem;">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:0.88rem;">The following table outlines key electoral activities and their scheduled start and end dates for the 2026 General Elections. Dates are subject to revision by the Commission as necessary.</p>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="calendarTable" style="border-color:var(--nec-gray-200);">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:4%;">#</th>
                                <th style="width:38%;">Activity</th>
                                <th style="width:17%;">Start Date</th>
                                <th style="width:17%;">End Date</th>
                                <th style="width:10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; $is_election = false; @endphp
                            @foreach($calendar as $ev)
                                @php
                                    $is_election = stripos($ev['activity'], 'Election Day') !== false;
                                    $icon = 'fa-calendar-day';
                                    if (stripos($ev['activity'], 'Voter') !== false || stripos($ev['activity'], 'Register') !== false) {
                                        $icon = 'fa-users';
                                    } elseif (stripos($ev['activity'], 'Candidate') !== false || stripos($ev['activity'], 'Nomination') !== false) {
                                        $icon = 'fa-user-check';
                                    } elseif (stripos($ev['activity'], 'Campaign') !== false || stripos($ev['activity'], 'Silence') !== false) {
                                        $icon = 'fa-bullhorn';
                                    } elseif (stripos($ev['activity'], 'Election') !== false || stripos($ev['activity'], 'Polling') !== false) {
                                        $icon = 'fa-check-double';
                                    } elseif (stripos($ev['activity'], 'Result') !== false) {
                                        $icon = 'fa-chart-bar';
                                    } elseif (stripos($ev['activity'], 'Objection') !== false || stripos($ev['activity'], 'Claim') !== false) {
                                        $icon = 'fa-gavel';
                                    } elseif (stripos($ev['activity'], 'Vetting') !== false) {
                                        $icon = 'fa-search';
                                    } elseif (stripos($ev['activity'], 'Display') !== false || stripos($ev['activity'], 'Publication') !== false) {
                                        $icon = 'fa-file-alt';
                                    } elseif (stripos($ev['activity'], 'Dispute') !== false) {
                                        $icon = 'fa-scale-balanced';
                                    }
                                @endphp
                            <tr class="{{ $is_election ? 'table-warning' : '' }}">
                                <td class="text-center fw-bold {{ $is_election ? 'text-warning-emphasis' : 'text-muted' }}" style="font-size:0.82rem;">{{ $i++ }}</td>
                                <td class="fw-semibold" style="font-size:0.88rem;">
                                    <i class="fas {{ $icon }} me-2" style="color:var(--nec-green);width:16px;text-align:center;"></i>
                                    {{ $ev['activity'] }}
                                    @if($is_election)
                                        <span class="badge bg-danger ms-2" style="font-size:0.6rem;animation:pulse-badge 2s infinite;"><i class="fas fa-check-circle me-1"></i>VOTE</span>
                                    @endif
                                </td>
                                <td style="font-size:0.85rem;"><i class="far fa-calendar-alt me-1 text-muted"></i>{{ $ev['start'] }}</td>
                                <td style="font-size:0.85rem;"><i class="far fa-calendar-alt me-1 text-muted"></i>{{ $ev['end'] }}</td>
                                <td><span class="badge w-100 {{ $ev['status_class'] }}" style="font-size:0.7rem;">{{ $ev['status'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Table is sortable — click any column header. Use the search box above to filter.</small>
            </div>
        </div>
    </div>
</section>

<!-- Bottom Info Cards -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0" style="font-size:1.8rem;color:var(--nec-gold);"><i class="fas fa-question-circle"></i></div>
                            <div>
                                <h5 class="fw-bold" style="font-size:0.95rem;">Important Notes</h5>
                                <ul class="small text-muted mb-0 ps-3" style="line-height:1.8;">
                                    <li>Dates are subject to change by the Commission as may be necessary</li>
                                    <li>All times are local time (CAT, UTC+2)</li>
                                    <li>Polling stations open at 7:00 AM and close at 5:00 PM</li>
                                    <li>Voters must present a valid voter ID card to vote</li>
                                    <li>Campaign silence period begins 24 hours before polling</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0" style="font-size:1.8rem;color:var(--nec-gold);"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <h5 class="fw-bold" style="font-size:0.95rem;">Need Help?</h5>
                                <p class="small text-muted mb-2">Contact the NEC for any questions about the election calendar or electoral process.</p>
                                <div class="small">
                                    <span class="d-block mb-1"><i class="fas fa-phone me-2" style="color:var(--nec-green);"></i>+211 (0) 912 345 678</span>
                                    <span class="d-block mb-1"><i class="fas fa-envelope me-2" style="color:var(--nec-green);"></i>info@nec.gov.ss</span>
                                    <a href="{{ route('contact.index') }}" class="btn btn-sm btn-outline-success mt-2">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse-badge {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
#countdownDisplay > div {
    min-width: 50px;
}
.table-dark th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(255,255,255,0.1) !important;
}
.table tbody tr:hover {
    background: rgba(0,145,76,0.04) !important;
}
.table tbody tr.table-warning:hover {
    background: rgba(255,193,7,0.15) !important;
}
#calendarTable td {
    vertical-align: middle;
    padding: 0.65rem 0.5rem;
}
</style>
@endsection

@section('extra_scripts')
<script>
(function($) {
    'use strict';

    function initCountdown() {
        var electionDay = new Date('2026-12-21T07:00:00').getTime();
        if (isNaN(electionDay)) return;

        function tick() {
            var now = new Date().getTime();
            var diff = electionDay - now;
            if (diff <= 0) {
                $('#countdownDisplay').html('<div class="text-center"><span class="fw-bold" style="font-size:1.5rem;color:var(--nec-red);">Election Day Is Here!</span></div>');
                return;
            }
            var days = Math.floor(diff / (1000 * 60 * 60 * 24));
            var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((diff % (1000 * 60)) / 1000);
            $('#countdownDays').text(days);
            $('#countdownHours').text(String(hours).padStart(2, '0'));
            $('#countdownMinutes').text(String(minutes).padStart(2, '0'));
            $('#countdownSeconds').text(String(seconds).padStart(2, '0'));
        }

        tick();
        setInterval(tick, 1000);
    }

    function initTableSearch() {
        var $input = $('#calendarSearch');
        var $table = $('#calendarTable');
        if (!$input.length || !$table.length) return;

        $input.on('keyup', function() {
            var q = $(this).val().toLowerCase();
            $table.find('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(q) > -1);
            });
        });
    }

    function initExportCsv() {
        var $btn = $('#exportCsvBtn');
        if (!$btn.length) return;

        $btn.on('click', function(e) {
            e.preventDefault();
            var rows = [];
            $('#calendarTable thead tr').each(function() {
                var cols = [];
                $(this).find('th').each(function() {
                    cols.push('"' + $(this).text().trim() + '"');
                });
                rows.push(cols.join(','));
            });
            $('#calendarTable tbody tr').each(function() {
                var cols = [];
                $(this).find('td').each(function() {
                    cols.push('"' + $(this).text().trim() + '"');
                });
                rows.push(cols.join(','));
            });
            var csv = rows.join('\n');
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'NEC_Election_Calendar_2026.csv';
            link.click();
        });
    }

    $(document).ready(function() {
        initCountdown();
        initTableSearch();
        initExportCsv();
    });
})(jQuery);
</script>
@endsection
