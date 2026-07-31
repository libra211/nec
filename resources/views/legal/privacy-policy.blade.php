@extends('layouts.app', ['title' => 'Privacy Policy', 'active_page' => 'legal'])

@php
$content = null;
try {
    $cmsPage = \App\Models\CmsPage::where('slug', 'privacy-policy')->first();
    if ($cmsPage) {
        $content = $cmsPage->content;
    }
} catch (\Exception $e) {}
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Privacy Policy</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Privacy Policy</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-shield-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($content)
                    <div class="cms-content">{!! $content !!}</div>
                @else
                    <div class="accordion" id="privacyAccordion">
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#infoWeCollect" style="color: var(--nec-black);">
                                    Information We Collect
                                </button>
                            </h2>
                            <div id="infoWeCollect" class="accordion-collapse collapse show" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>We collect information that you provide directly to us, including your name, address, date of birth, national ID number, and contact details when you register to vote or interact with our services. We also collect technical information such as IP address, browser type, and device information when you visit our website.</p>
                                    <p>We may collect sensitive personal data only where necessary and with your explicit consent, in compliance with applicable data protection laws.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#howWeUse" style="color: var(--nec-black);">
                                    How We Use Information
                                </button>
                            </h2>
                            <div id="howWeUse" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>We use the information we collect to process voter registration, maintain the voters register, facilitate the electoral process, communicate with you about election matters, improve our services, and comply with legal obligations.</p>
                                    <p>Your personal data is used solely for electoral purposes and will not be used for any other purpose without your consent unless required by law.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dataProtection" style="color: var(--nec-black);">
                                    Data Protection
                                </button>
                            </h2>
                            <div id="dataProtection" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>We implement appropriate technical and organisational measures to protect your personal data against unauthorised access, alteration, disclosure, or destruction. These include encryption, access controls, regular security audits, and staff training on data protection.</p>
                                    <p>We retain your personal data only for as long as necessary to fulfil the purposes for which it was collected, or as required by law.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cookies" style="color: var(--nec-black);">
                                    Cookies
                                </button>
                            </h2>
                            <div id="cookies" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>Our website uses cookies and similar tracking technologies to enhance your browsing experience, analyse site traffic, and improve our services. We use essential cookies for website functionality and analytics cookies to understand how visitors interact with our site.</p>
                                    <p>You can control cookie preferences through your browser settings. Disabling certain cookies may affect the functionality of the website.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#thirdParty" style="color: var(--nec-black);">
                                    Third-Party Services
                                </button>
                            </h2>
                            <div id="thirdParty" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>We may engage third-party service providers to assist in delivering our services, including hosting, analytics, and communication platforms. These providers are bound by contractual obligations to protect your data and use it only for the purposes we specify.</p>
                                    <p>We do not sell, trade, or transfer your personal data to third parties without your consent, except where required by law.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactPrivacy" style="color: var(--nec-black);">
                                    Contact
                                </button>
                            </h2>
                            <div id="contactPrivacy" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                                <div class="accordion-body text-muted">
                                    <p>If you have any questions about this Privacy Policy or wish to exercise your data protection rights, please contact us:</p>
                                    <p><strong>National Elections Commission</strong><br>
                                    NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Juba Na Bari, Juba<br>
                                    Email: info@nec.gov.ss<br>
                                    Phone: +211 (0) 912 345 678</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
