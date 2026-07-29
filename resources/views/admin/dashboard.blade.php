@extends('admin.layouts.app', ['title' => 'Dashboard'])

@section('content')
{{-- Greeting Banner --}}
<div class="greeting-banner">
    <div>
        <h2>
            @if($role === 'super_admin')
                <i class="fas fa-crown me-2"></i>
            @elseif($role === 'admin')
                <i class="fas fa-shield-alt me-2"></i>
            @elseif($role === 'state_coordinator')
                <i class="fas fa-map-marked-alt me-2"></i>
            @elseif($role === 'constituency_officer')
                <i class="fas fa-landmark me-2"></i>
            @else
                <i class="fas fa-tachometer-alt me-2"></i>
            @endif
            Welcome, {{ session('admin_user_name', 'Admin') }}
        </h2>
        <p><i class="fas fa-clock me-1"></i>{{ now()->format('l, F j, Y \a\t g:i A') }} &middot; <span class="badge" style="background:rgba(255,255,255,0.2);color:#fff;">{{ ucfirst(str_replace('_', ' ', $role ?? 'admin')) }}</span></p>
    </div>
    <div class="greeting-actions">
        @if(in_array($role, ['super_admin', 'admin']))
            <a href="{{ route('admin.users.create') }}" class="greeting-btn greeting-btn-primary">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        @endif
        <a href="{{ route('admin.voters.create') }}" class="greeting-btn">
            <i class="fas fa-user-check"></i> Register Voter
        </a>
    </div>
</div>

@if(in_array($role, ['super_admin', 'admin']))

{{-- Section: Voter Registration Overview --}}
<div class="section-title">
    <i class="fas fa-users me-2"></i>Voter Registration Overview
    <span class="section-title-badge">{{ number_format($stats['total_voters'] ?? 0) }} total</span>
