<!-- FOOTER -->
<footer class="nec-footer">
    <div class="footer-newsletter">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h4 class="newsletter-title">Subscribe to Our Newsletter</h4>
                    <p class="newsletter-text">Stay informed with the latest NEC updates, election schedules, and voter information.</p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form" id="newsletterForm" method="POST" action="{{ route('newsletter.subscribe') }}">
                        @csrf
                        <input type="hidden" name="newsletter_subscribe" value="1">
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Your name" required style="border-radius:50px 0 0 50px;">
                            <input type="email" name="email" class="form-control" placeholder="Your email" required>
                            <button class="btn btn-nec-danger fw-bold" type="submit" style="border-radius:0 50px 50px 0;padding:0 20px;font-size:0.82rem;">SUBSCRIBE</button>
                        </div>
                        <div class="newsletter-msg mt-2" style="font-size:0.82rem;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="footer-widget w-100">
                        <div class="footer-brand mb-3" style="display:flex;align-items:center;gap:14px;">
                            <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" width="80" height="80" style="object-fit:contain;max-width:100%;border-radius:5px;">
                            <div>
                                <span style="display:block;font-size:1.5rem;font-weight:900;color:var(--nec-gold);line-height:1;">NEC</span>
                                <span style="display:block;width:24px;height:2px;background:var(--nec-gold);margin:5px 0;border-radius:1px;"></span>
                                <span style="font-size:0.65rem;text-transform:uppercase;letter-spacing:3px;color:rgba(255,255,255,.45);">Republic of South Sudan</span>
                            </div>
                        </div>
                        <p class="text-white-50 mb-3" style="line-height:1.8;font-size:0.88rem;text-align:justify;">The National Elections Commission (NEC) is the independent constitutional body responsible for organizing and supervising elections in South Sudan.</p>
                        <h5 style="color:var(--nec-gold);font-size:0.85rem;font-weight:600;margin-bottom:10px;text-transform:uppercase;letter-spacing:1px;">Contact Us</h5>
                        <div class="footer-contact mb-3">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2" style="color:var(--nec-gold);"></i>{{ \App\Helpers\NecHelper::setting_get('contact_address', 'NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Thongpiny, Juba') }}</p>
                            @if(\App\Helpers\NecHelper::setting_get('public_show_socials', '1') === '1')
                            <p class="mb-1"><i class="fas fa-phone me-2" style="color:var(--nec-gold);"></i>{{ \App\Helpers\NecHelper::setting_get('contact_phone', '+211 (0) 912 345 678') }}</p>
                            <p class="mb-1"><i class="fas fa-envelope me-2" style="color:var(--nec-gold);"></i>{{ \App\Helpers\NecHelper::setting_get('contact_email', 'info@nec.gov.ss') }}</p>
                            @endif
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><i class="fas fa-clock me-2" style="color:var(--nec-gold);"></i>{{ \App\Helpers\NecHelper::setting_get('office_hours', 'Mon – Fri: 8:00 AM – 5:00 PM') }}</p>
                            <p class="mb-1" style="padding-left:28px;color:rgba(255,255,255,.5);font-size:0.85rem;">Sat – Sun: Closed</p>
                        </div>
                        @if(\App\Helpers\NecHelper::setting_get('public_show_socials', '1') === '1')
                        <div class="footer-social">
                            @php
                            $socialLinks = [
                                ['icon' => 'fa-facebook-f', 'label' => 'Facebook', 'url' => \App\Helpers\NecHelper::setting_get('facebook_url')],
                                ['icon' => 'fa-x-twitter', 'label' => 'Twitter / X', 'url' => \App\Helpers\NecHelper::setting_get('twitter_url')],
                                ['icon' => 'fa-youtube', 'label' => 'YouTube', 'url' => \App\Helpers\NecHelper::setting_get('youtube_url')],
                                ['icon' => 'fa-instagram', 'label' => 'Instagram', 'url' => \App\Helpers\NecHelper::setting_get('instagram_url')],
                                ['icon' => 'fa-linkedin-in', 'label' => 'LinkedIn', 'url' => \App\Helpers\NecHelper::setting_get('linkedin_url')],
                                ['icon' => 'fa-whatsapp', 'label' => 'WhatsApp', 'url' => \App\Helpers\NecHelper::setting_get('whatsapp_number') ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', \App\Helpers\NecHelper::setting_get('whatsapp_number')) : ''],
                            ];
                            @endphp
                            @foreach($socialLinks as $sl)
                            <a href="{{ $sl['url'] ?: '#' }}" class="social-icon" target="_blank" rel="noopener" aria-label="{{ $sl['label'] }}"><i class="fab {{ $sl['icon'] }}"></i></a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-lg d-flex">
                    <div class="footer-widget w-100">
                        <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Quick Links</h5>
                        <ul class="footer-links list-unstyled mb-0">
                            <li><a href="{{ route('about.index') }}">About NEC</a></li>
                            <li><a href="{{ route('about.mandate') }}">Our Mandate</a></li>
                            <li><a href="{{ route('about.commissioners') }}">Commissioners</a></li>
                            <li><a href="{{ route('about.departments') }}">The Secretariat</a></li>
                            <li><a href="{{ route('about.mandate') }}">Vision &amp; Mission</a></li>
                            <li><a href="{{ route('elections.calendar') }}">Election Calendar</a></li>
                            <li><a href="{{ route('elections.results') }}">Election Results</a></li>
                            <li><a href="{{ route('parties.index') }}">Political Parties</a></li>
                            <li><a href="{{ route('candidates.index') }}">Candidates</a></li>
                            <li><a href="{{ route('downloads.index') }}">Downloads</a></li>
                            <li><a href="{{ route('observers.index') }}">Observer Accreditation</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg d-flex">
                    <div class="footer-widget w-100">
                        <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">For Voters</h5>
                        <ul class="footer-links list-unstyled mb-0">
                            <li><a href="{{ route('voter.register') }}">Voter Registration</a></li>
                            <li><a href="{{ route('voter.verify') }}">Verify Registration</a></li>
                            <li><a href="{{ route('voter.polling-finder') }}">Find Polling Station</a></li>
                            <li><a href="{{ route('voter.transfer') }}">Transfer Request</a></li>
                            <li><a href="{{ route('voter.inquiry') }}">Voter Inquiry</a></li>
                            <li><a href="{{ route('constituencies.index') }}">Find Constituency</a></li>
                            <li><a href="{{ route('voter.education') }}">Voter Education</a></li>
                            <li><a href="{{ route('voter.how-to-vote') }}">How to Vote</a></li>
                            <li><a href="{{ route('downloads.forms') }}">Download Forms</a></li>
                            <li><a href="{{ route('voter.report-issue') }}">Report Issue / Complaint</a></li>
                            <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg d-flex">
                    <div class="footer-widget w-100">
                        <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Resources</h5>
                        <ul class="footer-links list-unstyled mb-0">
                            <li><a href="{{ route('media.news') }}">News &amp; Updates</a></li>
                            <li><a href="{{ route('media.gallery') }}">Photo Gallery</a></li>
                            <li><a href="{{ route('media.videos') }}">Videos</a></li>
                            <li><a href="{{ route('media.publications') }}">Publications</a></li>
                            <li><a href="{{ route('media.press-releases') }}">Press Releases</a></li>
                            <li><a href="{{ route('media.speeches') }}">Speeches</a></li>
                            <li><a href="{{ route('reports.annual') }}">Annual Reports</a></li>
                            <li><a href="{{ route('elections.types') }}">Election Types</a></li>
                            <li><a href="{{ route('downloads.forms') }}">Forms &amp; Documents</a></li>
                            <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg d-flex">
                    <div class="footer-widget w-100">
                        <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Support</h5>
                        <ul class="footer-links list-unstyled mb-0">
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                            <li><a href="{{ route('contact.index') }}">Send a Message</a></li>
                            <li><a href="{{ route('contact.index') }}">Office Locations</a></li>
                            <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                            <li><a href="{{ route('help.index') }}">Help Center</a></li>
                            <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                            <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('legal.terms-of-use') }}">Terms of Use</a></li>
                            <li><a href="{{ route('legal.accessibility') }}">Accessibility</a></li>
                            <li><a href="{{ route('careers.index') }}">Careers</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright mb-0">&copy; {{ date('Y') }} National Elections Commission – South Sudan. All rights reserved.</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-bottom-links mb-0">
                        <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms-of-use') }}">Terms of Service</a></li>
                        <li><a href="{{ route('legal.accessibility') }}">Accessibility</a></li>
                        <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- SEARCH MODAL -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-search text-muted" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-2 fw-bold">Search NEC South Sudan</h4>
                    <p class="text-muted">Enter your search term to find information across the website.</p>
                </div>
                <form action="{{ route('search.results') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control border-end-0" placeholder="Search for candidates, elections, forms..." required>
                        <button class="btn btn-success" type="submit" style="background: var(--nec-green); border-color: var(--nec-green);">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- WHATSAPP FLOAT -->
<div class="whatsapp-float">
    <a href="https://wa.me/211912345678" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>

<!-- SCROLL TO TOP -->
<button class="scroll-to-top" id="scrollToTop" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>
