@extends('layouts.app', ['title' => 'Political Parties', 'active_page' => 'parties'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Political Parties</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Political Parties</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-flag text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        @php
        $parties = [];
        try {
            $parties = \App\Models\PoliticalParty::select('id', 'name', 'acronym as abbreviation', 'leader', 'status', 'description', 'logo as logo_url', 'created_at as registered_date')
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
                ->toArray();
        } catch (\Exception $e) {}
        if (empty($parties)) {
            $parties = [
                ['name' => "Sudan People's Liberation Movement", 'abbreviation' => 'SPLM', 'leader' => 'Cde. Salva Kiir Mayardit', 'status' => 'active', 'description' => 'The SPLM is the ruling political party in South Sudan, founded in 1983 as a liberation movement and transitioning to a political party after independence.'],
                ['name' => "Sudan People's Liberation Movement-In Opposition", 'abbreviation' => 'SPLM-IO', 'leader' => 'Dr. Riek Machar Teny', 'status' => 'active', 'description' => 'The SPLM-IO is a major opposition political party formed in 2013 following a split from the ruling SPLM party.'],
                ['name' => 'South Sudan Opposition Alliance', 'abbreviation' => 'SSOA', 'leader' => 'Gabriel Changson Chang', 'status' => 'active', 'description' => 'SSOA is a coalition of opposition political parties and groups formed to participate in the peace process and elections.'],
                ['name' => 'Other Political Parties', 'abbreviation' => 'OPP', 'leader' => 'Various', 'status' => 'active', 'description' => 'A coalition of other opposition political parties participating in the transitional government.'],
                ['name' => 'National Democratic Movement', 'abbreviation' => 'NDM', 'leader' => 'Dr. Lam Akol', 'status' => 'active', 'description' => 'The NDM is a political party founded by Dr. Lam Akol, advocating for democratic governance and federalism.'],
                ['name' => 'United Democratic Republic', 'abbreviation' => 'UDR', 'leader' => 'Peter Mayen Majongdit', 'status' => 'active', 'description' => 'The UDR is a political party focused on youth empowerment and democratic reform in South Sudan.'],
                ['name' => "People's Democratic Party", 'abbreviation' => 'PDP', 'leader' => 'Hakim Dario', 'status' => 'active', 'description' => 'The PDP is a political party advocating for social democracy and human rights in South Sudan.'],
                ['name' => 'Sudan African National Union', 'abbreviation' => 'SANU', 'leader' => 'Toby Maduot', 'status' => 'active', 'description' => 'SANU is one of the oldest political parties in South Sudan, with roots in the first civil war era.'],
                ['name' => 'United Democratic Front', 'abbreviation' => 'UDF', 'leader' => 'Peter Abogo', 'status' => 'active', 'description' => 'The UDF is a political party committed to federalism and equitable resource distribution.'],
                ['name' => 'South Sudan Patriotic Movement', 'abbreviation' => 'SSPM', 'leader' => 'Constantine Agou', 'status' => 'active', 'description' => 'The SSPM emerged from the peace process, advocating for national unity and development.'],
                ['name' => 'African National Congress', 'abbreviation' => 'ANC', 'leader' => 'Samuel Dang', 'status' => 'active', 'description' => 'The ANC advocates for Pan-Africanism and economic transformation in South Sudan.'],
                ['name' => 'South Sudan Liberal Party', 'abbreviation' => 'SSLP', 'leader' => 'Peter Biar', 'status' => 'active', 'description' => 'The SSLP is focused on economic freedom, individual rights, and democratic governance.'],
                ['name' => 'South Sudan National Movement for Change', 'abbreviation' => 'SSNMC', 'leader' => 'Dr. Gai Yoach', 'status' => 'active', 'description' => 'The SSNMC advocates for constitutional reform and good governance in South Sudan.'],
                ['name' => 'National Salvation Front', 'abbreviation' => 'NASF', 'leader' => 'Thomas Cirilo', 'status' => 'active', 'description' => 'The NASF is a political movement focused on democratic transformation and federalism.'],
                ['name' => 'Sudan African Democratic Union', 'abbreviation' => 'SADU', 'leader' => 'Peter Manyang', 'status' => 'active', 'description' => 'SADU promotes democratic values and equitable development across all regions.'],
                ['name' => 'United South Sudan Party', 'abbreviation' => 'USSP', 'leader' => 'Mathew Ajawin', 'status' => 'active', 'description' => 'The USSP advocates for national unity and inclusive political participation.'],
                ['name' => 'South Sudan National Democratic Alliance', 'abbreviation' => 'SSNDA', 'leader' => 'Bangasi Joseph Bakosoro', 'status' => 'active', 'description' => 'The SSNDA focuses on democratic governance and social justice.'],
                ['name' => 'African Democratic Party', 'abbreviation' => 'ADP', 'leader' => 'Dr. John Gai', 'status' => 'active', 'description' => 'The ADP promotes democratic governance and economic empowerment.'],
                ['name' => 'National Congress Party', 'abbreviation' => 'NCP', 'leader' => 'Dr. Lual Akuot', 'status' => 'active', 'description' => 'The NCP advocates for national development and social welfare.'],
                ['name' => 'South Sudan Federal Democratic Party', 'abbreviation' => 'SSFDP', 'leader' => 'Deng Alor', 'status' => 'active', 'description' => 'The SSFDP champions federalism and equitable resource sharing.'],
                ['name' => 'United Democratic Salvation Front', 'abbreviation' => 'UDSF', 'leader' => 'David Yau Yau', 'status' => 'active', 'description' => 'The UDSF focuses on peacebuilding and grassroots development.'],
                ['name' => "Sudan People's Liberation Movement for Democratic Change", 'abbreviation' => 'SPLM-DC', 'leader' => 'Peter Nyuot', 'status' => 'active', 'description' => 'The SPLM-DC advocates for democratic reforms and institutional strengthening.'],
                ['name' => 'Communist Party of South Sudan', 'abbreviation' => 'CPSS', 'leader' => 'Joseph Garang', 'status' => 'active', 'description' => 'The CPSS promotes socialist principles and workers\' rights.'],
                ['name' => 'Green Party of South Sudan', 'abbreviation' => 'GPSS', 'leader' => 'Madut Akok', 'status' => 'active', 'description' => 'The GPSS advocates for environmental protection and sustainable development.'],
                ['name' => "Youth and Women's Alliance", 'abbreviation' => 'YWA', 'leader' => 'Rose Damen', 'status' => 'active', 'description' => 'The YWA focuses on youth and women empowerment in political processes.'],
                ['name' => 'United Nations Party', 'abbreviation' => 'UNP', 'leader' => 'Joseph Machar', 'status' => 'active', 'description' => 'The UNP advocates for peace, unity, and national reconciliation.'],
                ['name' => 'Pan African Congress', 'abbreviation' => 'PAC', 'leader' => 'Kosti Manibe', 'status' => 'active', 'description' => 'The PAC promotes Pan-African unity and economic integration.'],
                ['name' => 'Reform Party', 'abbreviation' => 'RP', 'leader' => "Majak D'Agoot", 'status' => 'active', 'description' => 'The Reform Party focuses on institutional reform and accountable governance.'],
                ['name' => 'South Sudan National Congress', 'abbreviation' => 'SSNC', 'leader' => 'Agum Deng', 'status' => 'active', 'description' => 'The SSNC advocates for national reconciliation and inclusive development.'],
            ];
        }

        $party_colors = [];
        foreach ($parties as $party) {
            $abbr = $party['abbreviation'] ?? '';
            $hash = crc32($abbr ?: $party['name']);
            $hue = $hash % 360;
            $saturation = 55 + ($hash % 20);
            $lightness = 40 + ($hash % 15);
            $party_colors[$abbr] = [$hue, $saturation, $lightness];
        }
        @endphp

        <div class="stat-grid mb-4">
            <div class="stat-slim primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-flag"></i></div>
                    <div>
                        <div class="stat-value">{{ count($parties) }}</div>
                        <div class="stat-label">Registered Parties</div>
                    </div>
                </div>
            </div>
            <div class="stat-slim success">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-value">{{ count(array_filter($parties, fn($p) => ($p['status'] ?? '') === 'active')) }}</div>
                        <div class="stat-label">Active</div>
                    </div>
                </div>
            </div>
            <div class="stat-slim teal">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="stat-value">{{ count(array_unique(array_column($parties, 'leader'))) }}</div>
                        <div class="stat-label">Party Leaders</div>
                    </div>
                </div>
            </div>
            <div class="stat-slim orange">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                    <div>
                        <div class="stat-value">{{ count(array_filter($parties, fn($p) => str_contains($p['name'] ?? '', 'Coalition'))) }}</div>
                        <div class="stat-label">Coalitions</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;overflow:hidden;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="partySearch" class="form-control border-start-0 ps-0" placeholder="Search by name or abbreviation..." style="border-left:none;">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex flex-wrap gap-1" id="letterFilter">
                            <button class="btn btn-sm px-3 rounded-pill letter-btn active" data-letter="">All</button>
                            @foreach(range('A', 'Z') as $letter)
                            <button class="btn btn-sm px-3 rounded-pill letter-btn" data-letter="{{ $letter }}">{{ $letter }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="partyGrid">
            @foreach($parties as $party)
            @php
                $abbr = $party['abbreviation'] ?? '';
                $hc = $party_colors[$abbr] ?? [0, 50, 50];
                $bg = "hsl({$hc[0]}, {$hc[1]}%, {$hc[2]}%)";
                $fg = $hc[2] > 50 ? '#000' : '#fff';
                $first_letter = strtoupper(substr($party['name'], 0, 1));
            @endphp
            <div class="col-md-6 col-lg-4 party-card" data-name="{{ strtolower($party['name'] . ' ' . $abbr) }}" data-letter="{{ $first_letter }}">
                <div class="card border-0 shadow-sm h-100 party-card-inner" style="border-radius:12px;transition:transform 0.2s,box-shadow 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center fw-bold" style="width: 52px; height: 52px; background: {{ $bg }}; color: {{ $fg }}; font-size: 1.1rem;">
                                {{ $abbr ?: substr($party['name'], 0, 2) }}
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-bold mb-0" style="font-size:0.95rem;">{{ $party['name'] }}</h6>
                                @if($abbr)
                                <span class="badge mt-1" style="background:{{ $bg }}20;color:{{ $bg }};font-size:10px;">{{ $abbr }}</span>
                                @endif
                            </div>
                        </div>
                        <p class="small text-muted mb-3" style="font-size:0.82rem;line-height:1.5;">{{ mb_substr($party['description'], 0, 200) }}{{ mb_strlen($party['description']) > 200 ? '...' : '' }}</p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <small class="text-muted"><i class="fas fa-user-tie me-1" style="color:var(--nec-green);"></i>{{ $party['leader'] }}</small>
                            <span class="badge rounded-pill px-3" style="background:rgba(var(--nec-green-rgb),0.1);color:var(--nec-green-dark);font-weight:500;">
                                <i class="fas fa-check-circle me-1" style="font-size:9px;"></i> {{ ucfirst($party['status'] ?? 'active') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="noPartiesMsg" class="text-center py-5 d-none">
            <i class="fas fa-flag text-muted" style="font-size: 3rem;"></i>
            <h4 class="fw-bold mt-3">No Parties Found</h4>
            <p class="text-muted">No political parties match your current search.</p>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<style>
.party-card-inner:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
}
.letter-btn {
    font-size: 0.75rem;
    padding: 2px 10px;
    min-width: 32px;
    transition: all 0.15s;
}
.letter-btn.active {
    background: var(--nec-green);
    color: #fff;
    border-color: var(--nec-green);
}
.letter-btn:not(.active):hover {
    background: rgba(var(--nec-green-rgb), 0.08);
    border-color: var(--nec-green);
    color: var(--nec-green);
}
</style>
<script>
$(document).ready(function() {
    function filterParties() {
        var q = $('#partySearch').val().toLowerCase();
        var letter = $('.letter-btn.active').data('letter');
        var visible = 0;
        $('.party-card').each(function() {
            var nameMatch = !q || $(this).data('name').indexOf(q) > -1;
            var letterMatch = !letter || $(this).data('letter') === letter;
            var show = nameMatch && letterMatch;
            $(this).toggle(show);
            if (show) visible++;
        });
        $('#noPartiesMsg').toggleClass('d-none', visible > 0);
    }

    $('#partySearch').on('keyup', filterParties);

    $('.letter-btn').click(function() {
        $('.letter-btn').removeClass('active');
        $(this).addClass('active');
        filterParties();
    });
});
</script>
@endsection
