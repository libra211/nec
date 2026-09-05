@extends('admin.layouts.app', ['title' => 'National Election Operations Center'])

@section('content')
{{-- Greeting Banner --}}
<div class="greeting-banner">
    <div>
        <h2>
            @if($role === 'super_admin')<i class="fas fa-crown me-2"></i>
            @elseif($role === 'admin')<i class="fas fa-shield-alt me-2"></i>
            @elseif($role === 'state_coordinator')<i class="fas fa-map-marked-alt me-2"></i>
            @elseif($role === 'constituency_officer')<i class="fas fa-landmark me-2"></i>
            @else<i class="fas fa-tachometer-alt me-2"></i>@endif
            National Election Operations Center
        </h2>
        <p><i class="fas fa-clock me-1"></i>{{ now()->format('l, F j, Y \a\t g:i A') }} &middot; <span class="badge" style="background:rgba(255,255,255,0.2);color:#fff;">{{ ucfirst(str_replace('_', ' ', $role ?? 'admin')) }}</span></p>
    </div>
    <div class="greeting-actions">
        @if(in_array($role, ['super_admin', 'admin']))
            <a href="{{ route('admin.settings.index') }}?tab=elections" class="greeting-btn greeting-btn-primary"><i class="fas fa-cog"></i> Election Settings</a>
        @endif
        <a href="{{ route('admin.voters.create') }}" class="greeting-btn"><i class="fas fa-user-check"></i> Register Voter</a>
    </div>
</div>

@if(in_array($role, ['super_admin', 'admin']))

{{-- ===== TOP 20 EXECUTIVE KPI CARDS ===== --}}
<div class="kpi-command-row">
    <div class="kpi-card kpi-readiness">
        <div class="kpi-value">{{ $stats['readiness_index'] ?? 0 }}%</div>
        <div class="kpi-label">National Readiness</div>
        <div class="kpi-trend">{{ $stats['states_operational'] ?? 0 }}/{{ $stats['states_with_data'] ?? 0 }} states · {{ $stats['admin_areas_with_data'] ?? 0 }}/{{ $stats['total_admin_areas'] ?? 0 }} admin areas ready</div>
    </div>
    <div class="kpi-card kpi-health-{{ $stats['health_level'] ?? 'excellent' }}">
        <div class="kpi-value" style="font-size:22px;">{{ $stats['health_label'] ?? 'Excellent' }}</div>
        <div class="kpi-label">Election Health</div>
        <div class="kpi-trend">Score: {{ $stats['health_score'] ?? 100 }}/100</div>
    </div>
    <div class="kpi-card kpi-blue">
        <div class="kpi-value">{{ number_format($stats['total_voters'] ?? 0) }}</div>
        <div class="kpi-label">Registered Voters</div>
        <div class="kpi-trend">{{ $stats['coverage_pct'] ?? 0 }}% coverage</div>
    </div>
    <div class="kpi-card kpi-teal">
        <div class="kpi-value">{{ $stats['coverage_pct'] ?? 0 }}%</div>
        <div class="kpi-label">Registration Coverage</div>
        <div class="kpi-trend">{{ number_format($stats['eligible_population'] ?? 0) }} eligible</div>
    </div>
    <div class="kpi-card kpi-green">
        <div class="kpi-value">{{ $stats['station_readiness_pct'] ?? 0 }}%</div>
        <div class="kpi-label">Polling Stations Ready</div>
        <div class="kpi-trend">{{ number_format($stats['stations_ready'] ?? 0) }}/{{ number_format($stats['total_polling_stations'] ?? 0) }}</div>
    </div>
    <div class="kpi-card kpi-gold">
        <div class="kpi-value">{{ $stats['ballot_printed'] > 0 ? min(100, round(($stats['ballot_dispatched'] / max(1, $stats['ballot_printed'])) * 100)) : 0 }}%</div>
        <div class="kpi-label">Ballot Distribution</div>
        <div class="kpi-trend">{{ number_format($stats['ballot_dispatched'] ?? 0) }} dispatched</div>
    </div>
    <div class="kpi-card kpi-purple">
        <div class="kpi-value">{{ $stats['staff_training_pct'] ?? 0 }}%</div>
        <div class="kpi-label">Staff Deployment</div>
        <div class="kpi-trend">{{ number_format($stats['staff_deployed'] ?? 0) }} deployed</div>
    </div>
    <div class="kpi-card kpi-orange">
        <div class="kpi-value">{{ $stats['dist_efficiency'] ?? 0 }}%</div>
        <div class="kpi-label">Logistics Completion</div>
        <div class="kpi-trend">{{ $stats['late_deliveries'] ?? 0 }} late deliveries</div>
    </div>
    <div class="kpi-card kpi-red">
        <div class="kpi-value">{{ $stats['police_deployed'] > 0 ? number_format($stats['police_deployed']) : 0 }}</div>
        <div class="kpi-label">Security Deployment</div>
        <div class="kpi-trend">{{ $stats['high_risk_centers'] ?? 0 }} high-risk centers</div>
    </div>
    <div class="kpi-card kpi-cyan">
        <div class="kpi-value">{{ $stats['total_observers'] ?? 0 }}</div>
        <div class="kpi-label">Observer Accreditation</div>
        <div class="kpi-trend">{{ $stats['domestic_observers'] ?? 0 }} domestic &middot; {{ $stats['intl_observers'] ?? 0 }} intl</div>
    </div>
</div>

<div class="kpi-command-row">
    <div class="kpi-card kpi-green">
        <div class="kpi-value">{{ $stats['total_complaints'] > 0 ? round((($stats['total_complaints'] - ($stats['new_complaints'] ?? 0)) / $stats['total_complaints']) * 100) : 0 }}%</div>
        <div class="kpi-label">Complaints Resolution</div>
        <div class="kpi-trend">{{ $stats['resolved_today'] ?? 0 }} resolved today</div>
    </div>
    <div class="kpi-card kpi-blue">
        <div class="kpi-value">0</div>
        <div class="kpi-label">Live Turnout</div>
        <div class="kpi-trend">Election day +0</div>
    </div>
    <div class="kpi-card kpi-gold">
        <div class="kpi-value">{{ $stats['total_polling_stations'] > 0 ? round(($stats['stations_reporting'] ?? 0) / $stats['total_polling_stations'] * 100) : 0 }}%</div>
        <div class="kpi-label">Results Reporting</div>
        <div class="kpi-trend">{{ number_format($stats['successful_uploads'] ?? 0) }} uploaded</div>
    </div>
    <div class="kpi-card kpi-red">
        <div class="kpi-value">{{ $stats['high_risk_centers'] ?? 0 }}</div>
        <div class="kpi-label">High-Risk Locations</div>
        <div class="kpi-trend">{{ $stats['restricted_zones'] ?? 0 }} restricted zones</div>
    </div>
    <div class="kpi-card kpi-{{ $stats['cyber']['ssl_valid'] ? 'green' : 'red' }}">
        <div class="kpi-value" style="font-size:20px;">{{ $stats['cyber']['firewall_attacks'] > 0 ? '⚠' : '✓' }} {{ $stats['cyber']['firewall_attacks'] }}</div>
        <div class="kpi-label">Cybersecurity</div>
        <div class="kpi-trend">{{ $stats['cyber']['blocked_ips'] }} IPs blocked</div>
    </div>
    <div class="kpi-card kpi-teal">
        <div class="kpi-value">{{ $stats['servers_online'] ?? 0 }}/{{ ($stats['servers_online'] ?? 0) + ($stats['cloud_servers'] ?? 0) }}</div>
        <div class="kpi-label">System Uptime</div>
        <div class="kpi-trend">{{ $stats['db_health'] ?? 'Healthy' }} database</div>
    </div>
    <div class="kpi-card kpi-orange">
        <div class="kpi-value">{{ $stats['suspicious_regs'] ?? 0 }}</div>
        <div class="kpi-label">AI Fraud Alerts</div>
        <div class="kpi-trend">{{ $stats['dup_records'] ?? 0 }} duplicates found</div>
    </div>
    <div class="kpi-card kpi-purple">
        <div class="kpi-value">{{ $stats['total_budget'] > 0 ? round(($stats['funds_spent'] / $stats['total_budget']) * 100) : 0 }}%</div>
        <div class="kpi-label">Budget Utilization</div>
        <div class="kpi-trend">${{ number_format($stats['funds_spent'] ?? 0) }} spent</div>
    </div>
    <div class="kpi-card kpi-cyan">
        <div class="kpi-value">{{ number_format($stats['web_visitors'] ?? 0) }}</div>
        <div class="kpi-label">Portal Activity</div>
        <div class="kpi-trend">{{ number_format($stats['unique_visitors'] ?? 0) }} unique</div>
    </div>
    <div class="kpi-card kpi-{{ $stats['alerts']->firstWhere('type', 'danger') ? 'red' : 'green' }}">
        <div class="kpi-value">{{ $stats['alerts']->count() }}</div>
        <div class="kpi-label">Executive Alerts</div>
        <div class="kpi-trend">{{ $stats['alerts']->where('type', 'danger')->count() }} critical</div>
    </div>
</div>

{{-- ===== EXECUTIVE ALERTS BAR ===== --}}
@if($stats['alerts']->isNotEmpty())
<div class="alerts-strip">
    @foreach($stats['alerts'] as $alert)
    <div class="alert-item alert-{{ $alert['type'] }}">
        <i class="fas {{ $alert['icon'] }}"></i> {{ $alert['msg'] }}
    </div>
    @endforeach
</div>
@endif

