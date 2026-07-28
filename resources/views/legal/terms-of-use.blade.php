@extends('layouts.app', ['title' => 'Terms of Use', 'active_page' => 'legal'])

@php
$content = null;
try {
    $cmsPage = \App\Models\CmsPage::where('slug', 'terms-of-use')->first();
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
                <h1 class="text-white fw-bold mb-2">Terms of Use</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Terms of Use</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-file-contract text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                    <div class="accordion" id="termsAccordion">
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#acceptance" style="color: var(--nec-black);">
                                    Acceptance of Terms
                                </button>
                            </h2>
                            <div id="acceptance" class="accordion-collapse collapse show" data-bs-parent="#termsAccordion">
                                <div class="accordion-body text-muted">
                                    <p>By accessing and using the National Elections Commission (NEC) website, you accept and agree to be bound by these Terms of Use. If you do not agree to these terms, please do not use this website.</p>
                                    <p>NEC reserves the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of the website after any modifications constitutes acceptance of the revised terms.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#userResponsibilities" style="color: var(--nec-black);">
                                    User Responsibilities
                                </button>
                            </h2>
                            <div id="userResponsibilities" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body text-muted">
                                    <p>You agree to use this website only for lawful purposes and in a manner that does not infringe the rights of others. You must not:</p>
                                    <ul>
                                        <li>Provide false or misleading information</li>
                                        <li>Attempt to gain unauthorised access to our systems</li>
                                        <li>Disrupt or interfere with the website's functionality</li>
                                        <li>Use the website for any fraudulent or illegal activity</li>
                                        <li>Upload or transmit malicious code or viruses</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#intellectualProperty" style="color: var(--nec-black);">
                                    Intellectual Property
                                </button>
                            </h2>
                            <div id="intellectualProperty" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body text-muted">
                                    <p>All content on this website, including text, graphics, logos, images, documents, and software, is the property of NEC or its content providers and is protected by applicable intellectual property laws.</p>
                                    <p>You may download and print content for personal, non-commercial use provided you retain all copyright and proprietary notices. Any other use requires prior written permission from NEC.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#limitationLiability" style="color: var(--nec-black);">
                                    Limitation of Liability
                                </button>
                            </h2>
                            <div id="limitationLiability" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body text-muted">
                                    <p>The NEC website and its content are provided on an "as is" basis. NEC makes no warranties, expressed or implied, regarding the accuracy, completeness, or reliability of the information provided.</p>
                                    <p>NEC shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising from your use of or inability to use this website. This limitation applies to the fullest extent permitted by law.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#governingLaw" style="color: var(--nec-black);">
                                    Governing Law
                                </button>
                            </h2>
                            <div id="governingLaw" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body text-muted">
                                    <p>These Terms of Use shall be governed by and construed in accordance with the laws of the Republic of South Sudan. Any disputes arising under these terms shall be subject to the exclusive jurisdiction of the courts of South Sudan.</p>
                                    <p>If any provision of these terms is found to be invalid or unenforceable, the remaining provisions shall remain in full force and effect.</p>
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