</div>
<div class="stat-grid">
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_voters'] ?? 0) }}</div>
                <div class="stat-label">Total Voters</div>
                <div class="stat-detail">
                    <span class="text-success">{{ number_format($stats['active_voters'] ?? 0) }} active</span>
                    &middot; <span class="text-muted">{{ number_format($stats['inactive_voters'] ?? 0) }} inactive</span>
                    &middot; <span class="text-danger">{{ number_format($stats['suspended_voters'] ?? 0) }} suspended</span>
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['active_voters'] ?? 0) }}</div>
                <div class="stat-label">Active Voters</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['active_voters'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of total voter population
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-mars"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['male_count'] ?? 0) }}</div>
                <div class="stat-label">Male Voters</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['male_count'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of voters &middot; Ratio {{ $stats['gender_ratio'] ?? 'N/A' }}:1
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-venus"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['female_count'] ?? 0) }}</div>
                <div class="stat-label">Female Voters</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['female_count'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of voters &middot; Ratio 1:{{ ($stats['female_count'] ?? 0) > 0 ? round(($stats['male_count'] ?? 0) / ($stats['female_count'] ?? 1), 2) : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim info">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-edit"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['self_registered'] ?? 0) }}</div>
                <div class="stat-label">Self-Registered</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['self_registered'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of all registrations
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim purple">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['agent_registered'] ?? 0) }}</div>
                <div class="stat-label">Agent-Assisted</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['agent_registered'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of all registrations
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['voters_by_status']['pending'] ?? 0) }}</div>
                <div class="stat-label">Pending Voters</div>
                <div class="stat-detail">
                    Awaiting verification &middot; {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['voters_by_status']['pending'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of total
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-ban"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['suspended_voters'] ?? 0) }}</div>
                <div class="stat-label">Suspended Voters</div>
                <div class="stat-detail">
                    {{ ($stats['total_voters'] ?? 0) > 0 ? round(($stats['suspended_voters'] ?? 0) / ($stats['total_voters'] ?? 1) * 100, 1) : 0 }}% of total population
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Registration Activity --}}
<div class="section-title">
    <i class="fas fa-chart-line me-2"></i>Registration Activity
    <span class="section-title-badge">{{ number_format($stats['new_this_month'] ?? 0) }} this month</span>
</div>
<div class="stat-grid">
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['new_today'] ?? 0) }}</div>
                <div class="stat-label">New Today</div>
                <div class="stat-detail">
                    <i class="fas fa-arrow-up text-success"></i> {{ number_format($stats['new_this_week'] ?? 0) }} this week
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['new_this_week'] ?? 0) }}</div>
                <div class="stat-label">This Week</div>
                <div class="stat-detail">
                    Weekly registration volume
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['new_this_month'] ?? 0) }}</div>
                <div class="stat-label">This Month</div>
                <div class="stat-detail">
                    Monthly registration total
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-tachometer-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['avg_daily_registrations'] ?? 0) }}</div>
                <div class="stat-label">Avg Daily Registrations</div>
                <div class="stat-detail">
                    Registration pace since campaign start
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Coverage KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:80px;height:80px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:conic-gradient(var(--nec-green) {{ ($stats['county_coverage_pct'] ?? 0) * 3.6 }}deg, #e9ecef 0deg);">
                    <span class="fw-bold" style="font-size:1.1rem;background:var(--card-bg);width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $stats['county_coverage_pct'] ?? 0 }}%</span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">County Coverage</h6>
                    <small class="text-muted">{{ number_format($stats['coverage_counties'] ?? 0) }} / {{ number_format($stats['total_counties'] ?? 0) }} counties</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:80px;height:80px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:conic-gradient(var(--nec-blue) {{ ($stats['total_payams'] ?? 0) > 0 ? round(($stats['coverage_payams'] ?? 0) / ($stats['total_payams'] ?? 1) * 360) : 0 }}deg, #e9ecef 0deg);">
                    <span class="fw-bold" style="font-size:1rem;background:var(--card-bg);width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ number_format($stats['coverage_payams'] ?? 0) }}</span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Payams Covered</h6>
                    <small class="text-muted">of {{ number_format($stats['total_payams'] ?? 0) }} total payams</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="width:60px;height:60px;background:rgba(46,139,87,0.12);color:var(--nec-green);">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Avg Daily Registrations</h6>
                    <h4 class="mb-0 fw-bold" style="color:var(--nec-green);">{{ number_format($stats['avg_daily_registrations'] ?? 0) }}</h4>
                    <small class="text-muted">{{ number_format($stats['new_this_week'] ?? 0) }} this week</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Voter Insights --}}
<div class="section-title">
    <i class="fas fa-chart-bar me-2"></i>Voter Insights
    <span class="section-title-badge">Age analytics</span>
</div>
<div class="stat-grid">
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-baby"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['youngest_voter_age'] ?? 'N/A' }}</div>
                <div class="stat-label">Youngest Voter Age</div>
                <div class="stat-detail">
                    Minimum voter age in the system
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['oldest_voter_age'] ?? 'N/A' }}</div>
                <div class="stat-label">Oldest Voter Age</div>
                <div class="stat-detail">
                    Maximum voter age in the system
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['age_highest_group'] ?? 'N/A' }}</div>
                <div class="stat-label">Most Populous Age Group</div>
                <div class="stat-detail">
                    Largest voter age demographic
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-minus"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['age_lowest_group'] ?? 'N/A' }}</div>
                <div class="stat-label">Least Populous Age Group</div>
                <div class="stat-detail">
                    Smallest voter age demographic
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Ballot & Petitions --}}
<div class="section-title">
    <i class="fas fa-box-open me-2"></i>Ballot & Petitions
    <span class="section-title-badge">{{ number_format(($stats['total_ballots'] ?? 0) + ($stats['total_petitions'] ?? 0)) }} items</span>
