@extends('layouts.app', ['title' => 'Voter ID Card - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Voter ID Card</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">Voter ID Card</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title">Your Voter Identification Card</h2>
                <p>The NEC Voter Identification Card is your official proof of registration and is required to vote on Election Day.</p>

                <h5 class="mt-4">About Your Voter ID Card</h5>
                <ul class="feature-list">
                    <li><i class="fas fa-check text-nec-green me-2"></i> Issued to all registered voters upon successful registration</li>
                    <li><i class="fas fa-check text-nec-green me-2"></i> Contains your unique voter identification number</li>
                    <li><i class="fas fa-check text-nec-green me-2"></i> Shows your name, photo, and polling station details</li>
                    <li><i class="fas fa-check text-nec-green me-2"></i> Must be presented at your polling station on Election Day</li>
                    <li><i class="fas fa-check text-nec-green me-2"></i> Valid for all national, state, and local elections</li>
                </ul>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <div class="voter-id-sample">
                    <div class="id-card-preview">
                        <div class="id-card-header">
                            <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" height="40">
                            <span>NATIONAL ELECTIONS COMMISSION</span>
                        </div>
                        <div class="id-card-body">
                            <div class="id-photo">
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Voter Photo" height="80">
                            </div>
                            <div class="id-details">
                                <p><strong>VOTER ID:</strong> NEC-SS-00000000</p>
                                <p><strong>Name:</strong> [Voter Name]</p>
                                <p><strong>Constituency:</strong> [Constituency]</p>
                                <p><strong>Polling Station:</strong> [Station]</p>
                            </div>
                        </div>
                        <div class="id-card-footer">
                            <small>Republic of South Sudan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="card h-100">
                    <div class="card-body">
                        <h4><i class="fas fa-redo text-nec-blue me-2"></i>Card Replacement</h4>
                        <p>If your voter ID card is lost, stolen, or damaged, you can request a replacement.</p>
                        <ul>
                            <li>Visit your nearest NEC office</li>
                            <li>Bring a valid government-issued ID</li>
                            <li>Complete the replacement form</li>
                            <li>A new card will be issued within 14 working days</li>
                        </ul>
                        <a href="{{ route('voter.inquiry') }}" class="btn btn-outline-nec btn-sm">Request Replacement</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100">
                    <div class="card-body">
                        <h4><i class="fas fa-question-circle text-nec-gold me-2"></i>Lost Your ID?</h4>
                        <p>If you don't have your voter ID card on Election Day, you can still vote if you appear on the voters roll.</p>
                        <ul>
                            <li>Bring any valid government-issued ID</li>
                            <li>Officials will verify your identity</li>
                            <li>You may need to provide additional proof</li>
                            <li>Contact NEC in advance if possible</li>
                        </ul>
                        <a href="{{ route('voter.forgot-id') }}" class="btn btn-outline-nec btn-sm">Recover Voter ID</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
