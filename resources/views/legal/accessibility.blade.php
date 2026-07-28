@extends('layouts.app', ['title' => 'Accessibility', 'active_page' => 'legal'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Accessibility</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Accessibility</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-universal-access text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                <div class="mb-5">
                    <h2 class="fw-bold mb-3" style="color: var(--nec-black);">Our Commitment</h2>
                    <p class="text-muted">The National Elections Commission (NEC) is committed to ensuring that our website is accessible to all users, including persons with disabilities. We strive to comply with the Web Content Accessibility Guidelines (WCAG) 2.1 at the AA level to provide an inclusive and equitable online experience.</p>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Standards Compliance</h3>
                    <p class="text-muted">Our website is designed and developed to meet or exceed the following accessibility standards:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                            <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                            <div><strong>WCAG 2.1 AA</strong> — We aim to conform to all applicable success criteria at Level AA.</div>
                        </li>
                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                            <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                            <div><strong>Section 508</strong> — Our website is designed to be compatible with US Section 508 requirements.</div>
                        </li>
                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                            <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                            <div><strong>EN 301 549</strong> — We follow European accessibility standards for ICT products and services.</div>
                        </li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Accessibility Features</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-keyboard fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Keyboard Navigation</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Full keyboard navigability with visible focus indicators. Use Tab, Enter, and Arrow keys to navigate.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-text-height fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Resizable Text</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Text can be resized up to 200% without loss of content or functionality using browser zoom.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-images fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Alternative Text</h5>
                                    </div>
                                    <p class="text-muted small mb-0">All images include descriptive alternative text for screen reader users.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-adjust fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Colour Contrast</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Sufficient colour contrast ratios for text and interactive elements to ensure readability.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-assistive-listening-systems fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Screen Reader Compatible</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Semantic HTML structure and ARIA landmarks for compatibility with assistive technologies.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-closed-captioning fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Multimedia Captions</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Video content includes captions and transcripts where available.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Browser Compatibility</h3>
                    <p class="text-muted">The NEC website is designed to work with the following browsers:</p>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 border rounded-3">
                                <i class="fab fa-chrome fs-2 mb-2" style="color: #4285F4;"></i>
                                <small class="d-block fw-semibold">Chrome</small>
                                <small class="text-muted">Latest 2 versions</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 border rounded-3">
                                <i class="fab fa-firefox fs-2 mb-2" style="color: #FF7139;"></i>
                                <small class="d-block fw-semibold">Firefox</small>
                                <small class="text-muted">Latest 2 versions</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 border rounded-3">
                                <i class="fab fa-safari fs-2 mb-2" style="color: #006CFF;"></i>
                                <small class="d-block fw-semibold">Safari</small>
                                <small class="text-muted">Latest 2 versions</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3 border rounded-3">
                                <i class="fab fa-edge fs-2 mb-2" style="color: #0078D7;"></i>
                                <small class="d-block fw-semibold">Edge</small>
                                <small class="text-muted">Latest 2 versions</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Contact for Accessibility Issues</h3>
                    <p class="text-muted">If you encounter any accessibility barriers on our website or have suggestions for improvement, please contact us:</p>
                    <div class="bg-white p-4 rounded-3">
                        <p class="mb-2"><strong>National Elections Commission</strong></p>
                        <p class="mb-1"><i class="fas fa-envelope me-2" style="color: var(--nec-gold);"></i> accessibility@nec.gov.ss</p>
                        <p class="mb-1"><i class="fas fa-phone me-2" style="color: var(--nec-gold);"></i> +211 (0) 912 345 678</p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2" style="color: var(--nec-gold);"></i> Juba, South Sudan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
