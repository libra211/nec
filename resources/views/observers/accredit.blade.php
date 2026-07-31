@extends('layouts.app', ['title' => 'Observer Accreditation - NEC South Sudan', 'active_page' => 'observers'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Observer Accreditation</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Observer Accreditation</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-binoculars text-white-50" style="font-size:3.5rem;opacity:0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">

                <h3 class="fw-bold mb-4">Accreditation Process</h3>
                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="card border-0 text-center p-3 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px;background:var(--nec-green);color:#fff;font-weight:900;font-size:1.3rem;">1</div>
                            <h6 class="fw-bold small mb-1">Submit Application</h6>
                            <small class="text-muted">Complete and submit the accreditation application form with required documents.</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 text-center p-3 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px;background:var(--nec-gold);color:#fff;font-weight:900;font-size:1.3rem;">2</div>
                            <h6 class="fw-bold small mb-1">Review</h6>
                            <small class="text-muted">NEC reviews the application for completeness and eligibility.</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 text-center p-3 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px;background:var(--nec-blue);color:#fff;font-weight:900;font-size:1.3rem;">3</div>
                            <h6 class="fw-bold small mb-1">Approval</h6>
                            <small class="text-muted">Upon successful review, the Commission approves the accreditation.</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 text-center p-3 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px;background:var(--nec-red);color:#fff;font-weight:900;font-size:1.3rem;">4</div>
                            <h6 class="fw-bold small mb-1">Accreditation</h6>
                            <small class="text-muted">Observer receives official accreditation certificate and materials.</small>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold mb-3">Requirements</h4>
                <ul class="list-group list-group-flush mb-5">
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Valid passport or national ID for individual applicants</li>
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Letter of appointment from the nominating organization</li>
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Completed accreditation application form</li>
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Signed code of conduct for election observers</li>
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Curriculum vitae or biography of proposed observers</li>
                    <li class="list-group-item d-flex align-items-center gap-3 px-3"><i class="fas fa-check-circle" style="color:var(--nec-green);"></i> Proof of institutional registration (for organizations)</li>
                </ul>

                <h4 class="fw-bold mb-3">Guidelines for Observers</h4>
                <div class="card border-0 mb-5" style="background:#fff;box-shadow:var(--nec-shadow-xs);border-radius:12px;">
                    <div class="card-body p-4">
                        <p>The National Elections Commission (NEC) of South Sudan welcomes domestic and international election observers to participate in the 2026 General Elections. Observer accreditation is governed by the <strong>National Elections Act</strong> and the <strong>Code of Conduct for Election Observers</strong>.</p>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-file-alt me-2"></i>Submission Deadline</h6>
                        <p>All accreditation applications must be submitted no later than <strong>30 November 2026</strong>. Late submissions will not be considered.</p>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-clock me-2"></i>Processing Time</h6>
                        <p>Applications are processed within <strong>5 working days</strong> of receipt. Incomplete applications will be returned for correction and may cause delays.</p>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-gavel me-2"></i>Code of Conduct</h6>
                        <p>All accredited observers must adhere to the NEC Code of Conduct, which includes:</p>
                        <ul class="mb-0">
                            <li class="mb-1">Maintaining strict impartiality and neutrality at all times</li>
                            <li class="mb-1">Not obstructing or interfering with the electoral process</li>
                            <li class="mb-1">Not expressing personal or organizational opinions about candidates or parties</li>
                            <li class="mb-1">Respecting the confidentiality of the voting process</li>
                            <li class="mb-1">Wearing the official observer identification at all times</li>
                            <li class="mb-1">Submitting a final observation report to NEC within 30 days after the election</li>
                        </ul>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-id-card me-2"></i>Accreditation Badges</h6>
                        <p>Approved observers will receive an accreditation badge that must be visibly worn at all times during observation activities. Badges are non-transferable and must be returned to NEC upon completion of the observation mission or upon request.</p>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-map-marker-alt me-2"></i>Areas of Observation</h6>
                        <p>Observers may monitor all stages of the electoral process, including:</p>
                        <ul class="mb-0">
                            <li class="mb-1">Voter registration and display of the provisional voter register</li>
                            <li class="mb-1">Campaign activities and political party rallies</li>
                            <li class="mb-1">Polling station setup and voting procedures on Election Day</li>
                            <li class="mb-1">Ballot counting and results tabulation</li>
                            <li class="mb-1">Post-election dispute resolution processes</li>
                        </ul>

                        <h6 class="fw-bold mt-4 mb-2" style="color:var(--nec-green);"><i class="fas fa-file-export me-2"></i>Reporting Requirements</h6>
                        <p>Accredited observers are required to submit a preliminary statement within 48 hours after the close of polling and a comprehensive final report within 30 days after the announcement of final results. Reports should be submitted electronically to <a href="mailto:observers@nec.gov.ss" style="color:var(--nec-green);">observers@nec.gov.ss</a>.</p>
                    </div>
                </div>

                <h4 class="fw-bold mb-3">Frequently Asked Questions</h4>
                <div class="accordion mb-5" id="observerFAQ">
                    <div class="accordion-item border-0 mb-2" style="box-shadow:var(--nec-shadow-xs);border-radius:10px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="font-size:0.9rem;border-radius:10px;">
                                Who can apply for observer accreditation?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#observerFAQ">
                            <div class="accordion-body small text-muted">Domestic and international organizations, civil society groups, diplomatic missions, and regional/international bodies with a vested interest in democratic processes may apply. Individual applicants must be sponsored by a recognized organization.</div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-2" style="box-shadow:var(--nec-shadow-xs);border-radius:10px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="font-size:0.9rem;border-radius:10px;">
                                Is there a fee for accreditation?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#observerFAQ">
                            <div class="accordion-body small text-muted">No. Observer accreditation is provided free of charge by the National Elections Commission.</div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-2" style="box-shadow:var(--nec-shadow-xs);border-radius:10px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="font-size:0.9rem;border-radius:10px;">
                                How long is the accreditation valid?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#observerFAQ">
                            <div class="accordion-body small text-muted">Accreditation is valid for the duration of the election cycle, from the date of issuance through the announcement of final results.</div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-2" style="box-shadow:var(--nec-shadow-xs);border-radius:10px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="font-size:0.9rem;border-radius:10px;">
                                Can accreditation be revoked?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#observerFAQ">
                            <div class="accordion-body small text-muted">Yes. NEC reserves the right to revoke accreditation if an observer violates the Code of Conduct, engages in partisan activities, or fails to comply with NEC directives.</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card border-0 mb-4" style="background:#fff;box-shadow:var(--nec-shadow-xs);border-radius:12px;">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:56px;height:56px;background:var(--nec-green);color:#fff;font-size:1.4rem;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Start Your Application</h6>
                        <p class="small text-muted mb-3">Complete the online accreditation application form.</p>
                        <a href="{{ route('observers.apply') }}" class="btn w-100 text-white fw-semibold" style="background:var(--nec-green);border-radius:8px;font-size:0.85rem;">
                            <i class="fas fa-edit me-1"></i> Apply Now
                        </a>
                    </div>
                </div>

                <div class="card border-0 mb-4" style="background:#fff;box-shadow:var(--nec-shadow-xs);border-radius:12px;">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:56px;height:56px;background:var(--nec-green);color:#fff;font-size:1.4rem;">
                            <i class="fas fa-download"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Download Forms</h6>
                        <p class="small text-muted mb-3">Download the official accreditation forms and code of conduct.</p>
                        <a href="#" class="btn btn-sm btn-outline-success w-100 mb-2" style="border-radius:8px;font-size:0.82rem;"><i class="fas fa-file-pdf me-1"></i> Accreditation Form (PDF)</a>
                        <a href="#" class="btn btn-sm btn-outline-success w-100" style="border-radius:8px;font-size:0.82rem;"><i class="fas fa-file-pdf me-1"></i> Code of Conduct (PDF)</a>
                    </div>
                </div>

                <div class="card border-0 mb-4" style="background:#fff;box-shadow:var(--nec-shadow-xs);border-radius:12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--nec-green);"><i class="fas fa-phone me-2"></i>Contact</h6>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle" style="width:40px;height:40px;background:var(--nec-green);color:#fff;font-size:0.9rem;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">NEC Headquarters</div>
                                <small class="text-muted">NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Juba Na Bari, Juba</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle" style="width:40px;height:40px;background:var(--nec-green);color:#fff;font-size:0.9rem;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Email</div>
                                <small class="text-muted">observers@nec.gov.ss</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle" style="width:40px;height:40px;background:var(--nec-green);color:#fff;font-size:0.9rem;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Phone</div>
                                <small class="text-muted">+211 912 345 678</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:56px;height:56px;background:#10b98120;color:#10b981;font-size:1.5rem;">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h6 class="fw-bold" style="color:#065f46;">Stay Updated</h6>
                        <p class="small mb-3" style="color:#047857;">Subscribe to receive observer updates and announcements.</p>
                        <form method="POST" action="{{ url('api/v1/newsletter') }}" id="observerNewsletterForm">
                            <input type="hidden" name="name" value="Observer Subscriber">
                            <input type="email" name="email" class="form-control form-control-sm mb-2" placeholder="Your email address" required style="border-radius:8px;border:1.5px solid #bbf7d0;">
                            <button type="submit" class="btn btn-sm w-100 text-white" style="background:#10b981;border-radius:8px;font-weight:600;">Subscribe</button>
                            <small class="newsletter-msg mt-1" style="display:block;font-size:0.75rem;"></small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var nf = document.getElementById('observerNewsletterForm');
    if (nf) {
        nf.addEventListener('submit', function(e) {
            e.preventDefault();
            var fd = new FormData(this);
            var msg = this.querySelector('.newsletter-msg');
            var btn = this.querySelector('button');
            msg.innerHTML = '<span style="color:#666;"><i class="fas fa-spinner fa-spin"></i> Subscribing...</span>';
            btn.disabled = true;
            fetch(this.getAttribute('action'), { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                msg.innerHTML = data.success
                    ? '<span style="color:#28a745;"><i class="fas fa-check-circle"></i> ' + data.message + '</span>'
                    : '<span style="color:#dc3545;"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>';
                if (data.success) nf.reset();
            })
            .catch(function() {
                msg.innerHTML = '<span style="color:#dc3545;"><i class="fas fa-exclamation-circle"></i> Network error.</span>';
            })
            .finally(function() {
                btn.disabled = false;
                setTimeout(function() { msg.innerHTML = ''; }, 6000);
            });
        });
    }
});
</script>
@endsection
