@extends('layouts.app', ['title' => 'Civic Education - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Civic Education</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">Civic Education</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
@php
$langLabel = ['English' => 'English', 'Arabic' => 'العربية'];
@endphp

<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Civic Education Resources</h2>
            <p>Empowering citizens with knowledge about democracy and the electoral process</p>
        </div>

        <div class="row g-4 mb-5" id="civic-resources">
            @foreach($resources as $res)
            @php
                $img = $res['image'];
                $pdf = $res['material']->file_path ?? null;
                $desc = $res['desc'] ?: ($res['material']->description ?? 'Download the ' . $res['title'] . ' to learn more about civic and voter education in South Sudan.');
            @endphp
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="civic-resource-card h-100 text-center p-0 overflow-hidden">
                    <div class="civic-resource-img position-relative" style="height:170px;background:linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);overflow:hidden;">
                        @if($img)
                        <img src="{{ $img }}" alt="{{ $res['title'] }}" class="w-100 h-100" style="object-fit:cover;">
                        @else
                        <i class="fas {{ $res['icon'] }} text-white-50" style="font-size:3.5rem;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"></i>
                        @endif
                        <span class="badge position-absolute top-0 end-0 m-2" style="background:rgba(0,0,0,0.45);color:#fff;">{{ $res['lang'] }}</span>
                    </div>
                    <div class="p-4">
                        <h5 class="fw-bold">{{ $res['title'] }}</h5>
                        <p class="text-muted small mb-3">{{ $desc }}</p>
                        @if($pdf)
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ asset($pdf) }}" target="_blank" rel="noopener" class="btn btn-outline-nec btn-sm"><i class="fas fa-eye me-1"></i> View</a>
                            <a href="{{ asset($pdf) }}" target="_blank" rel="noopener" class="btn btn-nec btn-sm" download><i class="fas fa-download me-1"></i> Download</a>
                        </div>
                        @else
                        <span class="text-muted small">Coming soon</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-landmark"></i></div>
                    <h4>Understanding Democracy</h4>
                    <p>Learn about the foundations of democracy, the Constitution of South Sudan, and how democratic governance works in our country.</p>
                    <ul class="civic-list">
                        <li>What is democracy?</li>
                        <li>Constitutional rights</li>
                        <li>Separation of powers</li>
                        <li>Rule of law</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-vote-yea"></i></div>
                    <h4>Your Right to Vote</h4>
                    <p>Understanding who can vote, how to register, and the importance of exercising your democratic right.</p>
                    <ul class="civic-list">
                        <li>Eligibility requirements</li>
                        <li>Registration process</li>
                        <li>Voter identification</li>
                        <li>Secret ballot</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-users"></i></div>
                    <h4>Political Participation</h4>
                    <p>How citizens can participate in the political process beyond just voting.</p>
                    <ul class="civic-list">
                        <li>Joining a political party</li>
                        <li>Becoming a candidate</li>
                        <li>Election observation</li>
                        <li>Community engagement</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-balance-scale"></i></div>
                    <h4>Electoral Laws</h4>
                    <p>Understanding the legal framework governing elections in South Sudan.</p>
                    <ul class="civic-list">
                        <li>Elections Act 2012</li>
                        <li>Political Parties Act</li>
                        <li>Electoral offenses</li>
                        <li>Dispute resolution</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-shield-alt"></i></div>
                    <h4>Election Integrity</h4>
                    <p>How to protect the integrity of elections and report irregularities.</p>
                    <ul class="civic-list">
                        <li>Recognizing election fraud</li>
                        <li>Reporting mechanisms</li>
                        <li>Observer roles</li>
                        <li>Transparency measures</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="civic-resource-card h-100">
                    <div class="civic-resource-icon"><i class="fas fa-venus"></i></div>
                    <h4>Women in Elections</h4>
                    <p>Promoting women's participation and representation in the electoral process.</p>
                    <ul class="civic-list">
                        <li>Gender quotas</li>
                        <li>Women candidates</li>
                        <li>Gender-based violence prevention</li>
                        <li>Empowerment programs</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <h3>Downloadable Resources</h3>
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-3">
                <a href="{{ asset('assets/documents/voter-guide.pdf') }}" class="btn btn-outline-nec" download><i class="fas fa-file-pdf me-2"></i>Voter's Guide (PDF)</a>
                <a href="{{ asset('assets/documents/electoral-process.pdf') }}" class="btn btn-outline-nec" download><i class="fas fa-file-pdf me-2"></i>Electoral Process (PDF)</a>
                <a href="{{ asset('assets/documents/rights-responsibilities.pdf') }}" class="btn btn-outline-nec" download><i class="fas fa-file-pdf me-2"></i>Rights & Responsibilities (PDF)</a>
            </div>
        </div>
    </div>
</section>
@endsection
