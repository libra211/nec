@extends('layouts.app', ['title' => 'Careers', 'active_page' => 'careers'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Careers</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Careers</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-briefcase text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                    <h2 class="fw-bold mb-3" style="color: var(--nec-black);">Working at NEC</h2>
                    <p class="text-muted">The National Elections Commission (NEC) offers a unique opportunity to contribute to the democratic development of South Sudan. We are seeking dedicated, professional, and motivated individuals to join our team in various capacities across the country.</p>
                    <p class="text-muted">NEC provides a dynamic work environment with opportunities for professional growth, competitive compensation, and the chance to make a meaningful impact on the electoral process in South Sudan.</p>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold mb-4" style="color: var(--nec-black);">Current Vacancies</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Position</th>
                                    <th class="fw-bold">Department</th>
                                    <th class="fw-bold">Location</th>
                                    <th class="fw-bold">Closing Date</th>
                                    <th class="fw-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senior IT Officer</td>
                                    <td>Information Technology</td>
                                    <td>Juba</td>
                                    <td>31 Jul 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Electoral Operations Manager</td>
                                    <td>Operations</td>
                                    <td>Juba</td>
                                    <td>15 Aug 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Communications Specialist</td>
                                    <td>Communications</td>
                                    <td>Juba</td>
                                    <td>30 Aug 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Field Coordinator – Central Equatoria</td>
                                    <td>Field Operations</td>
                                    <td>Central Equatoria</td>
                                    <td>15 Sep 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Data Analyst</td>
                                    <td>Research & Planning</td>
                                    <td>Juba</td>
                                    <td>30 Sep 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Finance Officer</td>
                                    <td>Finance</td>
                                    <td>Juba</td>
                                    <td>15 Oct 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                                <tr>
                                    <td>Voter Education Officer</td>
                                    <td>Civic & Voter Education</td>
                                    <td>Various</td>
                                    <td>31 Oct 2026</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-success" style="border-color: var(--nec-green); color: var(--nec-green);">Apply</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">How to Apply</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-file-upload fs-2" style="color: var(--nec-green);"></i>
                                        <h5 class="fw-bold mb-0">Submit Application</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Send your CV, cover letter, and certified copies of academic credentials to careers@nec.gov.ss with the position title in the subject line.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-file-pdf fs-2" style="color: var(--nec-gold);"></i>
                                        <h5 class="fw-bold mb-0">Application Form</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Download the NEC employment application form from our Downloads page and submit it with your supporting documents.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-clock fs-2" style="color: var(--nec-black);"></i>
                                        <h5 class="fw-bold mb-0">Application Timeline</h5>
                                    </div>
                                    <p class="text-muted small mb-0">Applications are reviewed on a rolling basis. Only shortlisted candidates will be contacted for interviews.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="fas fa-balance-scale fs-2" style="color: #dc3545;"></i>
                                        <h5 class="fw-bold mb-0">Equal Opportunity</h5>
                                    </div>
                                    <p class="text-muted small mb-0">NEC is an equal opportunity employer. We encourage applications from qualified candidates regardless of gender, ethnicity, disability, or religion.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Contact HR</h3>
                    <div class="bg-white p-4 rounded-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p class="mb-1"><i class="fas fa-envelope me-2" style="color: var(--nec-gold);"></i> careers@nec.gov.ss</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><i class="fas fa-phone me-2" style="color: var(--nec-gold);"></i> +211 (0) 912 345 678</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><i class="fas fa-map-marker-alt me-2" style="color: var(--nec-gold);"></i> Juba, South Sudan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
