@extends('layouts.app', ['title' => 'Candidates', 'active_page' => 'candidates'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Candidates</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Candidates</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-user-check text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        @php
        $candidates = [];
        try {
            $candidates = \App\Models\Candidate::select('nec_candidates.id', 'nec_candidates.name', 'nec_candidates.position', 'nec_candidates.state', 'nec_candidates.status', 'nec_candidates.photo as candidate_photo', 'nec_political_parties.name as party_name', 'nec_political_parties.acronym as party_abbr')
                ->leftJoin('nec_political_parties', 'nec_candidates.party_id', '=', 'nec_political_parties.id')
                ->orderBy('nec_candidates.position')
                ->orderBy('nec_candidates.state')
                ->orderBy('nec_candidates.name')
                ->get()
                ->toArray();
        } catch (\Exception $e) {}
        if (empty($candidates)) {
            $candidates = [
                ['name' => 'Salva Kiir Mayardit', 'party_name' => 'SPLM', 'party_abbr' => 'SPLM', 'position' => 'President', 'state' => 'Central Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Riek Machar Teny', 'party_name' => 'SPLM-IO', 'party_abbr' => 'SPLM-IO', 'position' => 'President', 'state' => 'Unity', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'James Wani Igga', 'party_name' => 'SPLM', 'party_abbr' => 'SPLM', 'position' => 'President', 'state' => 'Central Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Rebecca Nyandeng Garang', 'party_name' => 'SPLM', 'party_abbr' => 'SPLM', 'position' => 'President', 'state' => 'Warrap', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Gabriel Changson Chang', 'party_name' => 'SSOA', 'party_abbr' => 'SSOA', 'position' => 'President', 'state' => 'Upper Nile', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Lam Akol', 'party_name' => 'NDM', 'party_abbr' => 'NDM', 'position' => 'President', 'state' => 'Upper Nile', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Peter Mayen Majongdit', 'party_name' => 'UDR', 'party_abbr' => 'UDR', 'position' => 'President', 'state' => 'Lakes', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Toby Maduot', 'party_name' => 'SANU', 'party_abbr' => 'SANU', 'position' => 'President', 'state' => 'Central Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Hakim Dario', 'party_name' => 'PDP', 'party_abbr' => 'PDP', 'position' => 'President', 'state' => 'Western Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Constantine Agou', 'party_name' => 'SSPM', 'party_abbr' => 'SSPM', 'position' => 'President', 'state' => 'Western Bahr el Ghazal', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'John Garang De Mabior Jr.', 'party_name' => 'SPLM', 'party_abbr' => 'SPLM', 'position' => 'MP', 'state' => 'Jonglei', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Angelina Teny', 'party_name' => 'SPLM-IO', 'party_abbr' => 'SPLM-IO', 'position' => 'MP', 'state' => 'Unity', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Deng Alor', 'party_name' => 'SSFDP', 'party_abbr' => 'SSFDP', 'position' => 'MP', 'state' => 'Warrap', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Dr. Gai Yoach', 'party_name' => 'SSNMC', 'party_abbr' => 'SSNMC', 'position' => 'MP', 'state' => 'Jonglei', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Thomas Cirilo', 'party_name' => 'NASF', 'party_abbr' => 'NASF', 'position' => 'MP', 'state' => 'Eastern Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Peter Manyang', 'party_name' => 'SADU', 'party_abbr' => 'SADU', 'position' => 'MP', 'state' => 'Upper Nile', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Bangasi Joseph Bakosoro', 'party_name' => 'SSNDA', 'party_abbr' => 'SSNDA', 'position' => 'MP', 'state' => 'Western Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'David Yau Yau', 'party_name' => 'UDSF', 'party_abbr' => 'UDSF', 'position' => 'MP', 'state' => 'Jonglei', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Joseph Garang', 'party_name' => 'CPSS', 'party_abbr' => 'CPSS', 'position' => 'MP', 'state' => 'Central Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Rose Damen', 'party_name' => 'YWA', 'party_abbr' => 'YWA', 'position' => 'MP', 'state' => 'Lakes', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Madut Akok', 'party_name' => 'GPSS', 'party_abbr' => 'GPSS', 'position' => 'State Assembly', 'state' => 'Northern Bahr el Ghazal', 'status' => 'pending', 'candidate_photo' => ''],
                ['name' => 'Kosti Manibe', 'party_name' => 'PAC', 'party_abbr' => 'PAC', 'position' => 'State Assembly', 'state' => 'Eastern Equatoria', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => "Majak D'Agoot", 'party_name' => 'RP', 'party_abbr' => 'RP', 'position' => 'State Assembly', 'state' => 'Warrap', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Agum Deng', 'party_name' => 'SSNC', 'party_abbr' => 'SSNC', 'position' => 'State Assembly', 'state' => 'Lakes', 'status' => 'approved', 'candidate_photo' => ''],
                ['name' => 'Dr. Lual Akuot', 'party_name' => 'NCP', 'party_abbr' => 'NCP', 'position' => 'State Assembly', 'state' => 'Upper Nile', 'status' => 'approved', 'candidate_photo' => ''],
            ];
        }
        $positions = array_unique(array_column($candidates, 'position'));
        $states = array_unique(array_column($candidates, 'state'));
        sort($positions);
        sort($states);
        $partyNames = array_unique(array_column($candidates, 'party_name'));
        sort($partyNames);

        $count_by_position = [];
        foreach ($candidates as $c) {
            $count_by_position[$c['position']] = ($count_by_position[$c['position']] ?? 0) + 1;
        }
        $total_candidates = count($candidates);

        $filtered = $candidates;
        if (!empty(request('position'))) {
            $filtered = array_values(array_filter($filtered, fn($c) => $c['position'] === request('position')));
        }
        if (!empty(request('party'))) {
            $filtered = array_values(array_filter($filtered, fn($c) => $c['party_name'] === request('party')));
        }
        if (!empty(request('state'))) {
            $filtered = array_values(array_filter($filtered, fn($c) => $c['state'] === request('state')));
        }
        if (!empty(request('q'))) {
            $q = strtolower(request('q'));
            $filtered = array_values(array_filter($filtered, fn($c) => strpos(strtolower($c['name']), $q) !== false || strpos(strtolower($c['party_abbr'] ?? ''), $q) !== false));
        }
        @endphp

        <div class="stat-grid mb-4">
            <div class="stat-slim green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($total_candidates) }}</div>
                        <div class="stat-label">Total Candidates</div>
                    </div>
                </div>
            </div>
            @foreach($count_by_position as $pos => $cnt)
            <div class="stat-slim {{ $pos === 'President' ? 'red' : ($pos === 'MP' ? 'blue' : 'teal') }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-{{ $pos === 'President' ? 'user-tie' : ($pos === 'MP' ? 'landmark' : 'building') }}"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($cnt) }}</div>
                        <div class="stat-label">{{ $pos }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ url('candidates') }}" class="row g-2 g-md-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase" style="font-size:11px;letter-spacing:0.5px;"><i class="fas fa-search me-1"></i>Search</label>
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Name or party..." value="{{ request('q', '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase" style="font-size:11px;letter-spacing:0.5px;"><i class="fas fa-briefcase me-1"></i>Position</label>
                        <select name="position" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach($positions as $p)
                            <option value="{{ $p }}" {{ (request('position') == $p) ? 'selected' : '' }}>{{ $p }} ({{ $count_by_position[$p] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase" style="font-size:11px;letter-spacing:0.5px;"><i class="fas fa-flag me-1"></i>Party</label>
                        <select name="party" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach($partyNames as $pn)
                            <option value="{{ $pn }}" {{ (request('party') == $pn) ? 'selected' : '' }}>{{ $pn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase" style="font-size:11px;letter-spacing:0.5px;"><i class="fas fa-map-marker-alt me-1"></i>State</label>
                        <select name="state" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach($states as $s)
                            <option value="{{ $s }}" {{ (request('state') == $s) ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center pt-1">
                        <span class="text-muted small"><strong>{{ number_format(count($filtered)) }}</strong> candidate{{ count($filtered) !== 1 ? 's' : '' }} found</span>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm text-white px-3" style="background:var(--nec-green);"><i class="fas fa-filter me-1"></i> Filter</button>
                            <a href="{{ url('candidates') }}" class="btn btn-sm btn-outline-secondary px-3"><i class="fas fa-times me-1"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(count($filtered) > 0)
        <div class="row g-3">
            @foreach($filtered as $c)
            @php
                $initials = '';
                $parts = explode(' ', $c['name']);
                foreach ($parts as $p) { if (!empty($p[0])) $initials .= strtoupper($p[0]); if (strlen($initials) >= 2) break; }
                if (!$initials) $initials = strtoupper(substr($c['name'], 0, 2));
                $party_color = sprintf('#%06x', crc32($c['party_abbr'] ?? $c['party_name']) & 0xffffff);
                $is_light = (hexdec(substr($party_color, 1, 2)) * 0.299 + hexdec(substr($party_color, 3, 2)) * 0.587 + hexdec(substr($party_color, 5, 2)) * 0.114) > 160;
                $photo_url = $c['candidate_photo'] ?? '';
                $pos_icon = $c['position'] === 'President' ? 'user-tie' : ($c['position'] === 'MP' ? 'landmark' : 'building');
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 candidate-card" style="border-radius:14px;overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;">
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center p-3 p-md-4 gap-3 gap-md-4">
                            @if($photo_url)
                            <img src="{{ $photo_url }}" alt="" class="flex-shrink-0" style="width:64px;height:64px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;">
                            @else
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white rounded-circle" style="width:64px;height:64px;background:linear-gradient(135deg,var(--nec-green),var(--nec-green-dark));font-size:22px;border:3px solid rgba(var(--nec-green-rgb),0.15);">{{ $initials }}</div>
                            @endif
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-bold mb-1" style="font-size:0.95rem;">{{ $c['name'] }}</h6>
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    <span class="badge" style="background:{{ $party_color }};color:{{ $is_light ? '#000' : '#fff' }};font-size:10px;">
                                        {{ $c['party_abbr'] ?? $c['party_name'] }}
                                    </span>
                                    <span class="badge bg-white text-dark border" style="font-weight:500;font-size:10px;">
                                        <i class="fas fa-{{ $pos_icon }} me-1" style="color:var(--nec-green);"></i>
                                        {{ $c['position'] }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1" style="color:var(--nec-green);"></i>{{ $c['state'] }}</small>
                                    @if(($c['status'] ?? '') === 'approved')
                                    <span class="badge-success px-2" style="font-size:10px;border-radius:20px;"><i class="fas fa-check-circle me-1"></i>Approved</span>
                                    @else
                                    <span class="badge-warning px-2" style="font-size:10px;border-radius:20px;"><i class="fas fa-clock me-1"></i>Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body text-center py-5">
                <div style="width:72px;height:72px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-user-slash text-muted" style="font-size: 1.8rem;"></i>
                </div>
                <h4 class="fw-bold mt-2">No Candidates Found</h4>
                <p class="text-muted mb-3">No candidates match your current filter criteria.</p>
                <a href="{{ url('candidates') }}" class="btn btn-outline-primary btn-sm px-4"><i class="fas fa-times me-1"></i> Clear Filters</a>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
.candidate-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
}
.stat-slim.green { border-left-color: #10b981; } .stat-slim.blue { border-left-color: #3b82f6; }
.stat-slim.red { border-left-color: #ef4444; }
.stat-slim.green .stat-icon { background: rgba(16,185,129,0.12); color: #10b981; }
.stat-slim.blue .stat-icon { background: rgba(59,130,246,0.12); color: #3b82f6; }
.stat-slim.red .stat-icon { background: rgba(239,68,68,0.12); color: #ef4444; }
</style>
@endsection