</div>
<div class="stat-grid">
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-box-open"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_ballots'] ?? 0) }}</div>
                <div class="stat-label">Ballot Designs</div>
                <div class="stat-detail">
                    {{ number_format($stats['active_ballots'] ?? 0) }} active ballot designs
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-print"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_ballots_printed'] ?? 0) }}</div>
                <div class="stat-label">Ballots Printed</div>
                <div class="stat-detail">
                    Total printed ballot count
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-gavel"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_petitions'] ?? 0) }}</div>
                <div class="stat-label">Election Petitions</div>
                <div class="stat-detail">
                    Total petitions filed
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['pending_petitions'] ?? 0) }}</div>
                <div class="stat-label">Pending Petitions</div>
                <div class="stat-detail">
                    Awaiting court resolution
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: System Health --}}
<div class="section-title">
    <i class="fas fa-server me-2"></i>System Health
    <span class="section-title-badge">{{ number_format($stats['total_users'] ?? 0) }} users</span>
</div>
<div class="stat-grid">
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
                <div class="stat-label">System Users</div>
                <div class="stat-detail">
                    Registered admin & staff accounts
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim purple">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-history"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['recent_activity_count'] ?? 0) }}</div>
                <div class="stat-label">Recent Activity (24h)</div>
                <div class="stat-detail">
                    System actions in last 24 hours
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['security_logs_24h'] ?? 0) }}</div>
                <div class="stat-label">Security Events (24h)</div>
                <div class="stat-detail">
                    Security-related events logged
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_voters'] ?? 0) }}</div>
                <div class="stat-label">Database Records</div>
                <div class="stat-detail">
                    Total voter records maintained
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Election Infrastructure --}}
<div class="section-title">
    <i class="fas fa-landmark me-2"></i>Election Infrastructure
    <span class="section-title-badge">{{ number_format($stats['total_polling_stations'] ?? 0 + $stats['total_constituencies'] ?? 0) }} facilities</span>
</div>
<div class="stat-grid">
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_constituencies'] ?? 0) }}</div>
                <div class="stat-label">Constituencies</div>
                <div class="stat-detail">
                    {{ number_format($stats['coverage_counties'] ?? 0) }} counties covered
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-vote-yea"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_polling_stations'] ?? 0) }}</div>
                <div class="stat-label">Polling Stations</div>
                <div class="stat-detail">
                    Capacity: {{ $stats['registration_capacity_pct'] ?? 0 }}% utilized
                </div>
                <div class="progress" style="height:3px;margin-top:4px;">
                    <div class="progress-bar bg-info" style="width: {{ min($stats['registration_capacity_pct'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim purple">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-flag"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_parties'] ?? 0) }}</div>
                <div class="stat-label">Political Parties</div>
                <div class="stat-detail">
                    Registered political organizations
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_candidates'] ?? 0) }}</div>
                <div class="stat-label">Candidates</div>
                <div class="stat-detail">
                    Contesting in current election cycle
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_commissioners'] ?? 0) }}</div>
                <div class="stat-label">Commissioners</div>
                <div class="stat-detail">
                    NEC governing commissioners
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim info">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-eye"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_observers'] ?? 0) }}</div>
                <div class="stat-label">Observers</div>
                <div class="stat-detail">
                    Observer applications received
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim teal">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ ($stats['total_polling_stations'] ?? 0) > 0 ? number_format(($stats['total_voters'] ?? 0) / ($stats['total_polling_stations'] ?? 1)) : 0 }}</div>
                <div class="stat-label">Avg Voters/Station</div>
                <div class="stat-detail">
                    Average voters per polling station
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim pink">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-balance-scale"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ ($stats['total_constituencies'] ?? 0) > 0 ? number_format(($stats['total_candidates'] ?? 0) / ($stats['total_constituencies'] ?? 1)) : 0 }}</div>
                <div class="stat-label">Avg Candidates/Constituency</div>
                <div class="stat-detail">
                    Average candidates per constituency
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Content Management --}}
<div class="section-title">
    <i class="fas fa-newspaper me-2"></i>Content Management
    <span class="section-title-badge">{{ number_format(
        ($stats['published_news'] ?? 0) + ($stats['active_announcements'] ?? 0) + ($stats['total_events'] ?? 0) + ($stats['total_election_events'] ?? 0) + ($stats['total_gallery'] ?? 0) + ($stats['total_videos'] ?? 0) + ($stats['total_speeches'] ?? 0) + ($stats['total_education'] ?? 0) + ($stats['total_subscribers'] ?? 0)
    ) }} items</span>
