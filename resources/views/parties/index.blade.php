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
            $parties = \App\Models\PoliticalParty::select('id', 'name', 'acronym as abbreviation', 'leader', 'status', 'logo as logo_url', 'created_at as registered_date')
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
                ->toArray();
        } catch (\Exception $e) {}
        if (empty($parties)) {
            $parties = [
                ['name' => 'African National Congress', 'abbreviation' => 'ANC', 'leader' => 'Gen. (Rtd) George Kongor Arop', 'status' => 'active', 'description' => 'The ANC advocates for Pan-Africanism and economic transformation in South Sudan, committed to democratic governance and national unity.'],
                ['name' => "Sudan People's Liberation Movement", 'abbreviation' => 'SPLM', 'leader' => 'Gen. Salva Kiir Mayardit', 'status' => 'active', 'description' => 'The SPLM is the ruling political party in South Sudan, founded in 1983 as a liberation movement and transitioning to a political party after independence.'],
                ['name' => 'United Sudan African Party', 'abbreviation' => 'USAP', 'leader' => 'Hon. Joseph Malek Arop', 'status' => 'active', 'description' => 'A political party advocating for change through the ballot, representing diverse Sudanese and South Sudanese interests.'],
                ['name' => 'United Democratic Salvation Front - Mainstream', 'abbreviation' => 'UDSF-M', 'leader' => 'Hon. Francis Ben Ataba', 'status' => 'active', 'description' => 'A mainstream opposition front committed to justice, equality, peace, and development across South Sudan.'],
                ['name' => 'National Liberation Party', 'abbreviation' => 'NLP', 'leader' => 'Hon. Nkurumah Anai', 'status' => 'active', 'description' => 'A political party with the vision of one country, one people, one nation, focused on national liberation and unity.'],
                ['name' => 'National Congress Party', 'abbreviation' => 'NCP', 'leader' => 'Hon. Agnes Poni Lukudu', 'status' => 'active', 'description' => 'A party committed to peace, unity, transparency, good governance and justice in South Sudan.'],
                ['name' => 'Democratic Change Party', 'abbreviation' => 'DC', 'leader' => 'Hon. Onyoti Adigo Nykuac', 'status' => 'active', 'description' => 'A political movement focused on prosperity, civility and harmony, advocating for democratic change in South Sudan.'],
                ['name' => 'South Sudan Democratic Forum', 'abbreviation' => 'SSDF', 'leader' => 'Hon. Dr. Martin Elia Lomuro', 'status' => 'active', 'description' => 'A party dedicated to a united, strong, and forward-looking South Sudan through democratic dialogue and participation.'],
                ['name' => 'United South Sudan Party', 'abbreviation' => 'USSP', 'leader' => 'Hon. Paulino Lukudu Obede', 'status' => 'active', 'description' => 'A political party advocating for national unity, inclusive political participation, and a united South Sudan.'],
                ['name' => 'National United Democratic Front', 'abbreviation' => 'NUDF', 'leader' => 'Hon. Kornelio Kon Ngu', 'status' => 'active', 'description' => 'A democratic front committed to freedom, justice, and equality for all South Sudanese citizens.'],
                ['name' => 'South Sudan Democratic Alliance', 'abbreviation' => 'SSDA', 'leader' => 'Hon. Pasqulina Phillip Waden', 'status' => 'active', 'description' => 'An alliance dedicated to peace, unity, justice, and sustainable development across South Sudan.'],
                ['name' => 'Sudan African National Union - National', 'abbreviation' => 'SANU-N', 'leader' => 'Hon. Theresa Ciricio Iro (Late)', 'status' => 'active', 'description' => 'One of the oldest political formations in South Sudan, rooted in the struggle for peace, unity, and justice.'],
                ['name' => 'United Democratic Salvation Front', 'abbreviation' => 'UDSF', 'leader' => 'Hon. Rev. Emmanuel Sokiri', 'status' => 'active', 'description' => 'A salvation front committed to peace, freedom, justice, equality, and democracy for all South Sudanese.'],
                ['name' => 'National Democratic Party', 'abbreviation' => 'NDP', 'leader' => 'Hon. James Aniceto', 'status' => 'active', 'description' => 'A national democratic party focused on unity, equality, peace, and development across South Sudan.'],
                ['name' => 'United Democratic Party', 'abbreviation' => 'UDP', 'leader' => 'Hon. Tong Lual Ayat', 'status' => 'active', 'description' => 'A party promoting liberalism, democracy, and prosperity through inclusive political participation.'],
                ['name' => 'Federal Democratic Party', 'abbreviation' => 'FDP', 'leader' => 'Hon. Galdong Nganyek Bhok', 'status' => 'active', 'description' => 'A federalist party advocating for power, freedom, and equality through a decentralized system of governance.'],
                ['name' => 'United Democratic Front', 'abbreviation' => 'UDF', 'leader' => 'Hon. Bona Deng', 'status' => 'active', 'description' => 'A democratic front committed to justice, freedom, and progress for all South Sudanese citizens.'],
                ['name' => 'Communist Party of South Sudan', 'abbreviation' => 'CPSS', 'leader' => 'Hon. Joseph Wol Modesto', 'status' => 'active', 'description' => 'A party that stands with the people and for the people, promoting socialist principles and workers\' rights.'],
                ['name' => 'Democratic Unionist Party', 'abbreviation' => 'DUP', 'leader' => 'Hon. Albino John Lako', 'status' => 'active', 'description' => 'A unionist party dedicated to God, nation, and democracy in South Sudan.'],
                ['name' => 'South Sudan African National Union', 'abbreviation' => 'SSANU', 'leader' => 'Hon. Philip Palet', 'status' => 'active', 'description' => 'A national union advocating for unity, justice, and peace across all communities of South Sudan.'],
                ['name' => 'Generation Party', 'abbreviation' => 'GP', 'leader' => 'Hon. Looth Mah Tang', 'status' => 'active', 'description' => 'A party positioning itself as the servant of the people, focused on generational change and inclusive governance.'],
                ['name' => 'South Sudan National Party', 'abbreviation' => 'SSNP', 'leader' => 'Hon. Juma Said W', 'status' => 'active', 'description' => 'A national party committed to unity and democratic governance in South Sudan.'],
                ['name' => 'National Democratic Front', 'abbreviation' => 'NDF', 'leader' => 'Hon. Stephen Goro', 'status' => 'active', 'description' => 'A democratic front focused on peace, justice, equality, and development for all South Sudanese.'],
                ['name' => 'Republican Party of South Sudan', 'abbreviation' => 'RPSS', 'leader' => 'Hon. Anthony Agiem', 'status' => 'active', 'description' => 'A republican party with a God-fearing foundation, committed to national unity and democratic values.'],
                ['name' => 'Akut Bam Party', 'abbreviation' => 'ABP', 'leader' => 'Hon. Makuac Akol', 'status' => 'active', 'description' => 'A party grounded in the spirit of Kondial/Ujamaa, promoting community solidarity and collective development.'],
                ['name' => 'Popular Congress Party', 'abbreviation' => 'PCP', 'leader' => 'Hon. Abdalla Deng Nhial', 'status' => 'active', 'description' => 'A popular congress movement committed to justice, equality, and development for all South Sudanese.'],
                ['name' => 'South Sudan Generation Party', 'abbreviation' => 'SSGP', 'leader' => 'Adv. Mayen Jeramiah Turc', 'status' => 'active', 'description' => 'A generation-focused party advocating for unity, democracy, and prosperity for future generations.'],
                ['name' => 'National Justice Movement Party', 'abbreviation' => 'NJMP', 'leader' => 'Hon. Mater Mayind', 'status' => 'active', 'description' => 'A justice movement committed to leading for production and prosperity across South Sudan.'],
                ['name' => 'South Sudan National Labor Party', 'abbreviation' => 'SSNLP', 'leader' => 'Hon. James Andrea Anyak', 'status' => 'active', 'description' => 'A labor party focused on peace, equality, jobs, and unity for the working people of South Sudan.'],
                ['name' => 'Social Democratic Party', 'abbreviation' => 'SDP', 'leader' => 'Mrs. Rain Ayen Deng', 'status' => 'active', 'description' => 'A social democratic party that never gives up, advocating for social justice and democratic governance.'],
                ['name' => 'National Patriotic Movement', 'abbreviation' => 'NPM', 'leader' => 'Hon. Dr. Isaa Muzamil', 'status' => 'active', 'description' => 'A patriotic movement with the vision of one nation, one future, committed to national unity and prosperity.'],
                ['name' => 'South Sudan Democratic Front', 'abbreviation' => 'SSDF', 'leader' => 'Hon. Prof. David De Chan', 'status' => 'active', 'description' => 'A democratic front believing that together we stand and divided we fall, promoting unity and democracy.'],
                ['name' => "Peoples' United Forum", 'abbreviation' => 'PUF', 'leader' => 'Dr. Gai Chol Paul', 'status' => 'active', 'description' => 'A peoples\' forum dedicated to being the voice of the people, advocating for grassroots participation in governance.'],
                ['name' => "People's Democratic Movement", 'abbreviation' => 'PDM', 'leader' => 'H.E. Josephine Lagu', 'status' => 'active', 'description' => 'A democratic movement centered on people\'s power, promoting inclusive and representative governance.'],
                ['name' => 'IO Party', 'abbreviation' => 'IOP', 'leader' => 'Hon. Amb. Stephen Par Koul', 'status' => 'active', 'description' => 'Formerly SPLM-IO, the IO Party is committed to prosperity, freedom, peace, and justice for all South Sudanese.'],
                ['name' => 'National Democratic Movement', 'abbreviation' => 'NDM', 'leader' => 'Hon. Dr. Lam Akol Ajawin', 'status' => 'active', 'description' => 'The NDM advocates for democratic governance, federalism, and institutional reform in South Sudan.'],
                ['name' => 'South Sudan National Movement for Change', 'abbreviation' => 'SSNMC', 'leader' => 'Hon. Moro Isaac Jenesio', 'status' => 'active', 'description' => 'A movement for change focused on liberty, justice, and prosperity through constitutional reform.'],
                ['name' => 'People Liberal Party', 'abbreviation' => 'PLP', 'leader' => 'Hon. Peter Mayen Majongdit', 'status' => 'active', 'description' => 'A liberal party dedicated to creating a transparent political environment for future generations.'],
                ['name' => 'Revive South Sudan Party', 'abbreviation' => 'RSSP', 'leader' => 'Hon. Mawien Dot Pheot', 'status' => 'active', 'description' => 'A revival party focused on hope, progress, and unity to rebuild and rejuvenate South Sudan.'],
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
                        <p class="small text-muted mb-3" style="font-size:0.82rem;line-height:1.5;">{{ mb_substr($party['description'] ?? '', 0, 200) }}{{ (mb_strlen($party['description'] ?? '') > 200) ? '...' : '' }}</p>
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
