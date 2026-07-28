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

{{-- KPI Cards --}}
@if(in_array($role, ['super_admin', 'admin']))
<div class="stat-grid">
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_voters'] ?? 0) }}</div>
                <div class="stat-label">Total Voters &middot; <small class="text-success"><i class="fas fa-arrow-up"></i> {{ number_format($stats['new_today'] ?? 0) }} today</small></div>
            </div>
        </div>
    </div>
    <div class="stat-slim blue">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
                <div class="stat-label">Users</div>
            </div>
        </div>
    </div>
    <div class="stat-slim gold">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-landmark"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_constituencies'] ?? 0) }}</div>
                <div class="stat-label">Constituencies</div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_candidates'] ?? 0) }}</div>
                <div class="stat-label">Candidates</div>
            </div>
        </div>
    </div>
    <div class="stat-slim cyan">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-vote-yea"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_polling_stations'] ?? 0) }}</div>
                <div class="stat-label">Polling Stations</div>
                <div class="progress" style="height:3px;margin-top:4px;">
                    <div class="progress-bar bg-info" style="width: {{ min($stats['registration_capacity_pct'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="stat-slim green">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['total_agents'] ?? 0) }}</div>
                <div class="stat-label">Agents</div>
            </div>
        </div>
    </div>
    <div class="stat-slim orange">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['pending_transfers'] ?? 0) }}</div>
                <div class="stat-label">Pending Transfers</div>
            </div>
        </div>
    </div>
    <div class="stat-slim red">
        <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats['new_complaints'] ?? 0) }}</div>
                <div class="stat-label">Complaints <small class="text-muted">({{ $stats['total_complaints'] ?? 0 }} total)</small></div>
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

{{-- Gender + Registration Type + Status row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2 text-info"></i>Registration Type</h6></div>
            <div class="card-body text-center">
                <canvas id="regTypeChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-info"></i>Voter Status</h6></div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Charts: Registration Trend + Gender by Age --}}
@if(in_array($role, ['super_admin', 'admin']))
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
@endif

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
@if(in_array($role, ['super_admin', 'admin']))
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
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
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
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
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 10, usePointStyle: true, font: { size: 11 } } } } }
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
            options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true } } } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 11 } } } } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { ticks: { maxRotation: 45, font: { size: 10 } }, grid: { display: false } } } }
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
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
        });
    }

    // Top Counties
    var countyCtx = document.getElementById('countyChart');
    if (countyCtx) {
        var countyData = {!! json_encode($stats['voters_by_county']->toArray() ?: []) !!};
        new Chart(countyCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(countyData).map(c => c.length > 15 ? c.substring(0,14)+'...' : c), datasets: [{ label: 'Voters', data: Object.values(countyData), backgroundColor: necGreen, borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } } } }
        });
    }
    var scCtx = document.getElementById('stateCountyChart');
    if (scCtx) {
        var scData = {!! json_encode($stats['state_by_county']->toArray() ?: []) !!};
        new Chart(scCtx.getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(scData), datasets: [{ label: 'Voters', data: Object.values(scData), backgroundColor: necBlue, borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true }, y: { grid: { display: false } } } }
        });
    }
    @endif
});
</script>
@endsection