</div>
<div class="stat-grid">
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_news'] ?? 0) }}</div>
                <div class="stat-label">News Articles</div>
                <div class="stat-detail">
                    {{ number_format($stats['published_news'] ?? 0) }} published &middot; {{ max(0, ($stats['total_news'] ?? 0) - ($stats['published_news'] ?? 0)) }} drafts
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['active_announcements'] ?? 0) }}</div>
                <div class="stat-label">Active Announcements</div>
                <div class="stat-detail">
                    {{ number_format($stats['total_announcements'] ?? 0) }} total announcements
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format(($stats['total_events'] ?? 0) + ($stats['total_election_events'] ?? 0)) }}</div>
                <div class="stat-label">Events</div>
                <div class="stat-detail">
                    {{ number_format($stats['upcoming_events'] ?? 0) }} upcoming &middot; {{ number_format($stats['total_election_events'] ?? 0) }} election events
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim purple">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-images"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_gallery'] ?? 0) }}</div>
                <div class="stat-label">Gallery Items</div>
                <div class="stat-detail">
                    Photo gallery and albums
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-video"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_videos'] ?? 0) }}</div>
                <div class="stat-label">Videos</div>
                <div class="stat-detail">
                    Video library content
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-comment-dots"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_speeches'] ?? 0) }}</div>
                <div class="stat-label">Speeches</div>
                <div class="stat-detail">
                    Official NEC speeches and addresses
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_education'] ?? 0) }}</div>
                <div class="stat-label">Education Materials</div>
                <div class="stat-detail">
                    Voter education resources
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim info">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_subscribers'] ?? 0) }}</div>
                <div class="stat-label">Newsletter Subscribers</div>
                <div class="stat-detail">
                    Email subscribers for communications
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Operations & Support --}}
<div class="section-title">
    <i class="fas fa-tasks me-2"></i>Operations & Support
    <span class="section-title-badge">{{ number_format(
        ($stats['total_agents'] ?? 0) + ($stats['pending_transfers'] ?? 0) + ($stats['new_complaints'] ?? 0) + ($stats['pending_contacts'] ?? 0)
    ) }} pending items</span>