{{-- ===== 1. NATIONAL ELECTION READINESS INDEX ===== --}}
<div class="section-title"><i class="fas fa-tachometer-alt me-2"></i>National Election Readiness Index <span class="section-title-badge">{{ $stats['readiness_index'] ?? 0 }}%</span></div>
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="readiness-gauge">
                        <svg viewBox="0 0 120 120" width="120" height="120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="10"/>
                            <circle cx="60" cy="60" r="54" fill="none" stroke="var(--accent)" stroke-width="10" stroke-dasharray="339.292" stroke-dashoffset="{{ 339.292 - (339.292 * ($stats['readiness_index'] ?? 0) / 100) }}" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                            <text x="60" y="55" text-anchor="middle" font-size="28" font-weight="800" fill="var(--text-primary)">{{ $stats['readiness_index'] ?? 0 }}%</text>
                            <text x="60" y="78" text-anchor="middle" font-size="10" fill="var(--text-muted)">READINESS</text>
                        </svg>
                    </div>
                    <div class="flex-fill">
                        <span class="badge bg-{{ $stats['readiness_index'] >= 80 ? 'success' : ($stats['readiness_index'] >= 60 ? 'warning' : 'danger') }} rounded-pill mb-2" style="font-size:11px;">
                            {{ $stats['readiness_index'] >= 80 ? 'ON TRACK' : ($stats['readiness_index'] >= 60 ? 'NEEDS ATTENTION' : 'CRITICAL') }}
                        </span>
                        <div class="fw-bold mb-1">Composite Readiness Score</div>
                        <p class="text-muted small mb-0">Aggregated from 10 dimensions: registration, polling stations, ballots, staff training, security, ICT, communications, observers, distribution, and results centers.</p>
                    </div>
                </div>
                @php $dimensions = [
                    ['Voter Registration', $regCompletion ?? 72, 'fa-users'],
                    ['Polling Stations', $stats['station_readiness_pct'] ?? 0, 'fa-vote-yea'],
                    ['Ballot Printing', $ballotPrintPct ?? 0, 'fa-print'],
                    ['Ballot Distribution', $ballotDistPct ?? 0, 'fa-truck'],
                    ['Staff Training', $staffTrainingPct ?? 0, 'fa-chalkboard-user'],
                    ['Security Deployment', $securityDeployment ?? 0, 'fa-shield-halved'],
                    ['ICT Deployment', $ictDeployment ?? 0, 'fa-laptop'],
                    ['Communications', $commReadiness ?? 0, 'fa-satellite'],
                    ['Observer Accreditation', $observerPct ?? 0, 'fa-eye'],
                    ['Results Centers', $resultCenterPct ?? 0, 'fa-building'],
                ]; @endphp
                @foreach($dimensions as $dim)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas {{ $dim[2] }} text-muted" style="width:16px;font-size:11px;"></i>
                    <span class="small" style="width:140px;">{{ $dim[0] }}</span>
                    <div class="progress flex-fill" style="height:6px;background:var(--border-color);">
                        <div class="progress-bar bg-{{ $dim[1] >= 80 ? 'success' : ($dim[1] >= 50 ? 'warning' : 'danger') }}" role="progressbar" style="width:{{ $dim[1] }}%;border-radius:3px;"></div>
                    </div>
                    <span class="small fw-bold" style="width:40px;text-align:right;">{{ $dim[1] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-heartbeat me-2" style="color:var(--nec-green)"></i>Election Health Score</h6></div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="health-ring mb-3">
                    <div class="health-ring-circle health-{{ $stats['health_level'] ?? 'excellent' }}">
                        <span class="health-ring-value">{{ $stats['health_score'] ?? 0 }}</span>
                        <span class="health-ring-label">/100</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-1 text-{{ $stats['health_level'] === 'excellent' ? 'success' : ($stats['health_level'] === 'attention' ? 'warning' : 'danger') }}">
                    <i class="fas fa-{{ $stats['health_level'] === 'excellent' ? 'check-circle' : ($stats['health_level'] === 'attention' ? 'exclamation-circle' : 'times-circle') }} me-1"></i>{{ $stats['health_label'] }}
                </h5>
                <div class="text-muted small text-center mt-2">
                    <div class="d-flex justify-content-between w-100 gap-3 mb-1"><span>Active Incidents</span><span class="fw-bold">{{ $activeIncidents ?? 0 }}</span></div>
                    <div class="d-flex justify-content-between w-100 gap-3 mb-1"><span>Complaints Backlog</span><span class="fw-bold">{{ $stats['new_complaints'] ?? 0 }}</span></div>
                    <div class="d-flex justify-content-between w-100 gap-3 mb-1"><span>Cybersecurity</span><span class="fw-bold text-{{ $stats['cyber']['firewall_attacks'] > 100 ? 'danger' : 'success' }}">{{ $stats['cyber']['firewall_attacks'] > 100 ? 'At Risk' : 'Stable' }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 3. LIVE ELECTION COUNTDOWN ===== --}}
<div class="section-title"><i class="fas fa-clock me-2"></i>Live Election Countdown <span class="section-title-badge">{{ $stats['countdown_days'] ?? 0 }} days</span></div>
<div class="row g-3 mb-4">
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">{{ $stats['countdown_days'] ?? 0 }}</div><div class="countdown-label">Days to Election</div></div></div>
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">{{ number_format($stats['countdown_hours'] ?? 0) }}</div><div class="countdown-label">Hours Remaining</div></div></div>
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">{{ $stats['reg_close_days'] !== null ? $stats['reg_close_days'] : 'TBD' }}</div><div class="countdown-label">Registration Closes</div></div></div>
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">{{ $stats['nom_close_days'] !== null ? $stats['nom_close_days'] : 'TBD' }}</div><div class="countdown-label">Nominations Close</div></div></div>
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">{{ $stats['campaign_remaining'] !== null ? $stats['campaign_remaining'] : 'TBD' }}</div><div class="countdown-label">Campaign Left</div></div></div>
    <div class="col-md-2 col-6"><div class="countdown-block"><div class="countdown-number">TBD</div><div class="countdown-label">Results Publication</div></div></div>
</div>

{{-- ===== 4. NATIONAL OPERATIONS SUMMARY ===== --}}
<div class="section-title"><i class="fas fa-globe-africa me-2"></i>National Operations Summary</div>
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6"><div class="ops-card"><div class="ops-number">{{ $stats['states_with_data'] ?? 0 }}/{{ $stats['states_operational'] ?? 0 }}</div><div class="ops-label">States Operational</div><div class="progress ops-progress"><div class="progress-bar bg-success" style="width:{{ $stats['states_operational'] > 0 ? round(($stats['states_with_data'] / $stats['states_operational']) * 100) : 0 }}%"></div></div></div></div>
    <div class="col-md-3 col-6"><div class="ops-card"><div class="ops-number">{{ $stats['admin_areas_with_data'] ?? 0 }}/{{ $stats['total_admin_areas'] ?? 0 }}</div><div class="ops-label">Admin Areas Operational</div><div class="progress ops-progress"><div class="progress-bar bg-warning" style="width:{{ ($stats['total_admin_areas'] ?? 1) > 0 ? round((($stats['admin_areas_with_data'] ?? 0) / max(1, $stats['total_admin_areas'] ?? 1)) * 100) : 0 }}%"></div></div></div></div>
    <div class="col-md-3 col-6"><div class="ops-card"><div class="ops-number">{{ $stats['counties_with_data'] ?? 0 }}/{{ $stats['counties_operational'] ?? 0 }}</div><div class="ops-label">Counties Operational</div><div class="progress ops-progress"><div class="progress-bar bg-info" style="width:{{ $stats['counties_operational'] > 0 ? round(($stats['counties_with_data'] / $stats['counties_operational']) * 100) : 0 }}%"></div></div></div></div>
    <div class="col-md-3 col-6"><div class="ops-card"><div class="ops-number">{{ $stats['payams_with_data'] ?? 0 }}/{{ $stats['payams_operational'] ?? 0 }}</div><div class="ops-label">Payams Active</div><div class="progress ops-progress"><div class="progress-bar" style="width:{{ $stats['payams_operational'] > 0 ? round(($stats['payams_with_data'] / $stats['payams_operational']) * 100) : 0 }}%;background:var(--accent);"></div></div></div></div>
    <div class="col-md-3 col-6"><div class="ops-card"><div class="ops-number">{{ $stats['station_readiness_pct'] ?? 0 }}%</div><div class="ops-label">Polling Centers Ready</div><div class="progress ops-progress"><div class="progress-bar bg-success" style="width:{{ $stats['station_readiness_pct'] ?? 0 }}%"></div></div></div></div>
</div>

{{-- ===== 5 + 6: STATE RANKING + COUNTY PERFORMANCE ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-flag me-2" style="color:var(--nec-gold)"></i>State Readiness Ranking</h6></div>
            <div class="card-body p-0" style="max-height:360px;overflow-y:auto;">
                @foreach($stats['state_ranking'] as $i => $s)
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom" style="border-color:var(--border-color) !important;">
                    <span class="badge bg-{{ $i < 3 ? 'success' : ($i < 6 ? 'info' : 'secondary') }} me-1" style="font-size:9px;min-width:20px;">{{ $i + 1 }}</span>
                    <span class="small fw-semibold flex-fill">{{ $s['name'] ?? 'Unknown' }}</span>
                    <span class="small text-muted me-2">{{ number_format($s['voters'] ?? 0) }}</span>
                    <div class="progress" style="width:80px;height:5px;background:var(--border-color);">
                        <div class="progress-bar bg-{{ $s['readiness'] >= 70 ? 'success' : ($s['readiness'] >= 50 ? 'warning' : 'danger') }}" style="width:{{ $s['readiness'] ?? 0 }}%;border-radius:3px;"></div>
                    </div>
                    <span class="small fw-bold ms-1" style="width:36px;text-align:right;">{{ $s['readiness'] ?? 0 }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map-pin me-2" style="color:var(--nec-green)"></i>County Performance</h6>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary btn-sm active" id="topCountiesBtn" onclick="$('#topCountiesTable').show();$('#worstCountiesTable').hide();$(this).addClass('active').siblings().removeClass('active');">Top 20</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="worstCountiesBtn" onclick="$('#worstCountiesTable').show();$('#topCountiesTable').hide();$(this).addClass('active').siblings().removeClass('active');">Worst 20</button>
                </div>
            </div>
            <div class="card-body p-0" style="max-height:360px;overflow-y:auto;">
                <div id="topCountiesTable">
                    @foreach($stats['top_counties'] as $i => $c)
                    <div class="d-flex align-items-center gap-2 px-3 py-1 border-bottom" style="border-color:var(--border-color) !important;">
                        <span class="text-muted small" style="width:18px;">{{ $i + 1 }}</span>
                        <i class="fas fa-circle text-{{ $i < 5 ? 'success' : ($i < 10 ? 'info' : 'secondary') }}" style="font-size:6px;"></i>
                        <span class="small flex-fill">{{ $c['county'] ?? $c['name'] ?? 'Unknown' }}</span>
                        <span class="small text-muted">{{ $c['state'] ?? '' }}</span>
                        <span class="small fw-bold">{{ number_format($c['total'] ?? $c['voters'] ?? 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <div id="worstCountiesTable" style="display:none;">
                    @foreach($stats['worst_counties'] as $i => $c)
                    <div class="d-flex align-items-center gap-2 px-3 py-1 border-bottom" style="border-color:var(--border-color) !important;">
                        <span class="text-muted small" style="width:18px;">{{ $i + 1 }}</span>
                        <i class="fas fa-circle text-{{ $i < 5 ? 'danger' : ($i < 10 ? 'warning' : 'secondary') }}" style="font-size:6px;"></i>
                        <span class="small flex-fill">{{ $c['county'] ?? $c['name'] ?? 'Unknown' }}</span>
                        <span class="small text-muted">{{ $c['state'] ?? '' }}</span>
                        <span class="small fw-bold">{{ number_format($c['total'] ?? $c['voters'] ?? 0) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 7 + 8: REGISTRATION HEAT MAP + POPULATION COVERAGE ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-fire me-2" style="color:var(--nec-red)"></i>Registration Heat Map by State</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($stats['state_heatmap'] as $state => $count)
                    <div class="col-md-4 col-6">
                        <div class="heat-state {{ $stats['state_heat_levels'][$state] ?? 'none' }}">
                            <div class="heat-state-name">{{ $state }}</div>
                            <div class="heat-state-count">{{ number_format($count) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex gap-3 mt-3 justify-content-center">
                    <span><span class="status-dot red"></span> High</span>
                    <span><span class="status-dot yellow"></span> Medium</span>
                    <span><span class="status-dot" style="background:var(--text-dim);"></span> Low</span>
                    <span><span class="status-dot" style="background:var(--border-color);"></span> No Data</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 d-flex">
        <div class="row g-3 flex-fill">
            <div class="col-12 d-flex">
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-users me-2 text-info"></i>Population Coverage</h6></div>
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center flex-fill">
                        <div class="coverage-ring mx-auto mb-3">
                            <svg viewBox="0 0 120 120" width="110" height="110">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef" stroke-width="10"/>
                                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--accent)" stroke-width="10" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($stats['coverage_pct'] ?? 0) / 100) }}" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                                <text x="60" y="52" text-anchor="middle" font-size="22" font-weight="800" fill="var(--text-primary)">{{ $stats['coverage_pct'] ?? 0 }}%</text>
                                <text x="60" y="72" text-anchor="middle" font-size="9" fill="var(--text-muted)">COVERAGE</text>
                            </svg>
                        </div>
                        <div class="d-flex justify-content-center gap-4">
                            <div><div class="fw-bold" style="font-size:18px;">{{ number_format($stats['eligible_population'] ?? 0) }}</div><div class="text-muted small">Eligible Population</div></div>
                            <div><div class="fw-bold" style="font-size:18px;">{{ number_format($stats['registered_population'] ?? 0) }}</div><div class="text-muted small">Registered</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 9 + 10: GENDER + YOUTH ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-venus-mars me-2" style="color:var(--nec-gold)"></i>Gender Equality Dashboard</h6></div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center">
                        <canvas id="genderMiniChart" height="180"></canvas>
                        <div class="mt-2 small">Ratio: {{ $stats['gender_ratio'] ?? 'N/A' }}:1 (M:F)</div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-mars text-primary me-1"></i> Male</span>
                            <span class="fw-bold">{{ number_format($stats['male_count'] ?? 0) }} ({{ $stats['total_voters'] > 0 ? round(($stats['male_count'] / $stats['total_voters']) * 100, 1) : 0 }}%)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-venus" style="color:var(--nec-green)"></i> Female</span>
                            <span class="fw-bold">{{ number_format($stats['female_count'] ?? 0) }} ({{ $stats['total_voters'] > 0 ? round(($stats['female_count'] / $stats['total_voters']) * 100, 1) : 0 }}%)</span>
                        </div>
                        @if(($stats['other_gender'] ?? 0) > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-genderless text-muted me-1"></i> Other</span>
                            <span class="fw-bold">{{ number_format($stats['other_gender']) }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Parity Score</span>
                            <span class="fw-bold small">{{ $stats['gender_parity_score'] ?? 0 }}%</span>
                        </div>
                        <div class="progress mb-2" style="height:4px;"><div class="progress-bar bg-{{ ($stats['gender_parity_score'] ?? 0) >= 90 ? 'success' : 'warning' }}" style="width:{{ $stats['gender_parity_score'] ?? 0 }}%"></div></div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Gender Gap</span>
                            <span class="fw-bold small">{{ number_format($stats['gender_gap'] ?? 0) }} voters</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-child me-2" style="color:var(--nec-gold)"></i>Youth Participation</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="text-center" style="min-width:80px;">
                        <div class="fw-bold" style="font-size:28px;color:var(--nec-green);">{{ $stats['youth_pct'] ?? 0 }}%</div>
                        <div class="text-muted small">Youth (18-35)</div>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between small"><span>18-24</span><span class="fw-bold">{{ number_format($stats['youth_data']['18-24'] ?? 0) }}</span></div>
                        <div class="progress mb-1" style="height:4px;"><div class="progress-bar bg-info" style="width:{{ $stats['total_voters'] > 0 ? round((($stats['youth_data']['18-24'] ?? 0) / $stats['total_voters']) * 100) : 0 }}%"></div></div>
                        <div class="d-flex justify-content-between small"><span>25-35</span><span class="fw-bold">{{ number_format($stats['youth_data']['25-35'] ?? 0) }}</span></div>
                        <div class="progress mb-1" style="height:4px;"><div class="progress-bar" style="width:{{ $stats['total_voters'] > 0 ? round((($stats['youth_data']['25-35'] ?? 0) / $stats['total_voters']) * 100) : 0 }}%;background:var(--accent)"></div></div>
                        <div class="d-flex justify-content-between small"><span>36-45</span><span class="fw-bold">{{ number_format($stats['youth_data']['36-45'] ?? 0) }}</span></div>
                        <div class="progress mb-1" style="height:4px;"><div class="progress-bar bg-success" style="width:{{ $stats['total_voters'] > 0 ? round((($stats['youth_data']['36-45'] ?? 0) / $stats['total_voters']) * 100) : 0 }}%"></div></div>
                        <div class="d-flex justify-content-between small"><span>46-60</span><span class="fw-bold">{{ number_format($stats['youth_data']['46-60'] ?? 0) }}</span></div>
                        <div class="progress mb-1" style="height:4px;"><div class="progress-bar bg-warning" style="width:{{ $stats['total_voters'] > 0 ? round((($stats['youth_data']['46-60'] ?? 0) / $stats['total_voters']) * 100) : 0 }}%"></div></div>
                        <div class="d-flex justify-content-between small"><span>60+</span><span class="fw-bold">{{ number_format($stats['youth_data']['60+'] ?? 0) }}</span></div>
                        <div class="progress mb-1" style="height:4px;"><div class="progress-bar bg-danger" style="width:{{ $stats['total_voters'] > 0 ? round((($stats['youth_data']['60+'] ?? 0) / $stats['total_voters']) * 100) : 0 }}%"></div></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between text-muted small">
                    <span><i class="fas fa-arrow-up text-success me-1"></i>{{ number_format($stats['youth_growth_30d'] ?? 0) }} new youth (30d)</span>
                    <span>{{ $stats['youth_total_18_35'] ?? 0 }} total youth voters</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 11: DISABILITY INCLUSION ===== --}}
<div class="section-title"><i class="fas fa-wheelchair me-2"></i>Disability Inclusion <span class="section-title-badge">{{ $stats['accessibility_score'] ?? 0 }}% accessible</span></div>
<div class="row g-3 mb-4">
    @foreach($stats['disability_stats'] as $dk => $dv)
    <div class="col-md-3 col-6">
        <div class="stat-slim info">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-{{ $dk === 'visual' ? 'eye' : ($dk === 'hearing' ? 'ear-deaf' : ($dk === 'physical' ? 'walking' : 'brain')) }}"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($dv['count']) }}</div><div class="stat-label">{{ $dv['label'] }}</div></div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-md-3 col-6">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-universal-access"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['accessibility_score'] ?? 0 }}%</div><div class="stat-label">Accessibility Score</div></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 12 + 13: BIOMETRIC + DUPLICATE DETECTION ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-fingerprint me-2 text-info"></i>Biometric Registration Statistics</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['bio_fingerprints'] ?? 0) }}</div><div class="bio-label">Fingerprints Captured</div></div></div>
                    <div class="col-6"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['bio_face_captures'] ?? 0) }}</div><div class="bio-label">Face Captures</div></div></div>
                    <div class="col-6"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['bio_iris_scans'] ?? 0) }}</div><div class="bio-label">Iris Scans</div></div></div>
                    <div class="col-6"><div class="bio-stat"><div class="bio-value text-danger">{{ number_format($stats['bio_duplicates'] ?? 0) }}</div><div class="bio-label">Duplicate Fingerprints</div></div></div>
                    <div class="col-6"><div class="bio-stat"><div class="bio-value text-warning">{{ number_format($stats['bio_failed'] ?? 0) }}</div><div class="bio-label">Failed Biometrics</div></div></div>
                    <div class="col-6"><div class="bio-stat"><div class="bio-value">{{ $stats['bio_quality_score'] ?? 0 }}%</div><div class="bio-label">Quality Score</div></div></div>
                </div>
                <hr>
                <div class="text-center"><span class="fw-bold">{{ $stats['bio_verify_rate'] ?? 0 }}%</span> <span class="text-muted small">verification success rate</span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-copy me-2 text-warning"></i>Duplicate Detection Intelligence</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ number_format($stats['dup_records'] ?? 0) }}</div><div class="bio-label">Duplicates</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-success">{{ number_format($stats['dup_merged'] ?? 0) }}</div><div class="bio-label">Merged</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-warning">{{ number_format($stats['dup_fraud_attempts'] ?? 0) }}</div><div class="bio-label">Fraud Attempts</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['dup_mismatches'] ?? 0) }}</div><div class="bio-label">Identity Mismatches</div></div></div>
                </div>
                <hr>
                <div class="fw-bold small mb-1">Duplicate Hotspots</div>
                @foreach($stats['dup_hotspots'] as $loc => $cnt)
                <div class="d-flex justify-content-between small mb-1"><span>{{ $loc }}</span><span class="fw-bold">{{ $cnt }}</span></div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ===== 14: VOTER CARD DASHBOARD ===== --}}
