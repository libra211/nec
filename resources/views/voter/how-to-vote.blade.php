@extends('layouts.app', ['title' => 'How to Vote - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">How to Vote</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">How to Vote</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">How to Cast Your Vote</h2>
            <p>A step-by-step guide to voting on Election Day</p>
        </div>

        <div class="voting-steps">
            @php
            $steps = [
                ['icon' => 'fa-home', 'title' => 'Go to Your Polling Station', 'desc' => 'Find your designated polling station using the NEC website or your voter registration card. Polling stations are open from 6:00 AM to 6:00 PM.'],
                ['icon' => 'fa-id-card', 'title' => 'Bring Your Voter ID', 'desc' => 'Carry your voter identification card or any valid government-issued ID. You must be on the voters roll to cast your vote.'],
                ['icon' => 'fa-user-check', 'title' => 'Identity Verification', 'desc' => 'The polling station officials will verify your identity against the voters roll and check your finger for indelible ink.'],
                ['icon' => 'fa-book', 'title' => 'Receive Your Ballot Paper', 'desc' => 'You will receive ballot papers for each election being held. Each ballot will have the candidates and parties contesting.'],
                ['icon' => 'fa-pen', 'title' => 'Mark Your Vote', 'desc' => 'Go to the private voting booth and mark an X or circle next to your chosen candidate on each ballot paper.'],
                ['icon' => 'fa-box', 'title' => 'Cast Your Ballot', 'desc' => 'Fold your ballot paper and place it in the ballot box. Ensure your vote remains secret.'],
                ['icon' => 'fa-hand-pointer', 'title' => 'Ink Your Finger', 'desc' => 'Your finger will be marked with indelible ink to prevent double voting. This is your proof of participation.'],
                ['icon' => 'fa-home', 'title' => 'Leave the Polling Station', 'desc' => 'You may leave after casting your vote. Results will be announced at the polling station and transmitted to the tallying center.'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="voting-step {{ $i % 2 === 0 ? 'left' : 'right' }}" data-aos="fade-{{ $i % 2 === 0 ? 'right' : 'left' }}">
                <div class="step-number-badge">{{ $i + 1 }}</div>
                <div class="step-content">
                    <div class="step-icon"><i class="fas {{ $step['icon'] }}"></i></div>
                    <h4>{{ $step['title'] }}</h4>
                    <p>{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-5 g-4">
            <div class="col-lg-4" data-aos="fade-up">
                <div class="tip-card">
                    <h5><i class="fas fa-lightbulb text-nec-gold me-2"></i>Important Tips</h5>
                    <ul>
                        <li>Arrive early to avoid long queues</li>
                        <li>Bring your own pen (black or blue)</li>
                        <li>Do not take photos of your ballot</li>
                        <li>Keep your vote secret</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="tip-card">
                    <h5><i class="fas fa-exclamation-triangle text-nec-red me-2"></i>What NOT to Do</h5>
                    <ul>
                        <li>Do not vote more than once</li>
                        <li>Do not take someone else's ballot</li>
                        <li>Do not show your marked ballot</li>
                        <li>Do not campaign at polling stations</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="tip-card">
                    <h5><i class="fas fa-phone text-nec-green me-2"></i>Need Help?</h5>
                    <ul>
                        <li>Ask polling station officials</li>
                        <li>Call NEC hotline: +211 920 000 000</li>
                        <li>Contact election observers</li>
                        <li>WhatsApp: +211 920 000 000</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
