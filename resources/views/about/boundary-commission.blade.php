@extends('layouts.app', ['title' => 'Boundary Commission - NEC South Sudan', 'active_page' => 'about'])

@section('extra_head')
<style>
    .nec-hero-wrapper {
        position: relative;
        overflow: hidden;
    }
</style>
@endsection

@section('hero')
<div class="nec-hero-wrapper text-white" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('about.mandate') }}" class="text-white opacity-75 text-decoration-none">About NEC</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Boundary Commission</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Boundary Commission</h1>
        <p class="lead mb-0 opacity-90">Ensuring fair representation through scientific delimitation of electoral constituencies.</p>
    </div>
</div>
@endsection

@section('content')
@php
    $sidebar_section = 'About NEC';
    $sidebar_links = [
        ['url' => route('about.mandate'), 'label' => 'Our Mandate', 'icon' => 'fas fa-gavel'],
        ['url' => route('about.leadership'), 'label' => 'Leadership', 'icon' => 'fas fa-users'],
        ['url' => route('about.commissioners'), 'label' => 'Commissioners', 'icon' => 'fas fa-user-tie'],
        ['url' => route('about.state-committees'), 'label' => 'State Committees', 'icon' => 'fas fa-map-marker-alt'],
        ['url' => route('about.departments'), 'label' => 'Departments', 'icon' => 'fas fa-building'],
        ['url' => route('about.history'), 'label' => 'History', 'icon' => 'fas fa-history'],
        ['url' => route('about.legal-framework'), 'label' => 'Legal Framework', 'icon' => 'fas fa-file-contract'],
        ['url' => route('about.boundary-commission'), 'label' => 'Boundary Commission', 'icon' => 'fas fa-draw-polygon', 'active' => true],
    ];

    $totalConstituencies = $totalConstituencies ?? 0;
    $totalStates = $totalStates ?? 0;
@endphp

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4">Electoral Boundary Commission</h2>
                    <p>The Electoral Boundary Commission is a specialized body within the National Elections Commission responsible for the delimitation of electoral constituencies across South Sudan. The Commission ensures that each constituency contains approximately equal populations, providing fair and equitable representation in the National Assembly.</p>

                    <div class="row g-3 my-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded-3">
                                <div class="display-6 fw-bold text-success">{{ $totalConstituencies }}</div>
                                <small class="text-muted text-uppercase fw-semibold">Constituencies</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded-3">
                                <div class="display-6 fw-bold text-danger">{{ $totalStates }}</div>
                                <small class="text-muted text-uppercase fw-semibold">Electoral Areas</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded-3">
                                <div class="display-6 fw-bold" style="color:var(--nec-blue);">~80K</div>
                                <small class="text-muted text-uppercase fw-semibold">Avg Voters / Constituency</small>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Delimitation Criteria</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1 text-success"><i class="fas fa-users"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Population Equality</h6>
                                            <small class="text-muted">Constituencies shall have approximately equal numbers of inhabitants, based on the most recent national census.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1 text-info"><i class="fas fa-globe-africa"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Geographical Contiguity</h6>
                                            <small class="text-muted">Constituencies shall be contiguous land areas, respecting natural geographical boundaries.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1 text-warning"><i class="fas fa-map-signs"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Administrative Boundaries</h6>
                                            <small class="text-muted">Constituency boundaries shall follow county and state administrative borders where possible.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="fs-1 text-danger"><i class="fas fa-scale-balanced"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Community of Interest</h6>
                                            <small class="text-muted">Consideration shall be given to ethnic, cultural, and economic communities of interest.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">State Constituency Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>State / Area</th>
                                    <th>Constituencies</th>
                                    <th>Est. Population</th>
                                    <th>Avg Voters / Constituency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($breakdownRows) > 0)
                                    @foreach($breakdownRows as $br)
                                        @php
                                            $isSpecial = str_contains($br->state_name, 'Abyei') || str_contains($br->state_name, 'Pibor') || str_contains($br->state_name, 'Ruweng');
                                        @endphp
                                        <tr @if($isSpecial) class="table-secondary" @endif>
                                            <td>{{ $br->state_name }}</td>
                                            <td>{{ (int)$br->constituencies }}</td>
                                            <td>{{ number_format((int)$br->estimated_population) }}</td>
                                            <td>~{{ number_format((int)$br->avg_voters) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4" class="text-center text-muted py-4">No constituency breakdown data available.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px;position:sticky;top:100px;">
                <div class="card-body p-0">
                    <div class="px-3 pt-3 pb-2" style="background:var(--nec-green);color:#fff;border-radius:12px 12px 0 0;">
                        <small class="fw-bold text-uppercase" style="font-size:0.65rem;letter-spacing:1px;opacity:0.8;">In this section</small>
                        <h6 class="fw-bold mb-0" style="color:#fff;font-size:0.95rem;">{{ $sidebar_section }}</h6>
                    </div>
                    <ul class="sidebar-nav p-2">
                        @foreach($sidebar_links as $sl)
                        <li class="sidebar-nav-item">
                            <a href="{{ $sl['url'] }}" class="sidebar-nav-link {{ ($sl['active'] ?? false) ? 'active' : '' }}">
                                <i class="{{ $sl['icon'] ?? 'fas fa-link' }}"></i>
                                {{ $sl['label'] }}
                                @isset($sl['badge'])
                                <span class="badge bg-{{ $sl['badge_color'] ?? 'success' }} ms-auto">{{ $sl['badge'] }}</span>
                                @endisset
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-draw-polygon fa-3x mb-3" style="color:var(--nec-blue);"></i>
                    <h6 class="fw-bold">Constituency Mapping</h6>
                    <p class="small text-muted">View detailed maps and information about all 102 constituencies.</p>
                    <a href="{{ route('constituencies.index') }}" class="btn btn-outline-primary btn-sm w-100">Explore Constituencies</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-history me-2 text-muted"></i>Review Timeline</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                            <span>Current Review</span>
                            <span class="badge bg-success">Ongoing</span>
                        </li>
                        <li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                            <span>2021 Delimitation</span>
                            <span class="badge bg-secondary">Completed</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>2015 Initial</span>
                            <span class="badge bg-secondary">Completed</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