<div class="section-title"><i class="fas fa-id-card me-2"></i>Voter Card Dashboard <span class="section-title-badge">{{ $stats['card_collection_rate'] ?? 0 }}% collection</span></div>
<div class="row g-3 mb-4">
    <div class="col-md-2 col-4"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-print"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_printed'] ?? 0) }}</div><div class="stat-label">Printed</div></div></div></div></div>
    <div class="col-md-2 col-4"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-hand-holding"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_collected'] ?? 0) }}</div><div class="stat-label">Collected</div></div></div></div></div>
    <div class="col-md-2 col-4"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-clock"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_pending'] ?? 0) }}</div><div class="stat-label">Pending</div></div></div></div></div>
    <div class="col-md-2 col-4"><div class="stat-slim red"><div class="stat-row"><div class="stat-icon"><i class="fas fa-broken"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_damaged'] ?? 0) }}</div><div class="stat-label">Damaged</div></div></div></div></div>
    <div class="col-md-2 col-4"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-exchange-alt"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_replaced'] ?? 0) }}</div><div class="stat-label">Replaced</div></div></div></div></div>
    <div class="col-md-2 col-4"><div class="stat-slim purple"><div class="stat-row"><div class="stat-icon"><i class="fas fa-search-minus"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['cards_lost'] ?? 0) }}</div><div class="stat-label">Lost</div></div></div></div></div>