</div>
<div class="stat-grid">
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_agents'] ?? 0) }}</div>
                <div class="stat-label">Registration Agents</div>
                <div class="stat-detail">
                    Active field registration agents
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['pending_transfers'] ?? 0) }}</div>
                <div class="stat-label">Pending Transfers</div>
                <div class="stat-detail">
                    {{ $stats['pending_transfer_rate'] ?? 0 }}% of total voters
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['new_complaints'] ?? 0) }}</div>
                <div class="stat-label">New Complaints</div>
                <div class="stat-detail">
                    {{ number_format($stats['total_complaints'] ?? 0) }} total complaints received
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['pending_contacts'] ?? 0) }}</div>
                <div class="stat-label">Unread Messages</div>
                <div class="stat-detail">
                    {{ number_format($stats['total_contacts'] ?? 0) }} total contact messages
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim purple">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_reports'] ?? 0) }}</div>
                <div class="stat-label">Reports</div>
                <div class="stat-detail">
                    Published NEC reports
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-users-cog"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_polling_staff'] ?? 0) }}</div>
                <div class="stat-label">Polling Staff</div>
                <div class="stat-detail">
                    {{ number_format($stats['trained_staff'] ?? 0) }} trained &middot; {{ max(0, ($stats['total_polling_staff'] ?? 0) - ($stats['trained_staff'] ?? 0)) }} pending training
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-download"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_downloads'] ?? 0) }}</div>
                <div class="stat-label">Downloads</div>
                <div class="stat-detail">
                    Downloadable resources & forms
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim teal">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['transfers_by_status']['approved'] ?? 0) }}</div>
                <div class="stat-label">Completed Transfers</div>
                <div class="stat-detail">
                    Approved voter transfer requests
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Gender + Registration Type + Status row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-venus-mars me-2" style="color: var(--nec-gold)"></i>Gender Split</h6></div>
            <div class="card-body text-center">
                <canvas id="genderMiniChart" height="200"></canvas>
                <div class="d-flex justify-content-center gap-4 mt-2">
                    <span><i class="fas fa-mars text-primary me-1"></i> {{ number_format($stats['male_count'] ?? 0) }}</span>
                    <span><i class="fas fa-venus" style="color:var(--nec-green)"></i> {{ number_format($stats['female_count'] ?? 0) }}</span>
                </div>
                <small class="text-muted">Ratio: {{ $stats['gender_ratio'] ?? 'N/A' }}:1 (M:F)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2 text-info"></i>Registration Type</h6></div>
            <div class="card-body text-center">
                <canvas id="regTypeChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-info"></i>Voter Status</h6></div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Charts: Registration Trend + Gender by Age --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2" style="color: var(--nec-green)"></i>Registration Trend (Last 30 Days)</h6>
                <span class="badge bg-success text-white">{{ number_format($stats['new_this_month'] ?? 0) }} this month</span>
            </div>
            <div class="card-body">
                <canvas id="registrationTrendChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm chart-card h-100">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-venus-mars me-2" style="color: var(--nec-gold)"></i>Gender by Age Group</h6>
            </div>
            <div class="card-body">
                <canvas id="genderAgeChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Charts: State Bar + Age Distribution --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt me-2" style="color: var(--nec-blue)"></i>Voters by State</h6>
            </div>
            <div class="card-body">
                <canvas id="stateBarChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm chart-card h-100">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-birthday-cake me-2" style="color: var(--nec-red)"></i>Age Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="ageChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Charts: Monthly Trend + Top Counties --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2" style="color: var(--nec-blue)"></i>Monthly Trend (12 Months)</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyTrendChart" height="260"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map me-2" style="color: var(--nec-green)"></i>Top Counties</h6>
            </div>
            <div class="card-body">
                <canvas id="countyChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Activity + Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2" style="color: var(--nec-green)"></i>Recent Activity</h6>
                @if(in_array($role, ['super_admin', 'admin']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                @endif
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentActivity as $log)
                    @php $actionClass = $log->action ?? 'create'; @endphp
                    <div class="activity-item {{ $actionClass }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold" style="font-size:0.85rem;">{{ $log->user_email ?? 'System' }}</span>
                                <span class="ms-2" style="font-size:0.82rem;">{{ $log->details ?? $log->description ?? '' }}</span>
                            </div>
                            <small class="text-muted text-nowrap ms-3" style="font-size:0.72rem;">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-bolt me-2" style="color: var(--nec-gold)"></i>Quick Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.voters.create') }}" class="quick-action-btn text-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon" style="background: rgba(46,139,87,0.12); width:40px; height:40px; min-width:40px;">
                            <i class="fas fa-user-check" style="color: var(--nec-green); font-size:0.9rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;">Register Voter</div>
                            <small class="text-muted">New voter registration</small>
                        </div>
                    </div>
                </a>
                @if(in_array($role, ['super_admin', 'admin']))
                    <a href="{{ route('admin.voter-transfers.index') }}" class="quick-action-btn text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="kpi-icon" style="background: rgba(255,193,7,0.12); width:40px; height:40px; min-width:40px;">
                                <i class="fas fa-exchange-alt" style="color: #ffc107; font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">Voter Transfers</div>
                                <small class="text-muted">Review transfer requests</small>
                            </div>
                            @if(($stats['pending_transfers'] ?? 0) > 0)
                                <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending_transfers'] }}</span>
                            @endif
                        </div>
                    </a>
                    <a href="{{ route('admin.complaints.index') }}" class="quick-action-btn text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="kpi-icon" style="background: rgba(139,0,0,0.12); width:40px; height:40px; min-width:40px;">
                                <i class="fas fa-exclamation-triangle" style="color: var(--nec-red); font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">Complaints</div>
                                <small class="text-muted">Review complaints</small>
                            </div>
                            @if(($stats['new_complaints'] ?? 0) > 0)
                                <span class="badge bg-danger ms-auto">{{ $stats['new_complaints'] }}</span>
                            @endif
                        </div>
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="quick-action-btn text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="kpi-icon" style="background: rgba(26,60,143,0.12); width:40px; height:40px; min-width:40px;">
                                <i class="fas fa-envelope" style="color: var(--nec-blue); font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">Messages</div>
                                <small class="text-muted">Check contact messages</small>
                            </div>
                            @if(($stats['pending_contacts'] ?? 0) > 0)
                                <span class="badge bg-primary ms-auto">{{ $stats['pending_contacts'] }}</span>
                            @endif
                        </div>
                    </a>
                    <a href="{{ route('admin.observers.index') }}" class="quick-action-btn text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="kpi-icon" style="background: rgba(111,66,193,0.12); width:40px; height:40px; min-width:40px;">
                                <i class="fas fa-eye" style="color: #6f42c1; font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">Observers</div>
                                <small class="text-muted">Review observer applications</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="quick-action-btn text-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="kpi-icon" style="background: rgba(108,117,125,0.12); width:40px; height:40px; min-width:40px;">
                                <i class="fas fa-history" style="color: #6c757d; font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">Activity Logs</div>
                                <small class="text-muted">View system audit trail</small>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Top Agents + Top Constituencies --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-user-check me-2" style="color: var(--nec-green)"></i>Top Registration Agents</h6></div>
            <div class="card-body p-0">
                @forelse($stats['top_agents'] ?? [] as $agent)
                    <div class="agent-row px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold" style="font-size:0.88rem;">{{ $agent->first_name }} {{ $agent->last_name }}</span>
                            <br><small class="text-muted">{{ $agent->assigned_state ?? 'N/A' }}</small>
                        </div>
                        <span class="badge" style="background:var(--nec-green);color:#fff;">{{ number_format($agent->voters_registered ?? 0) }} voters</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">No agent data</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2" style="color: var(--nec-blue)"></i>Top Constituencies</h6></div>
            <div class="card-body p-0">
                @forelse($stats['top_constituencies'] ?? [] as $tc)
                    <div class="agent-row px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold" style="font-size:0.88rem;">{{ $tc->constituency }}</span>
                            <br><small class="text-muted">{{ $tc->state }}</small>
                        </div>
                        <span class="badge bg-primary">{{ number_format($tc->total) }} voters</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">No data</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Pending Tasks --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-bottom">
        <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2" style="color: var(--nec-green)"></i>Pending Tasks</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('admin.voter-transfers.index') }}" class="text-decoration-none">
                    <div class="p-3 rounded" style="background: rgba(255,193,7,0.08); border: 1px solid rgba(255,193,7,0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-exchange-alt text-warning me-2"></i><span class="fw-semibold" style="font-size:0.85rem;">Voter Transfers</span></div>
                            <span class="badge bg-warning text-dark">{{ $stats['pending_transfers'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">
                    <div class="p-3 rounded" style="background: rgba(139,0,0,0.06); border: 1px solid rgba(139,0,0,0.15);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-envelope text-danger me-2"></i><span class="fw-semibold" style="font-size:0.85rem;">Messages</span></div>
                            <span class="badge bg-danger">{{ $stats['pending_contacts'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.complaints.index') }}" class="text-decoration-none">
                    <div class="p-3 rounded" style="background: rgba(139,0,0,0.06); border: 1px solid rgba(139,0,0,0.15);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-exclamation-triangle text-danger me-2"></i><span class="fw-semibold" style="font-size:0.85rem;">Complaints</span></div>
                            <span class="badge bg-danger">{{ $stats['new_complaints'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.observers.index') }}" class="text-decoration-none">
                    <div class="p-3 rounded" style="background: rgba(13,202,240,0.06); border: 1px solid rgba(13,202,240,0.15);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-eye text-info me-2"></i><span class="fw-semibold" style="font-size:0.85rem;">Observers</span></div>
                            <span class="badge bg-info">{{ $stats['total_observers'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- State Coordinator Dashboard --}}
@if($role === 'state_coordinator')
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2" style="color: var(--nec-green)"></i>Registration Trend (30 Days)</h6>
            </div>
            <div class="card-body"><canvas id="stateTrendChart" height="280"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map me-2" style="color: var(--nec-blue)"></i>Voters by County</h6>
            </div>
            <div class="card-body"><canvas id="stateCountyChart" height="280"></canvas></div>
        </div>
    </div>
</div>
@endif

{{-- Constituency Officer Dashboard --}}
@if($role === 'constituency_officer')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm nec-kpi h-100" style="border-left-color: var(--nec-blue) !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(26,60,143,0.12);"><i class="fas fa-vote-yea fa-lg" style="color: var(--nec-blue)"></i></div>
                <div><h6 class="text-muted mb-0">Polling Stations</h6><h3 class="mb-0" style="color: var(--nec-blue);">{{ number_format($stats['constituency_stations'] ?? 0) }}</h3></div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('extra_scripts')
<script>
Chart.defaults.animation = false;

document.addEventListener('DOMContentLoaded', function() {
    const necGreen = '#2E8B57', necBlue = '#1a3c8f', necGold = '#D4AF37', necRed = '#8B0000', necLightGreen = 'rgba(46,139,87,0.15)';

    @if(in_array($role ?? '', ['super_admin', 'admin']))

    // Gender Mini
    var genderCtx = document.getElementById('genderMiniChart');
    if (genderCtx) {
        new Chart(genderCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{ data: [{{ $stats['male_count'] ?? 0 }}, {{ $stats['female_count'] ?? 0 }}], backgroundColor: [necBlue, necGreen], borderWidth: 2, borderColor: '#fff' }]
            },
            options: { cutout: '65%', plugins: { legend: { display: false } } }
        });
    }

    // Registration Type
    var regTypeCtx = document.getElementById('regTypeChart');
    if (regTypeCtx) {
        var regTypeData = {!! json_encode($stats['registration_by_type']->toArray() ?: []) !!};
        new Chart(regTypeCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(regTypeData).map(k => k === 'self' ? 'Self-Registered' : k === 'agent' ? 'Agent-Assisted' : k.charAt(0).toUpperCase() + k.slice(1)),
                datasets: [{ data: Object.values(regTypeData), backgroundColor: [necBlue, necGold, '#6c757d'], borderWidth: 2, borderColor: '#fff' }]
            },
            options: { cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 10, usePointStyle: true, font: { size: 11 } } } } }
        });
    }

    // Status Pie
    var statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        var statusData = {!! json_encode($stats['voters_by_status']->toArray() ?: []) !!};
        var statusColors = Object.keys(statusData).map(function(s) { return s === 'active' ? necGreen : s === 'pending' ? necGold : s === 'suspended' ? necRed : '#6c757d'; });
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{ data: Object.values(statusData), backgroundColor: statusColors, borderWidth: 2, borderColor: '#fff' }]
            },
            options: { cutout: '60%', plugins: { legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true } } } }
        });
    }

    // Registration Trend (30 days)
    var trendCtx = document.getElementById('registrationTrendChart');
    if (trendCtx) {
        var trendData = {!! json_encode($stats['registration_trend_30d'] ?? collect()) !!};
        new Chart(trendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: trendData.map(d => { var dt = new Date(d.date); return dt.toLocaleDateString('en-GB', {day:'2-digit', month:'short'}); }),
                datasets: [{
                    label: 'New Registrations',
                    data: trendData.map(d => d.total),
                    borderColor: necGreen, backgroundColor: necLightGreen, fill: true, tension: 0.4, borderWidth: 2.5,
                    pointBackgroundColor: necGreen, pointRadius: 3, pointHoverRadius: 5
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 11 } } } } }
        });
    }

    // Gender by Age
    var gaCtx = document.getElementById('genderAgeChart');
    if (gaCtx) {
        var gaData = {!! json_encode($stats['gender_by_age']->toArray() ?: []) !!};
        var ageOrder = ['Under 18','18-25','26-35','36-50','51-65','65+'];
        var gaLabels = ageOrder.filter(k => gaData[k]);
        new Chart(gaCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: gaLabels,
                datasets: [
                    { label: 'Male', data: gaLabels.map(k => (gaData[k]||{}).M||0), backgroundColor: necBlue, borderRadius: 4 },
                    { label: 'Female', data: gaLabels.map(k => (gaData[k]||{}).F||0), backgroundColor: necGreen, borderRadius: 4 }
                ]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
        });
    }

    // State Bar
    var stateCtx = document.getElementById('stateBarChart');
    if (stateCtx) {
        var stateData = {!! json_encode($stats['voters_by_state']->toArray() ?: []) !!};
        var stateLabels = Object.keys(stateData);
        var stateValues = Object.values(stateData);
        var barColors = stateLabels.map(function(_, i) { return [necGreen, necBlue, necGold, necRed, '#0dcaf0', '#198754', '#ffc107', '#0d6efd', '#6c757d', '#6f42c1'][i % 10]; });
        new Chart(stateCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: stateLabels.map(s => s.length > 12 ? s.substring(0,11)+'...' : s), datasets: [{ label: 'Voters', data: stateValues, backgroundColor: barColors, borderWidth: 0, borderRadius: 6, maxBarThickness: 50 }] },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { ticks: { maxRotation: 45, font: { size: 10 } }, grid: { display: false } } } }
        });
    }

    // Age Distribution
    var ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        var ageData = {!! json_encode($stats['age_distribution']->toArray() ?: []) !!};
        var ageLabels = ['Under 18','18-25','26-35','36-50','51-65','65+'].filter(k => ageData[k] !== undefined);
        new Chart(ageCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: ageLabels, datasets: [{ label: 'Voters', data: ageLabels.map(k => ageData[k]||0), backgroundColor: [necRed, necGreen, necBlue, necGold, '#0dcaf0', '#6f42c1'], borderRadius: 6 }] },
            options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } }
        });
    }

    // Monthly Trend
    var mtCtx = document.getElementById('monthlyTrendChart');
    if (mtCtx) {
        var mtData = {!! json_encode($stats['monthly_trend'] ?? collect()) !!};
        new Chart(mtCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: mtData.map(d => d.month),
                datasets: [{ label: 'Registrations', data: mtData.map(d => d.total), borderColor: necBlue, backgroundColor: 'rgba(26,60,143,0.1)', fill: true, tension: 0.4, borderWidth: 2.5, pointBackgroundColor: necBlue, pointRadius: 4 }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
        });
    }

    // Top Counties
    var countyCtx = document.getElementById('countyChart');
    if (countyCtx) {
        var countyData = {!! json_encode($stats['voters_by_county']->toArray() ?: []) !!};
        new Chart(countyCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(countyData).map(c => c.length > 15 ? c.substring(0,14)+'...' : c), datasets: [{ label: 'Voters', data: Object.values(countyData), backgroundColor: necGreen, borderRadius: 6 }] },
            options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } }
        });
    }

    @endif

    // State Coordinator Charts
    @if(($role ?? '') === 'state_coordinator')
    var stCtx = document.getElementById('stateTrendChart');
    if (stCtx) {
        var stData = {!! json_encode($stats['state_registration_trend'] ?? collect()) !!};
        new Chart(stCtx.getContext('2d'), {
            type: 'line',
            data: { labels: stData.map(d => { var dt = new Date(d.date); return dt.toLocaleDateString('en-GB',{day:'2-digit',month:'short'}); }), datasets: [{ label: 'Registrations', data: stData.map(d => d.total), borderColor: necGreen, backgroundColor: necLightGreen, fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3 }] },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } } } }
        });
    }
    var scCtx = document.getElementById('stateCountyChart');
    if (scCtx) {
        var scData = {!! json_encode($stats['state_by_county']->toArray() ?: []) !!};
        new Chart(scCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(scData), datasets: [{ label: 'Voters', data: Object.values(scData), backgroundColor: necBlue, borderRadius: 6 }] },
            options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true }, y: { grid: { display: false } } } }
        });
    }
    @endif
});
</script>
@endsection