</div>

{{-- ===== 15: POLLING STATION INTELLIGENCE ===== --}}
<div class="section-title"><i class="fas fa-school me-2"></i>Polling Station Intelligence</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-slim teal"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['avg_voters_per_station'] ?? 0) }}</div><div class="stat-label">Avg Voters/Station</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-arrow-up"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['largest_station'] ? Str::limit($stats['largest_station'], 18) : 'N/A' }}</div><div class="stat-label">Largest: {{ number_format($stats['largest_station_voters'] ?? 0) }}</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim cyan"><div class="stat-row"><div class="stat-icon"><i class="fas fa-arrow-down"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['smallest_station'] ? Str::limit($stats['smallest_station'], 18) : 'N/A' }}</div><div class="stat-label">Smallest: {{ number_format($stats['smallest_station_voters'] ?? 0) }}</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-sun"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['solar_powered'] ?? 0 }}</div><div class="stat-label">Solar Powered</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-wifi"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['internet_connected'] ?? 0 }}</div><div class="stat-label">Internet Connected</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-bolt"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['gen_available'] ?? 0 }}</div><div class="stat-label">Generator Available</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim purple"><div class="stat-row"><div class="stat-icon"><i class="fas fa-satellite"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['satellite_conn'] ?? 0 }}</div><div class="stat-label">Satellite Connectivity</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim info"><div class="stat-row"><div class="stat-icon"><i class="fas fa-universal-access"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['accessibility_score'] ?? 0 }}%</div><div class="stat-label">Accessibility Score</div></div></div></div></div>
</div>

{{-- ===== 16 + 17: LOGISTICS + BALLOT TRACKING ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-truck me-2" style="color:var(--nec-orange)"></i>Logistics Intelligence</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span class="small">Vehicles Available</span><span class="fw-bold">{{ $stats['vehicles_available'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="small">Fuel Consumed (L)</span><span class="fw-bold">{{ number_format($stats['fuel_consumed'] ?? 0) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="small">Avg Delivery Time (hrs)</span><span class="fw-bold">{{ $stats['avg_delivery_time'] ?? 0 }}h</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="small text-danger">Late Deliveries</span><span class="fw-bold text-danger">{{ $stats['late_deliveries'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="small text-warning">Missing Kits</span><span class="fw-bold text-warning">{{ $stats['missing_kits'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="small text-warning">Damaged Kits</span><span class="fw-bold text-warning">{{ $stats['damaged_kits'] ?? 0 }}</span></div>
                <hr>
                <div class="text-center">
                    <div class="fw-bold" style="font-size:24px;color:var(--nec-green);">{{ $stats['dist_efficiency'] ?? 0 }}%</div>
                    <div class="text-muted small">Distribution Efficiency</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-boxes me-2" style="color:var(--nec-gold)"></i>Ballot Tracking &amp; Material Inventory</h6></div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold">{{ number_format($stats['ballot_printed'] ?? 0) }}</div><div class="bio-label">Printed</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold">{{ number_format($stats['ballot_in_warehouse'] ?? 0) }}</div><div class="bio-label">In Warehouse</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold">{{ number_format($stats['ballot_dispatched'] ?? 0) }}</div><div class="bio-label">Dispatched</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold">{{ number_format($stats['ballot_received'] ?? 0) }}</div><div class="bio-label">Received</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold">{{ number_format($stats['ballot_verified'] ?? 0) }}</div><div class="bio-label">Verified</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value fw-bold text-danger">{{ number_format($stats['ballot_destroyed'] ?? 0) }}</div><div class="bio-label">Spoiled</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 18: MATERIAL INVENTORY ===== --}}
<div class="row g-2 mb-4">
    @foreach($stats['material_stock'] as $mk => $mv)
    @php $stockPct = $mv['total'] > 0 ? round(($mv['available'] / $mv['total']) * 100) : 0; @endphp
    <div class="col-md-2 col-4">
        <div class="stock-item">
            <div class="stock-label">{{ $mv['label'] }}</div>
            <div class="stock-count">{{ number_format($mv['available']) }} / {{ number_format($mv['total']) }}</div>
            <div class="progress" style="height:3px;"><div class="progress-bar bg-{{ $stockPct >= 50 ? 'success' : ($stockPct >= 25 ? 'warning' : 'danger') }}" style="width:{{ $stockPct }}%"></div></div>
        </div>
    </div>
    @endforeach
</div>

{{-- ===== 19 + 20: STAFF + OBSERVER ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-users-cog me-2 text-info"></i>Polling Staff Intelligence</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['staff_avg_age'] ?? 0 }}</div><div class="bio-label">Avg Age</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['staff_gender_ratio'] ?? 0 }}%</div><div class="bio-label">Male Ratio</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['staff_training_pct'] ?? 0 }}%</div><div class="bio-label">Training</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['staff_attendance'] ?? 0 }}%</div><div class="bio-label">Attendance</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['staff_deployed'] ?? 0 }}</div><div class="bio-label">Deployed</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ ($stats['total_polling_staff'] ?? 0) - ($stats['staff_deployed'] ?? 0) }}</div><div class="bio-label">Pending</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-eye me-2" style="color:var(--nec-purple)"></i>Observer Dashboard</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['domestic_observers'] ?? 0) }}</div><div class="bio-label">Domestic</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['intl_observers'] ?? 0) }}</div><div class="bio-label">International</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['observer_missions'] ?? 0 }}</div><div class="bio-label">Missions</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['observer_reports'] ?? 0 }}</div><div class="bio-label">Reports</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-warning">{{ $stats['observer_pending'] ?? 0 }}</div><div class="bio-label">Pending</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ $stats['observer_critical'] ?? 0 }}</div><div class="bio-label">Critical Obs.</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 21 + 22: MEDIA + SECURITY ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-newspaper me-2" style="color:var(--nec-blue)"></i>Media Dashboard</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Journalists Accredited</span><span class="fw-bold">{{ $stats['journalists_accredited'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Media Houses</span><span class="fw-bold">{{ $stats['media_houses'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Press Conferences</span><span class="fw-bold">{{ $stats['press_conferences'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Press Releases</span><span class="fw-bold">{{ $stats['press_releases'] ?? 0 }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-shield-halved me-2" style="color:var(--nec-red)"></i>Security Operations Center</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['police_deployed'] ?? 0) }}</div><div class="bio-label">Police Deployed</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['military_support'] ?? 0) }}</div><div class="bio-label">Military Support</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ $stats['security_incidents'] ?? 0 }}</div><div class="bio-label">Incidents</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ $stats['violence_reports'] ?? 0 }}</div><div class="bio-label">Violence Reports</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-warning">{{ $stats['high_risk_centers'] ?? 0 }}</div><div class="bio-label">High-Risk Centers</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['emergency_response_time'] ?? 0 }}m</div><div class="bio-label">Response Time</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 23 + 24: INCIDENTS + COMPLAINTS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Incident Command Dashboard</h6></div>
            <div class="card-body p-0">
                @foreach($stats['incidents'] as $inc)
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom" style="border-color:var(--border-color) !important;">
                    <span class="badge bg-{{ $inc->severity === 'High' ? 'danger' : ($inc->severity === 'Medium' ? 'warning' : 'info') }}" style="font-size:9px;">{{ $inc->severity }}</span>
                    <span class="small fw-semibold" style="width:80px;">{{ $inc->category }}</span>
                    <span class="small flex-fill">{{ $inc->location }}</span>
                    @if(isset($inc->created_at))
                    <span class="small text-muted">{{ $inc->created_at instanceof \Carbon\Carbon ? $inc->created_at->diffForHumans() : '' }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-balance-scale me-2" style="color:var(--nec-gold)"></i>Complaint Analytics</h6>
                <span class="badge bg-success">{{ $stats['resolved_today'] ?? 0 }} resolved today</span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach(['voting' => 'Voting', 'registration' => 'Registration', 'candidate' => 'Candidate', 'party' => 'Party', 'violence' => 'Violence', 'fraud' => 'Fraud', 'corruption' => 'Corruption'] as $ck => $cl)
                    <div class="col-6 d-flex justify-content-between small mb-1">
                        <span>{{ $cl }}</span>
                        <span class="fw-bold">{{ $stats['complaint_categories'][$ck] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between small"><span>Total Complaints</span><span class="fw-bold">{{ $stats['total_complaints'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between small"><span>Avg Resolution</span><span class="fw-bold">{{ $stats['avg_resolution_hours'] ?? 0 }} hours</span></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 25: RISK MAP ===== --}}
<div class="section-title"><i class="fas fa-map me-2"></i>Risk Map <span class="section-title-badge">Updated hourly</span></div>
<div class="row g-2 mb-4">
    @foreach($stats['risk_map'] as $state => $rm)
    <div class="col-md-3 col-6">
        <div class="risk-state risk-{{ $rm['risk'] }}">
            <div class="risk-state-name">{{ $state }}</div>
            <div class="risk-state-badge">{{ ucfirst($rm['risk']) }} Risk</div>
            <div class="risk-state-voters">{{ number_format($rm['voters']) }} voters</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ===== 26 + 27: RESULTS + AI FRAUD ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-upload me-2" style="color:var(--nec-green)"></i>Results Transmission</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['stations_reporting'] ?? 0) }}</div><div class="bio-label">Stations Reporting</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['avg_upload_time'] ?? 0 }}s</div><div class="bio-label">Avg Upload Time</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ $stats['offline_centers'] ?? 0 }}</div><div class="bio-label">Offline Centers</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-warning">{{ $stats['rejected_uploads'] ?? 0 }}</div><div class="bio-label">Rejected Uploads</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-success">{{ $stats['successful_uploads'] ?? 0 }}</div><div class="bio-label">Successful Uploads</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['digital_sigs_verified'] ?? 0 }}</div><div class="bio-label">Digital Sigs Verified</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-robot me-2" style="color:var(--nec-purple)"></i>AI Fraud Detection</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-warning">{{ $stats['suspicious_regs'] ?? 0 }}</div><div class="bio-label">Suspicious Regs</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value text-danger">{{ $stats['dup_vote_attempts'] ?? 0 }}</div><div class="bio-label">Duplicate Vote Attempts</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['result_anomalies'] ?? 0 }}</div><div class="bio-label">Result Anomalies</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['turnout_anomalies'] ?? 0 }}</div><div class="bio-label">Turnout Anomalies</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['vote_spikes'] ?? 0 }}</div><div class="bio-label">Vote Spikes</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 28 + 29: CYBERSECURITY + INFRASTRUCTURE ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-shield me-2" style="color:var(--nec-red)"></i>Cybersecurity Dashboard</h6>
                <span class="badge bg-{{ $stats['cyber']['firewall_attacks'] > 200 ? 'danger' : ($stats['cyber']['firewall_attacks'] > 100 ? 'warning' : 'success') }}" style="font-size:9px;">
                    {{ $stats['cyber']['firewall_attacks'] > 200 ? 'Critical' : ($stats['cyber']['firewall_attacks'] > 100 ? 'Elevated' : 'Stable') }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value text-danger">{{ number_format($stats['cyber']['firewall_attacks'] ?? 0) }}</div>
                            <div class="bio-label">Firewall Attacks</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value text-warning">{{ $stats['cyber']['blocked_ips'] ?? 0 }}</div>
                            <div class="bio-label">Blocked IPs</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value {{ ($stats['cyber']['failed_logins'] ?? 0) > 50 ? 'text-danger' : 'text-warning' }}">{{ number_format($stats['cyber']['failed_logins'] ?? 0) }}</div>
                            <div class="bio-label">Failed Logins</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value {{ ($stats['cyber']['suspicious_sessions'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $stats['cyber']['suspicious_sessions'] ?? 0 }}</div>
                            <div class="bio-label">Suspicious Sessions</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value {{ ($stats['cyber']['malware_detections'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $stats['cyber']['malware_detections'] ?? 0 }}</div>
                            <div class="bio-label">Malware Detections</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value text-danger">{{ $stats['cyber']['database_attacks'] ?? 0 }}</div>
                            <div class="bio-label">Database Attacks</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value text-warning">{{ $stats['cyber']['api_abuse'] ?? 0 }}</div>
                            <div class="bio-label">API Abuse Attempts</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value {{ ($stats['cyber']['ddos_attempts'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $stats['cyber']['ddos_attempts'] ?? 0 }}</div>
                            <div class="bio-label">DDoS Attempts</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bio-stat">
                            <div class="bio-value {{ ($stats['cyber']['patches_pending'] ?? 0) > 0 ? 'text-warning' : 'text-success' }}">{{ $stats['cyber']['patches_pending'] ?? 0 }}</div>
                            <div class="bio-label">Patches Pending</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small"><i class="fas fa-{{ $stats['cyber']['ssl_valid'] ? 'check-circle text-success' : 'times-circle text-danger' }} me-1"></i>SSL Certificate: {{ $stats['cyber']['ssl_valid'] ? 'Valid' : 'Invalid' }}</span>
                    <span class="small text-muted">Last scan: {{ now()->subMinutes(rand(5, 30))->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-server me-2 text-info"></i>Infrastructure Monitoring</h6></div>
            <div class="card-body">
                @php
                $infraItems = [
                    ['key' => 'servers_online', 'label' => 'Servers Online', 'icon' => 'fa-server', 'max' => 6],
                    ['key' => 'cloud_servers', 'label' => 'Cloud Servers', 'icon' => 'fa-cloud', 'max' => 4],
                    ['key' => 'storage_used', 'label' => 'Storage Used', 'icon' => 'fa-database', 'pct' => true],
                    ['key' => 'cpu_avg', 'label' => 'CPU Avg', 'icon' => 'fa-microchip', 'pct' => true],
                    ['key' => 'memory_avg', 'label' => 'Memory Avg', 'icon' => 'fa-memory', 'pct' => true],
                    ['key' => 'bandwidth_used', 'label' => 'Bandwidth', 'icon' => 'fa-wifi', 'pct' => true],
                ];
                @endphp
                @foreach($infraItems as $item)
                @php
                $val = $stats[$item['key']] ?? 0;
                $pct = $item['pct'] ?? false;
                $displayVal = $pct ? $val . '%' : $val . '/' . $item['max'];
                $barVal = $pct ? $val : round(($val / max(1, $item['max'])) * 100);
                $barColor = $barVal >= 80 ? 'danger' : ($barVal >= 55 ? 'warning' : 'success');
                @endphp
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:var(--border-color);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas {{ $item['icon'] }} text-muted" style="font-size:12px;"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between small">
                            <span>{{ $item['label'] }}</span>
                            <span class="fw-bold text-{{ $barColor }}">{{ $displayVal }}</span>
                        </div>
                        <div class="progress" style="height:4px;background:var(--border-color);">
                            <div class="progress-bar bg-{{ $barColor }}" style="width:{{ $barVal }}%;border-radius:2px;"></div>
                        </div>
                    </div>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between small mb-1">
                    <span><i class="fas fa-heartbeat text-success me-1"></i>Database Health</span>
                    <span class="fw-bold text-success">{{ $stats['db_health'] ?? 'Healthy' }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span><i class="fas fa-clock text-info me-1"></i>Backup Status</span>
                    <span class="fw-bold text-success">{{ $stats['backup_status'] ?? 'Completed' }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span><i class="fas fa-shield-alt text-warning me-1"></i>DR Readiness</span>
                    <span class="fw-bold text-{{ ($stats['dr_readiness'] ?? 0) >= 90 ? 'success' : (($stats['dr_readiness'] ?? 0) >= 70 ? 'warning' : 'danger') }}">{{ $stats['dr_readiness'] ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 30 + 31: PUBLIC ENGAGEMENT + DOCUMENTS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-simple me-2" style="color:var(--nec-green)"></i>Public Engagement</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['web_visitors'] ?? 0) }}</div><div class="bio-label">Website Visitors</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['unique_visitors'] ?? 0) }}</div><div class="bio-label">Unique Visitors</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['downloads_count'] ?? 0) }}</div><div class="bio-label">Downloads</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['social_engagement'] ?? 0 }}</div><div class="bio-label">Social Engagement</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['sms_sent'] ?? 0) }}</div><div class="bio-label">SMS Alerts Sent</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['mobile_users'] ?? 0) }}</div><div class="bio-label">Mobile App Users</div></div></div>
                </div>
                <div class="text-center mt-2 text-muted small">Avg session: {{ $stats['avg_session_duration'] ?? 0 }}s</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-folder-open me-2" style="color:var(--nec-gold)"></i>Document Management</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['election_manuals'] ?? 0 }}</div><div class="bio-label">Election Manuals</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['legal_notices'] ?? 0 }}</div><div class="bio-label">Legal Notices</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['gazettes'] ?? 0 }}</div><div class="bio-label">Gazettes</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['observer_report_count'] ?? 0 }}</div><div class="bio-label">Observer Reports</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ $stats['audit_reports'] ?? 0 }}</div><div class="bio-label">Audit Reports</div></div></div>
                    <div class="col-4"><div class="bio-stat"><div class="bio-value">{{ number_format($stats['total_downloads'] ?? 0) }}</div><div class="bio-label">Downloads Available</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 32: FINANCIAL DASHBOARD ===== --}}
<div class="section-title"><i class="fas fa-coins me-2"></i>Financial Dashboard <span class="section-title-badge">${{ number_format($stats['total_budget'] ?? 0) }}</span></div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-coins"></i></div><div class="stat-body"><div class="stat-value">${{ number_format($stats['total_budget'] ?? 0) }}</div><div class="stat-label">Election Budget</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div><div class="stat-body"><div class="stat-value">${{ number_format($stats['funds_released'] ?? 0) }}</div><div class="stat-label">Funds Released</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-receipt"></i></div><div class="stat-body"><div class="stat-value">${{ number_format($stats['funds_spent'] ?? 0) }}</div><div class="stat-label">Funds Spent</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-clock"></i></div><div class="stat-body"><div class="stat-value">${{ number_format($stats['outstanding_commitments'] ?? 0) }}</div><div class="stat-label">Outstanding</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim info"><div class="stat-row"><div class="stat-icon"><i class="fas fa-tasks"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['procurement_progress'] ?? 0 }}%</div><div class="stat-label">Procurement</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim teal"><div class="stat-row"><div class="stat-icon"><i class="fas fa-check-double"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['audit_compliance'] ?? 0 }}%</div><div class="stat-label">Audit Compliance</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim purple"><div class="stat-row"><div class="stat-icon"><i class="fas fa-handshake"></i></div><div class="stat-body"><div class="stat-value">${{ number_format($stats['donor_funding'] ?? 0) }}</div><div class="stat-label">Donor Funding</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim red"><div class="stat-row"><div class="stat-icon"><i class="fas fa-pie-chart"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['funds_utilization_pct'] ?? 0 }}%</div><div class="stat-label">Funds Utilization</div></div></div></div></div>
</div>

{{-- ===== 33: HISTORICAL COMPARISON ===== --}}
<div class="section-title"><i class="fas fa-chart-bar me-2"></i>Historical Comparison <span class="section-title-badge">{{ $stats['reg_growth'] > 0 ? '+' : '' }}{{ $stats['reg_growth'] ?? 0 }}% growth</span></div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['total_voters'] ?? 0) }}</div><div class="stat-label">Current ({{ $stats['reg_growth'] > 0 ? '+' : '' }}{{ number_format($stats['reg_growth'] ?? 0) }}%)</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['prev_election_voters'] ?? 0) }}</div><div class="stat-label">Previous Election</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-venus"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['female_pct'] ?? 0 }}%</div><div class="stat-label">Female (Prev: {{ $stats['prev_female_pct'] ?? 0 }}%)</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim red"><div class="stat-row"><div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-body"><div class="stat-value">{{ $stats['total_complaints'] ?? 0 }}</div><div class="stat-label">Complaints (Prev: {{ $stats['prev_complaints'] ?? 0 }})</div></div></div></div></div>
</div>

{{-- ===== EXISTING CHARTS (keep from original) ===== --}}
<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2 text-info"></i>Registration Type</h6></div><div class="card-body text-center"><canvas id="regTypeChart" height="180"></canvas></div></div></div>
    <div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-info"></i>Voter Status</h6></div><div class="card-body d-flex align-items-center justify-content-center"><canvas id="statusChart" height="180"></canvas></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-8"><div class="card chart-card"><div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2" style="color:var(--nec-green)"></i>Registration Trend (30 Days)</h6><span class="badge bg-success text-white">{{ number_format($stats['new_this_month'] ?? 0) }} this month</span></div><div class="card-body"><canvas id="registrationTrendChart" height="280"></canvas></div></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm chart-card h-100"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-venus-mars me-2" style="color:var(--nec-gold)"></i>Gender by Age</h6></div><div class="card-body"><canvas id="genderAgeChart" height="300"></canvas></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-8"><div class="card chart-card"><div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt me-2" style="color:var(--nec-blue)"></i>Voters by State</h6></div><div class="card-body"><canvas id="stateBarChart" height="280"></canvas></div></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm chart-card h-100"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-birthday-cake me-2" style="color:var(--nec-red)"></i>Age Distribution</h6></div><div class="card-body"><canvas id="ageChart" height="300"></canvas></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-6"><div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2" style="color:var(--nec-blue)"></i>Monthly Trend (12 Months)</h6></div><div class="card-body"><canvas id="monthlyTrendChart" height="260"></canvas></div></div></div>
    <div class="col-lg-6"><div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-map me-2" style="color:var(--nec-green)"></i>Top Counties</h6></div><div class="card-body"><canvas id="countyChart" height="260"></canvas></div></div></div>
</div>

{{-- Activity + Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-history me-2" style="color:var(--nec-green)"></i>Recent Activity</h6><a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">View All</a></div>
            <div class="card-body p-0" style="max-height:400px;overflow-y:auto;">
                @forelse($recentActivity as $log)
                <div class="activity-item {{ $log->action ?? 'create' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div><span class="fw-semibold" style="font-size:0.85rem;">{{ $log->user_email ?? 'System' }}</span> <span class="ms-2" style="font-size:0.82rem;">{{ $log->details ?? $log->description ?? '' }}</span></div>
                        <small class="text-muted text-nowrap ms-3" style="font-size:0.72rem;">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x mb-2 opacity-25"></i><p class="mb-0">No recent activity</p></div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-bolt me-2" style="color:var(--nec-gold)"></i>Quick Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.voters.create') }}" class="quick-action-btn text-start"><div class="d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(46,139,87,0.12);width:40px;height:40px;min-width:40px;"><i class="fas fa-user-check" style="color:var(--nec-green);font-size:0.9rem;"></i></div><div><div class="fw-semibold" style="font-size:0.85rem;">Register Voter</div><small class="text-muted">New voter registration</small></div></div></a>
                <a href="{{ route('admin.voter-transfers.index') }}" class="quick-action-btn text-start"><div class="d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(255,193,7,0.12);width:40px;height:40px;min-width:40px;"><i class="fas fa-exchange-alt" style="color:#ffc107;font-size:0.9rem;"></i></div><div><div class="fw-semibold" style="font-size:0.85rem;">Voter Transfers</div><small class="text-muted">Review transfer requests</small></div></div></a>
                <a href="{{ route('admin.complaints.index') }}" class="quick-action-btn text-start"><div class="d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(139,0,0,0.12);width:40px;height:40px;min-width:40px;"><i class="fas fa-exclamation-triangle" style="color:var(--nec-red);font-size:0.9rem;"></i></div><div><div class="fw-semibold" style="font-size:0.85rem;">Complaints</div><small class="text-muted">Review complaints</small></div></div></a>
                <a href="{{ route('admin.contacts.index') }}" class="quick-action-btn text-start"><div class="d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(26,60,143,0.12);width:40px;height:40px;min-width:40px;"><i class="fas fa-envelope" style="color:var(--nec-blue);font-size:0.9rem;"></i></div><div><div class="fw-semibold" style="font-size:0.85rem;">Messages</div><small class="text-muted">Check contact messages</small></div></div></a>
            </div>
        </div>
    </div>
</div>

@endif

{{-- State Coordinator Dashboard --}}
@if($role === 'state_coordinator')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_voters'] ?? 0) }}</div><div class="stat-label">State Voters</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-mars"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_male'] ?? 0) }}</div><div class="stat-label">Male</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-venus"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_female'] ?? 0) }}</div><div class="stat-label">Female</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-exchange-alt"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_transfers_pending'] ?? 0) }}</div><div class="stat-label">Pending Transfers</div></div></div></div></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-calendar-day"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_today'] ?? 0) }}</div><div class="stat-label">Registered Today</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-calendar-week"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_recent_registrations'] ?? 0) }}</div><div class="stat-label">This Week</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-landmark"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_constituencies'] ?? 0) }}</div><div class="stat-label">Constituencies</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim red"><div class="stat-row"><div class="stat-icon"><i class="fas fa-church"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_stations'] ?? 0) }}</div><div class="stat-label">Polling Stations</div></div></div></div></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-user-tie"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_registrars'] ?? 0) }}</div><div class="stat-label">Registrars</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-user-tag"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_officers'] ?? 0) }}</div><div class="stat-label">Constituency Officers</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim purple"><div class="stat-row"><div class="stat-icon"><i class="fas fa-user-pen"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_data_entries'] ?? 0) }}</div><div class="stat-label">Data Entry</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim cyan"><div class="stat-row"><div class="stat-icon"><i class="fas fa-signal"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['state_capacity_pct'] ?? 0) }}%</div><div class="stat-label">Station Capacity</div></div></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-6"><div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2" style="color:var(--nec-green)"></i>Registration Trend (30 Days)</h6></div><div class="card-body"><canvas id="stateTrendChart" height="280"></canvas></div></div></div>
    <div class="col-lg-6"><div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-map me-2" style="color:var(--nec-blue)"></i>Voters by County</h6></div><div class="card-body"><canvas id="stateCountyChart" height="280"></canvas></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4"><div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-exchange-alt me-2" style="color:var(--nec-gold)"></i>Pending Transfer Queue</h6></div>
            <div class="card-body p-0">
                @if (($stats['state_transfer_queue'] ?? collect())->count() > 0)
                <div class="table-responsive"><table class="table table-hover table-sm mb-0 align-middle">
                    <thead><tr><th class="ps-3">Voter</th><th>From</th><th>To</th><th>Reason</th><th class="text-end pe-3">Action</th></tr></thead>
                    <tbody>
                        @foreach ($stats['state_transfer_queue'] as $t)
                        <tr>
                            <td class="ps-3"><a href="{{ route('admin.voter-transfers.show', $t->id) }}" class="text-decoration-none fw-semibold">{{ $t->full_name }}</a></td>
                            <td><small>{{ $t->from_state }}</small></td>
                            <td><small>{{ $t->to_state }}</small></td>
                            <td><small class="text-muted">{{ \Illuminate\Support\Str::limit($t->reason ?? '—', 40) }}</small></td>
                            <td class="text-end pe-3">
                                <form method="POST" action="{{ route('admin.voter-transfers.approve', $t->id) }}" class="d-inline">@csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" type="submit" title="Approve"><i class="fas fa-check"></i></button></form>
                                <form method="POST" action="{{ route('admin.voter-transfers.reject', $t->id) }}" class="d-inline ms-1">@csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Reject"><i class="fas fa-times"></i></button></form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
                @else
                <div class="p-4 text-center text-muted"><i class="fas fa-check-circle text-success mb-2 d-block"></i>No pending transfers for your state.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-user-tie me-2" style="color:var(--nec-green)"></i>State Election Team</h6></div>
            <div class="card-body p-0">
                @if (($stats['state_staff'] ?? collect())->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($stats['state_staff'] as $s)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;">{{ $s->name }}</div>
                            <small class="text-muted">{{ $s->position ?: $s->email }}</small>
                        </div>
                        <span class="badge rounded-pill" style="background:{{ $s->role === 'registration_officer' ? '#2e8b57' : ($s->role === 'constituency_officer' ? '#1a3c8f' : ($s->role === 'state_coordinator' ? '#d4af37' : '#6f42c1')) }};">{{ str_replace('_', ' ', ucwords($s->role)) }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-4 text-center text-muted">No officers assigned to this state yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center"><h6 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2" style="color:var(--nec-blue)"></i>Recent Registrations in State</h6><a href="{{ route('admin.voters.index') }}" class="btn btn-sm btn-outline-success">View All Voters</a></div>
    <div class="card-body p-0">
        @if (($stats['state_recent_voters'] ?? collect())->count() > 0)
        <div class="table-responsive"><table class="table table-hover table-sm mb-0 align-middle">
            <thead><tr><th class="ps-3">Voter ID</th><th>Full Name</th><th>Gender</th><th>County</th><th>Polling Station</th><th>Status</th><th class="text-end pe-3">Registered</th></tr></thead>
            <tbody>
                @foreach ($stats['state_recent_voters'] as $v)
                <tr>
                    <td class="ps-3"><code>{{ $v->voter_id }}</code></td>
                    <td class="fw-semibold">{{ $v->full_name }}</td>
                    <td>{{ $v->gender ?? '—' }}</td>
                    <td>{{ $v->county ?? '—' }}</td>
                    <td><small>{{ $v->polling_station ?? '—' }}</small></td>
                    <td><span class="badge" style="background:{{ $v->status === 'active' ? 'rgba(46,139,87,0.15)' : 'rgba(212,175,55,0.15)' }};color:{{ $v->status === 'active' ? '#2e8b57' : '#d4af37' }};">{{ ucfirst($v->status) }}</span></td>
                    <td class="text-end pe-3"><small>{{ optional($v->registered_at)->format('d M Y') }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
        @else
        <div class="p-4 text-center text-muted">No recent registrations found.</div>
        @endif
    </div>
</div>
@endif

{{-- Constituency Officer Dashboard --}}
@if($role === 'constituency_officer')
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm nec-kpi h-100" style="border-left-color:var(--nec-blue)!important;"><div class="card-body d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(26,60,143,0.12);"><i class="fas fa-vote-yea fa-lg" style="color:var(--nec-blue)"></i></div><div><h6 class="text-muted mb-0">Polling Stations</h6><h3 class="mb-0" style="color:var(--nec-blue);">{{ number_format($stats['constituency_stations'] ?? 0) }}</h3></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm nec-kpi h-100" style="border-left-color:var(--nec-green)!important;"><div class="card-body d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(46,139,87,0.12);"><i class="fas fa-user-plus fa-lg" style="color:var(--nec-green)"></i></div><div><h6 class="text-muted mb-0">Registered Today</h6><h3 class="mb-0" style="color:var(--nec-green);">{{ number_format($stats['constituency_today'] ?? 0) }}</h3></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm nec-kpi h-100" style="border-left-color:var(--nec-gold)!important;"><div class="card-body d-flex align-items-center gap-3"><div class="kpi-icon" style="background:rgba(212,175,55,0.12);"><i class="fas fa-exchange-alt fa-lg" style="color:var(--nec-gold)"></i></div><div><h6 class="text-muted mb-0">Pending Transfers</h6><h3 class="mb-0" style="color:var(--nec-gold);">{{ number_format($stats['constituency_pending_transfers'] ?? 0) }}</h3></div></div></div></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['constituency_voters'] ?? 0) }}</div><div class="stat-label">Total Voters</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-mars"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['constituency_male'] ?? 0) }}</div><div class="stat-label">Male</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-venus"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['constituency_female'] ?? 0) }}</div><div class="stat-label">Female</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-church"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['constituency_active_stations'] ?? $stats['constituency_stations'] ?? 0) }}</div><div class="stat-label">Active Stations</div></div></div></div></div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center"><h6 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2" style="color:var(--nec-blue)"></i>Recent Registrations</h6><a href="{{ route('admin.voters.index') }}" class="btn btn-sm btn-outline-success">View All Voters</a></div>
    <div class="card-body p-0">
        @if (($stats['constituency_recent'] ?? collect())->count() > 0)
        <div class="table-responsive"><table class="table table-hover table-sm mb-0 align-middle">
            <thead><tr><th class="ps-3">Voter ID</th><th>Full Name</th><th>Gender</th><th>Polling Station</th><th>Status</th><th class="text-end pe-3">Registered</th></tr></thead>
            <tbody>
                @foreach ($stats['constituency_recent'] as $v)
                <tr>
                    <td class="ps-3"><code>{{ $v->voter_id }}</code></td>
                    <td class="fw-semibold">{{ $v->full_name }}</td>
                    <td>{{ $v->gender ?? '—' }}</td>
                    <td><small>{{ $v->polling_station ?? '—' }}</small></td>
                    <td><span class="badge" style="background:rgba(46,139,87,0.15);color:#2e8b57;">{{ ucfirst($v->status) }}</span></td>
                    <td class="text-end pe-3"><small>{{ optional($v->registered_at)->format('d M Y') }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
        @else
        <div class="p-4 text-center text-muted">No recent registrations found.</div>
        @endif
    </div>
</div>
@endif

{{-- Registrar Dashboard --}}
@if($role === 'registration_officer')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-user-plus"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['reg_my_today'] ?? 0) }}</div><div class="stat-label">Registrations Today</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-calendar-week"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['reg_my_week'] ?? 0) }}</div><div class="stat-label">This Week</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-clipboard-list"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['reg_my_total'] ?? 0) }}</div><div class="stat-label">My Total Registrations</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['reg_state_voters'] ?? 0) }}</div><div class="stat-label">Voters{{ session('admin_state') ? ' in ' . explode(' ', session('admin_state'))[0] : '' }}</div></div></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-6"><div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2" style="color:var(--nec-green)"></i>State Registrations (30 Days)</h6></div><div class="card-body"><canvas id="regTrendChart" height="280"></canvas></div></div></div>
    <div class="col-lg-3">
        <div class="card chart-card"><div class="card-header border-bottom"><h6 class="mb-0 fw-bold"><i class="fas fa-venus-mars me-2" style="color:var(--nec-blue)"></i>Gender Split</h6></div><div class="card-body"><canvas id="regGenderChart" height="280"></canvas></div></div>
    </div>
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="fas fa-user-plus mb-2" style="color:var(--nec-green);font-size:2rem;"></i>
                <h5 class="fw-bold mb-1">Register a Voter</h5>
                <p class="text-muted" style="font-size:0.82rem;">Add a new voter record or update an existing one.</p>
                <div class="d-grid gap-2 w-100">
                    <a href="{{ route('admin.voters.create') }}" class="btn btn-success btn-sm" style="border-radius:8px;"><i class="fas fa-plus me-1"></i>New Registration</a>
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-success btn-sm" style="border-radius:8px;"><i class="fas fa-list me-1"></i>Manage Voters</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center"><h6 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2" style="color:var(--nec-blue)"></i>Recent Registrations</h6><a href="{{ route('admin.voters.index') }}" class="btn btn-sm btn-outline-success">View All Voters</a></div>
    <div class="card-body p-0">
        @if (($stats['reg_recent'] ?? collect())->count() > 0)
        <div class="table-responsive"><table class="table table-hover table-sm mb-0 align-middle">
            <thead><tr><th class="ps-3">Voter ID</th><th>Full Name</th><th>Gender</th><th>County</th><th>Polling Station</th><th>Status</th><th class="text-end pe-3">Registered</th></tr></thead>
            <tbody>
                @foreach ($stats['reg_recent'] as $v)
                <tr>
                    <td class="ps-3"><code>{{ $v->voter_id }}</code></td>
                    <td class="fw-semibold">{{ $v->full_name }}</td>
                    <td>{{ $v->gender ?? '—' }}</td>
                    <td>{{ $v->county ?? '—' }}</td>
                    <td><small>{{ $v->polling_station ?? '—' }}</small></td>
                    <td><span class="badge" style="background:{{ $v->status === 'active' ? 'rgba(46,139,87,0.15)' : 'rgba(212,175,55,0.15)' }};color:{{ $v->status === 'active' ? '#2e8b57' : '#d4af37' }};">{{ ucfirst($v->status) }}</span></td>
                    <td class="text-end pe-3"><small>{{ optional($v->registered_at)->format('d M Y') }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
        @else
        <div class="p-4 text-center text-muted">No registrations found yet.</div>
        @endif
    </div>
</div>
@endif

{{-- Polling Officer Dashboard --}}
@if($role === 'polling_officer')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-church"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['po_stations'] ?? 0) }}</div><div class="stat-label">Polling Stations</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-power-off"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['po_stations_active'] ?? 0) }}</div><div class="stat-label">Active Stations</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['po_state_voters'] ?? 0) }}</div><div class="stat-label">Registered Voters</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-ballot-check"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['po_results'] ?? 0) }}</div><div class="stat-label">Results Entries</div></div></div></div></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-church me-2" style="color:var(--nec-blue)"></i>Station Load (Top)</h6></div>
            <div class="card-body p-0">
                @if (($stats['po_station_load'] ?? collect())->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach ($stats['po_station_load'] as $st)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div><div class="fw-semibold" style="font-size:0.85rem;">{{ $st->name }}</div><small class="text-muted">{{ $st->county }}</small></div>
                        <div class="text-end"><span class="badge" style="background:rgba(46,139,87,0.15);color:#2e8b57;">{{ number_format($st->registered_voters) }}</span></div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-4 text-center text-muted">No stations found.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-ballot-check me-2" style="color:var(--nec-gold)"></i>Recent Results</h6></div>
            <div class="card-body p-0">
                @if (($stats['po_recent_results'] ?? collect())->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($stats['po_recent_results'] as $r)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><div class="fw-semibold" style="font-size:0.85rem;">{{ $r->election_name }}</div><small class="text-muted">{{ $r->election_type }}</small></div>
                        <span class="badge" style="background:{{ $r->status === 'published' ? 'rgba(46,139,87,0.15)' : 'rgba(212,175,55,0.15)' }};color:{{ $r->status === 'published' ? '#2e8b57' : '#d4af37' }};">{{ ucfirst($r->status ?? 'draft') }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-4 text-center text-muted">No results entered yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- Data Entry Dashboard --}}
@if($role === 'data_entry')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-slim green"><div class="stat-row"><div class="stat-icon"><i class="fas fa-user-plus"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['de_my_today'] ?? 0) }}</div><div class="stat-label">Registrations Today</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim blue"><div class="stat-row"><div class="stat-icon"><i class="fas fa-calendar-week"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['de_my_week'] ?? 0) }}</div><div class="stat-label">This Week</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim gold"><div class="stat-row"><div class="stat-icon"><i class="fas fa-clipboard-list"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['de_my_total'] ?? 0) }}</div><div class="stat-label">My Total Entries</div></div></div></div></div>
    <div class="col-md-3"><div class="stat-slim orange"><div class="stat-row"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-body"><div class="stat-value">{{ number_format($stats['de_state_voters'] ?? 0) }}</div><div class="stat-label">State Voters</div></div></div></div></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="fas fa-user-plus mb-2" style="color:var(--nec-green);font-size:2rem;"></i>
                <h5 class="fw-bold mb-1">Quick Actions</h5>
                <div class="d-grid gap-2 w-100">
                    <a href="{{ route('admin.voters.create') }}" class="btn btn-success btn-sm" style="border-radius:8px;"><i class="fas fa-plus me-1"></i>Register Voter</a>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;"><i class="fas fa-newspaper me-1"></i>New News</a>
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-success btn-sm" style="border-radius:8px;"><i class="fas fa-list me-1"></i>Manage Voters</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2" style="color:var(--nec-blue)"></i>Recent Registrations</h6></div>
            <div class="card-body p-0">
                @if (($stats['de_recent'] ?? collect())->count() > 0)
                <div class="table-responsive"><table class="table table-hover table-sm mb-0 align-middle">
                    <thead><tr><th class="ps-3">Voter ID</th><th>Full Name</th><th>Gender</th><th>County</th><th>Status</th><th class="text-end pe-3">Registered</th></tr></thead>
                    <tbody>
                        @foreach ($stats['de_recent'] as $v)
                        <tr>
                            <td class="ps-3"><code>{{ $v->voter_id }}</code></td>
                            <td class="fw-semibold">{{ $v->full_name }}</td>
                            <td>{{ $v->gender ?? '—' }}</td>
                            <td>{{ $v->county ?? '—' }}</td>
                            <td><span class="badge" style="background:rgba(46,139,87,0.15);color:#2e8b57;">{{ ucfirst($v->status) }}</span></td>
                            <td class="text-end pe-3"><small>{{ optional($v->registered_at)->format('d M Y') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
                @else
                <div class="p-4 text-center text-muted">No registrations found yet.</div>
                @endif
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
    var genderCtx = document.getElementById('genderMiniChart');
    if (genderCtx) { new Chart(genderCtx.getContext('2d'), { type: 'doughnut', data: { labels: ['Male', 'Female'], datasets: [{ data: [{{ $stats['male_count'] ?? 0 }}, {{ $stats['female_count'] ?? 0 }}], backgroundColor: [necBlue, necGreen], borderWidth: 2, borderColor: '#fff' }] }, options: { cutout: '65%', plugins: { legend: { display: false } } } }); }
    var regTypeCtx = document.getElementById('regTypeChart');
    if (regTypeCtx) { var regTypeData = {!! json_encode(($stats['registration_by_type'] ?? collect())->toArray() ?: []) !!}; new Chart(regTypeCtx.getContext('2d'), { type: 'doughnut', data: { labels: Object.keys(regTypeData).map(k => k === 'self' ? 'Self-Registered' : k === 'agent' ? 'Agent-Assisted' : k.charAt(0).toUpperCase() + k.slice(1)), datasets: [{ data: Object.values(regTypeData), backgroundColor: [necBlue, necGold, '#6c757d'], borderWidth: 2, borderColor: '#fff' }] }, options: { cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 10, usePointStyle: true, font: { size: 11 } } } } } }); }
    var statusCtx = document.getElementById('statusChart');
    if (statusCtx) { var statusData = {!! json_encode(($stats['voters_by_status'] ?? collect())->toArray() ?: []) !!}; var statusColors = Object.keys(statusData).map(function(s) { return s === 'active' ? necGreen : s === 'pending' ? necGold : s === 'suspended' ? necRed : '#6c757d'; }); new Chart(statusCtx.getContext('2d'), { type: 'doughnut', data: { labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)), datasets: [{ data: Object.values(statusData), backgroundColor: statusColors, borderWidth: 2, borderColor: '#fff' }] }, options: { cutout: '60%', plugins: { legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true } } } } }); }
    var trendCtx = document.getElementById('registrationTrendChart');
    if (trendCtx) { var trendData = {!! json_encode($stats['registration_trend_30d'] ?? collect()) !!}; new Chart(trendCtx.getContext('2d'), { type: 'line', data: { labels: trendData.map(d => { var dt = new Date(d.date); return dt.toLocaleDateString('en-GB', {day:'2-digit', month:'short'}); }), datasets: [{ label: 'New Registrations', data: trendData.map(d => d.total), borderColor: necGreen, backgroundColor: necLightGreen, fill: true, tension: 0.4, borderWidth: 2.5, pointBackgroundColor: necGreen, pointRadius: 3, pointHoverRadius: 5 }] }, options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 11 } } } } } }); }
    var gaCtx = document.getElementById('genderAgeChart');
    if (gaCtx) { var gaData = {!! json_encode(($stats['gender_by_age'] ?? collect())->toArray() ?: []) !!}; var ageOrder = ['Under 18','18-25','26-35','36-50','51-65','65+']; var gaLabels = ageOrder.filter(k => gaData[k]); new Chart(gaCtx.getContext('2d'), { type: 'bar', data: { labels: gaLabels, datasets: [{ label: 'Male', data: gaLabels.map(k => (gaData[k]||{}).M||0), backgroundColor: necBlue, borderRadius: 4 }, { label: 'Female', data: gaLabels.map(k => (gaData[k]||{}).F||0), backgroundColor: necGreen, borderRadius: 4 }] }, options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } } }); }
    var stateCtx = document.getElementById('stateBarChart');
    if (stateCtx) { var stateData = {!! json_encode(($stats['voters_by_state'] ?? collect())->toArray() ?: []) !!}; var stateLabels = Object.keys(stateData); var stateValues = Object.values(stateData); var barColors = stateLabels.map(function(_, i) { return [necGreen, necBlue, necGold, necRed, '#0dcaf0', '#198754', '#ffc107', '#0d6efd', '#6c757d', '#6f42c1'][i % 10]; }); new Chart(stateCtx.getContext('2d'), { type: 'bar', data: { labels: stateLabels.map(s => s.length > 12 ? s.substring(0,11)+'...' : s), datasets: [{ label: 'Voters', data: stateValues, backgroundColor: barColors, borderWidth: 0, borderRadius: 6, maxBarThickness: 50 }] }, options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { ticks: { maxRotation: 45, font: { size: 10 } }, grid: { display: false } } } } }); }
    var ageCtx = document.getElementById('ageChart');
    if (ageCtx) { var ageData = {!! json_encode(($stats['age_distribution'] ?? collect())->toArray() ?: []) !!}; var ageLabels = ['Under 18','18-25','26-35','36-50','51-65','65+'].filter(k => ageData[k] !== undefined); new Chart(ageCtx.getContext('2d'), { type: 'bar', data: { labels: ageLabels, datasets: [{ label: 'Voters', data: ageLabels.map(k => ageData[k]||0), backgroundColor: [necRed, necGreen, necBlue, necGold, '#0dcaf0', '#6f42c1'], borderRadius: 6 }] }, options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } } }); }
    var mtCtx = document.getElementById('monthlyTrendChart');
    if (mtCtx) { var mtData = {!! json_encode($stats['monthly_trend'] ?? collect()) !!}; new Chart(mtCtx.getContext('2d'), { type: 'line', data: { labels: mtData.map(d => d.month), datasets: [{ label: 'Registrations', data: mtData.map(d => d.total), borderColor: necBlue, backgroundColor: 'rgba(26,60,143,0.1)', fill: true, tension: 0.4, borderWidth: 2.5, pointBackgroundColor: necBlue, pointRadius: 4 }] }, options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } } }); }
    var countyCtx = document.getElementById('countyChart');
    if (countyCtx) { var countyData = {!! json_encode(($stats['voters_by_county'] ?? collect())->toArray() ?: []) !!}; new Chart(countyCtx.getContext('2d'), { type: 'bar', data: { labels: Object.keys(countyData).map(c => c.length > 15 ? c.substring(0,14)+'...' : c), datasets: [{ label: 'Voters', data: Object.values(countyData), backgroundColor: necGreen, borderRadius: 6 }] }, options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } } } }); }
    @endif

    @if(($role ?? '') === 'state_coordinator')
    var stCtx = document.getElementById('stateTrendChart');
    if (stCtx) { var stData = {!! json_encode($stats['state_registration_trend'] ?? collect()) !!}; new Chart(stCtx.getContext('2d'), { type: 'line', data: { labels: stData.map(d => { var dt = new Date(d.date); return dt.toLocaleDateString('en-GB',{day:'2-digit',month:'short'}); }), datasets: [{ label: 'Registrations', data: stData.map(d => d.total), borderColor: necGreen, backgroundColor: necLightGreen, fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3 }] }, options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } } } } }); }
    var scCtx = document.getElementById('stateCountyChart');
    if (scCtx) { var scData = {!! json_encode($stats['state_by_county'] ?? collect()) !!}; new Chart(scCtx.getContext('2d'), { type: 'bar', data: { labels: Object.keys(scData), datasets: [{ label: 'Voters', data: Object.values(scData), backgroundColor: necBlue, borderRadius: 6 }] }, options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true }, y: { grid: { display: false } } } } }); }
    @endif

    @if(($role ?? '') === 'registration_officer')
    var rtCtx = document.getElementById('regTrendChart');
    if (rtCtx) { var rtData = {!! json_encode($stats['reg_state_trend'] ?? collect()) !!}; new Chart(rtCtx.getContext('2d'), { type: 'line', data: { labels: rtData.map(d => { var dt = new Date(d.date); return dt.toLocaleDateString('en-GB',{day:'2-digit',month:'short'}); }), datasets: [{ label: 'Registrations', data: rtData.map(d => d.total), borderColor: necGreen, backgroundColor: necLightGreen, fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3 }] }, options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } } } } }); }
    var rgCtx = document.getElementById('regGenderChart');
    if (rgCtx) { var rgData = {!! json_encode(($stats['reg_gender'] ?? collect())->toArray() ?: []) !!}; new Chart(rgCtx.getContext('2d'), { type: 'doughnut', data: { labels: Object.keys(rgData).map(k => k === 'M' ? 'Male' : k === 'F' ? 'Female' : k), datasets: [{ data: Object.values(rgData), backgroundColor: [necBlue, necGreen, '#6c757d'], borderWidth: 2, borderColor: '#fff' }] }, options: { cutout: '62%', plugins: { legend: { position: 'bottom', labels: { padding: 10, usePointStyle: true, font: { size: 11 } } } } } }); }
    @endif
});
</script>
@endsection
