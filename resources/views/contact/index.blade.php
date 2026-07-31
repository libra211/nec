@extends('layouts.app', ['title' => 'Contact Us', 'active_page' => 'contact'])

@php
$n1 = rand(3, 8);
$n2 = rand(2, 7);
session(['contact_captcha' => $n1 + $n2]);
@endphp

@section('extra_head')
<style>
/* ─── Hero ─── */
.contact-hero {
    background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
    position: relative; overflow: hidden; padding: 80px 0 60px;
}
.contact-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.contact-hero .hero-content { position: relative; z-index: 1; }
.contact-hero h1 { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
.contact-hero p { font-size: 1.1rem; color: rgba(255,255,255,0.75); max-width: 560px; }

/* ─── Contact Info Cards ─── */
.contact-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 576px) { .contact-info-grid { grid-template-columns: 1fr; } }
.info-card {
    background: #fff; border: 1px solid #eef0f2; border-radius: 14px;
    padding: 20px; transition: all 0.25s;
    display: flex; align-items: flex-start; gap: 14px;
}
.info-card:hover { border-color: #c8d6d0; box-shadow: 0 4px 20px rgba(0,0,0,0.05); transform: translateY(-2px); }
.info-card .icon-wrap {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 18px;
}
.info-card .icon-wrap.green { background: #e8f5e9; color: #2d7d46; }
.info-card .icon-wrap.blue { background: #e3f2fd; color: #1565c0; }
.info-card .icon-wrap.amber { background: #fff8e1; color: #f9a825; }
.info-card .icon-wrap.purple { background: #f3e5f5; color: #7b1fa2; }
.info-card .cta-label { font-size: 12px; font-weight: 600; color: #8c8f94; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
.info-card .cta-value { font-weight: 600; color: #1d2327; line-height: 1.4; }

/* ─── Form ─── */
.form-card {
    background: #fff; border-radius: 16px; box-shadow: 0 2px 24px rgba(0,0,0,0.06);
    overflow: hidden;
}
.form-card .form-header {
    padding: 28px 32px 0;
}
.form-card .form-body { padding: 20px 32px 32px; }
.form-card h3 { font-weight: 700; margin-bottom: 4px; }
.form-card .form-subtitle { color: #8c8f94; font-size: 14px; margin-bottom: 20px; }

.form-floating {
    position: relative; margin-bottom: 16px;
}
.form-floating input, .form-floating select, .form-floating textarea {
    width: 100%; padding: 16px 14px 6px; font-size: 14px;
    border: 1.5px solid #dde0e4; border-radius: 10px;
    background: #fafbfc; transition: all 0.2s; outline: none;
    font-family: inherit;
}
.form-floating input:focus, .form-floating select:focus, .form-floating textarea:focus {
    border-color: var(--nec-green); background: #fff;
    box-shadow: 0 0 0 3px rgba(46,139,87,0.08);
}
.form-floating input.error, .form-floating select.error, .form-floating textarea.error {
    border-color: #dc3545; box-shadow: 0 0 0 3px rgba(220,53,69,0.08);
}
.form-floating label {
    position: absolute; top: 14px; left: 14px;
    font-size: 14px; color: #8c8f94; transition: all 0.15s;
    pointer-events: none; background: transparent; padding: 0 4px;
}
.form-floating input:focus ~ label, .form-floating input:not(:placeholder-shown) ~ label,
.form-floating textarea:focus ~ label, .form-floating textarea:not(:placeholder-shown) ~ label,
.form-floating select:focus ~ label, .form-floating select:not([value=""]):not([value=""]) ~ label {
    top: 4px; font-size: 11px; color: var(--nec-green); font-weight: 600;
}
.form-floating textarea { min-height: 120px; resize: vertical; padding-top: 22px; }
.form-floating .field-error { font-size: 11px; color: #dc3545; margin-top: 4px; display: none; }

/* Topic picker */
.topic-picker { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
@media (max-width: 576px) { .topic-picker { grid-template-columns: 1fr; } }
.topic-option {
    position: relative; padding: 14px 16px; border: 1.5px solid #e5e7eb;
    border-radius: 12px; cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; gap: 12px;
    background: #fafbfc;
}
.topic-option:hover { border-color: #c8d6d0; background: #f5f8f6; }
.topic-option.selected {
    border-color: var(--nec-green); background: #f0fdf4;
    box-shadow: 0 0 0 3px rgba(46,139,87,0.08);
}
.topic-option .topic-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.topic-option .topic-icon.green { background: #e8f5e9; color: #2d7d46; }
.topic-option .topic-icon.blue { background: #e3f2fd; color: #1565c0; }
.topic-option .topic-icon.amber { background: #fff8e1; color: #f9a825; }
.topic-option .topic-icon.purple { background: #f3e5f5; color: #7b1fa2; }
.topic-option .topic-icon.red { background: #fce4ec; color: #c62828; }
.topic-option .topic-icon.teal { background: #e0f2f1; color: #00695c; }
.topic-option .topic-icon.gray { background: #f5f5f5; color: #616161; }
.topic-option .topic-label { font-size: 13px; font-weight: 600; color: #1d2327; }
.topic-option .topic-desc { font-size: 11px; color: #8c8f94; }
.topic-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }

.form-error-msg {
    padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px;
    display: none; align-items: center; gap: 8px;
}
.form-error-msg.show { display: flex; }
.form-error-msg.error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.form-error-msg.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

.btn-submit {
    width: 100%; padding: 14px; border: none; border-radius: 12px;
    font-weight: 700; font-size: 15px; color: #fff;
    background: linear-gradient(135deg, #1a5c2e, #2d7d46);
    transition: all 0.25s; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(46,139,87,0.35); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-submit .spinner { display: none; }
.btn-submit.loading .spinner { display: inline-block; }
.btn-submit.loading .btn-text { display: none; }

/* ─── Map ─── */
.map-section { position: relative; height: 380px; border-radius: 16px; overflow: hidden; }
.map-section iframe { width: 100%; height: 100%; border: 0; }
.map-overlay {
    position: absolute; top: 20px; left: 20px;
    background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
    padding: 16px 20px; border-radius: 12px; max-width: 240px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.map-overlay h6 { font-weight: 700; margin-bottom: 2px; }
.map-overlay small { color: #8c8f94; }

/* ─── Human Verification ─── */
.human-check {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 16px; margin-bottom: 16px;
    background: #fffbeb; border: 1.5px dashed #fbbf24;
    border-radius: 10px; transition: background 0.2s;
}
.human-check:focus-within { background: #fef9c3; }
.human-check-icon {
    width: 34px; height: 34px; border-radius: 50%;
    background: #fef3c7; color: #d97706;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 14px;
}
.human-check-body { flex: 1; min-width: 0; }
.human-check-label { font-size: 10px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1px; }
.human-check-question { font-size: 14px; color: #1d2327; font-weight: 500; }
.human-check-question strong { color: #b45309; }
.human-check-input { flex-shrink: 0; }
.human-check-input input {
    width: 60px; padding: 8px 10px; text-align: center;
    border: 1.5px solid #fbbf24; border-radius: 8px;
    font-size: 16px; font-weight: 700; outline: none;
    background: #fff; transition: all 0.2s; -moz-appearance: textfield;
}
.human-check-input input::-webkit-outer-spin-button,
.human-check-input input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.human-check-input input:focus { border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,0.12); }
.human-check-input input.error { border-color: #dc3545; box-shadow: 0 0 0 3px rgba(220,53,69,0.08); }

/* ─── Animations ─── */
.fade-up { opacity: 0; transform: translateY(24px); transition: all 0.6s ease; }
.fade-up.visible { opacity: 1; transform: translateY(0); }
.fade-up-d1 { transition-delay: 0.1s; }
.fade-up-d2 { transition-delay: 0.2s; }
.fade-up-d3 { transition-delay: 0.3s; }
</style>
@endsection

@section('hero')
<!-- Hero -->
<section class="contact-hero">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <p class="text-white-50 fw-semibold text-uppercase small mb-2" style="letter-spacing:2px;">Get in Touch</p>
                <h1>We'd Love to Hear From You</h1>
                <p>Have a question about voter registration, observer accreditation, or any other inquiry? Our team is ready to assist you.</p>
            </div>
            <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200' width='160' height='160'%3E%3Ccircle cx='100' cy='100' r='90' fill='rgba(255,255,255,0.06)'/%3E%3Cpath d='M60 70h80v60H60z' fill='none' stroke='rgba(255,255,255,0.2)' stroke-width='2'/%3E%3Cpath d='M60 70l40 30 40-30' fill='none' stroke='rgba(255,255,255,0.2)' stroke-width='2'/%3E%3C/svg%3E" alt="Contact" style="opacity:0.6;">
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="fade-up visible">
                    <h3 class="fw-bold mb-3">Contact Information</h3>
                    <p class="text-muted small mb-4">Reach out to us through any of the channels below, or use the inquiry form.</p>
                </div>
                <div class="contact-info-grid fade-up visible fade-up-d1">
                    <div class="info-card">
                        <div class="icon-wrap blue"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="cta-label">Address</div>
                            <div class="cta-value">National Elections Commission<br>{{ \App\Helpers\NecHelper::setting_get('contact_address', 'NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Thongpiny, Juba') }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="icon-wrap green"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <div class="cta-label">Phone</div>
                            <div class="cta-value">{{ \App\Helpers\NecHelper::setting_get('contact_phone', '+211 912 345 678') }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="icon-wrap amber"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="cta-label">Email</div>
                            <div class="cta-value">{{ \App\Helpers\NecHelper::setting_get('contact_email', 'info@nec.gov.ss') }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="icon-wrap purple"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="cta-label">Working Hours</div>
                            <div class="cta-value">{{ \App\Helpers\NecHelper::setting_get('office_hours', 'Mon–Fri: 8:00 AM – 5:00 PM') }}<br>Sat–Sun: Closed</div>
                        </div>
                    </div>
                </div>

                <div class="fade-up visible fade-up-d2 mt-4">
                    <div class="map-section">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.987!2d31.582!3d4.851!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNMKwNTEnMDMuNiJOIDMxw4XCmjM5JzM1LjIiRQ!5e0!3m2!1sen!2ss!4v1" allowfullscreen loading="lazy"></iframe>
                        <div class="map-overlay">
                            <h6><i class="fas fa-map-pin text-success me-1"></i> NEC Headquarters</h6>
                            <small>Bilpam Road, Thongpiny, Juba, South Sudan</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="col-lg-7">
                <div class="form-card fade-up visible fade-up-d3">
                    <div class="form-header">
                        <h3>Send an Inquiry</h3>
                        <p class="form-subtitle">Choose a topic and fill in the details — we'll respond within 24 hours.</p>
                    </div>
                    <div class="form-body">
                        <div class="form-error-msg" id="formMsg"></div>
                        <form id="contactForm" method="POST" action="{{ url('api/v1/contact') }}">
                            @csrf
                            <input type="hidden" name="form_type" value="standard">

                            <label class="fw-bold text-muted small mb-2" style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Select Topic <span style="color:#dc3545;">*</span></label>
                            <div class="topic-picker" id="topicPicker">
                                @php
                                $topics = [
                                    'voter_registration'  => ['Voter Registration', 'Register or check your status', 'fas fa-vote-yea', 'green'],
                                    'observer_accreditation' => ['Observer Accreditation', 'Apply as an election observer', 'fas fa-user-check', 'blue'],
                                    'political_party'     => ['Political Party', 'Party registration & compliance', 'fas fa-flag', 'purple'],
                                    'media_press'         => ['Media / Press', 'Press accreditation & inquiries', 'fas fa-newspaper', 'amber'],
                                    'complaint'           => ['Complaint / Feedback', 'Report an issue or concern', 'fas fa-exclamation-triangle', 'red'],
                                    'partnership'         => ['Partnership', 'Collaboration & partnerships', 'fas fa-handshake', 'teal'],
                                    'general'             => ['General Inquiry', 'Something else', 'fas fa-comment-dots', 'gray'],
                                ];
                                $first = true;
                                @endphp
                                @foreach($topics as $val => $topic)
                                <label class="topic-option {{ $first ? 'selected' : '' }}">
                                    <input type="radio" name="subject" value="{{ $val }}" {{ $first ? 'checked' : '' }}>
                                    <div class="topic-icon {{ $topic[3] }}"><i class="{{ $topic[2] }}"></i></div>
                                    <div>
                                        <div class="topic-label">{{ $topic[0] }}</div>
                                        <div class="topic-desc">{{ $topic[1] }}</div>
                                    </div>
                                </label>
                                @php $first = false; @endphp
                                @endforeach
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="f_name" placeholder=" " required>
                                        <label for="f_name">Full Name <span style="color:#dc3545;">*</span></label>
                                        <div class="field-error" id="err_name">Please enter your name.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="f_email" placeholder=" " required>
                                        <label for="f_email">Email Address <span style="color:#dc3545;">*</span></label>
                                        <div class="field-error" id="err_email">Please enter a valid email.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" name="phone" id="f_phone" placeholder=" ">
                                        <label for="f_phone">Phone Number (optional)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" id="f_message" placeholder=" " required></textarea>
                                        <label for="f_message">Your Message <span style="color:#dc3545;">*</span></label>
                                        <div class="field-error" id="err_message">Please enter your message.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="human-check">
                                <div class="human-check-icon"><i class="fas fa-shield-halved"></i></div>
                                <div class="human-check-body">
                                    <div class="human-check-label">Human verification</div>
                                    <div class="human-check-question">What is <strong>{{ $n1 }}</strong> + <strong>{{ $n2 }}</strong>?</div>
                                </div>
                                <div class="human-check-input">
                                    <input type="number" name="captcha" id="f_captcha" min="2" max="18" placeholder="?" required>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit mt-2" id="submitBtn">
                                <span class="btn-text"><i class="fas fa-paper-plane me-2"></i> Send Message</span>
                                <span class="spinner"><i class="fas fa-spinner fa-spin me-2"></i> Sending...</span>
                            </button>
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
    const topicOptions = document.querySelectorAll('.topic-option');
    topicOptions.forEach(function(opt) {
        opt.addEventListener('click', function() {
            topicOptions.forEach(function(o) { o.classList.remove('selected'); });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const msgBox = document.getElementById('formMsg');

    function showError(fieldId, errId) {
        const field = document.getElementById(fieldId);
        const err = document.getElementById(errId);
        if (field) field.classList.add('error');
        if (err) err.style.display = 'block';
    }

    function clearErrors() {
        document.querySelectorAll('.form-floating input.error, .form-floating textarea.error, .form-floating select.error').forEach(function(el) {
            el.classList.remove('error');
        });
        document.querySelectorAll('.field-error').forEach(function(el) {
            el.style.display = 'none';
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        msgBox.className = 'form-error-msg';
        msgBox.style.display = 'none';

        var name = document.getElementById('f_name').value.trim();
        var email = document.getElementById('f_email').value.trim();
        var message = document.getElementById('f_message').value.trim();
        var captcha = document.getElementById('f_captcha').value.trim();
        var valid = true;

        if (!name) { showError('f_name', 'err_name'); valid = false; }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('f_email', 'err_email'); valid = false; }
        if (!message) { showError('f_message', 'err_message'); valid = false; }
        if (!captcha) { document.getElementById('f_captcha').classList.add('error'); valid = false; }

        if (!valid) return;

        var fd = new FormData(form);
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        fetch(form.getAttribute('action'), { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                msgBox.className = 'form-error-msg show ' + (data.success ? 'success' : 'error');
                msgBox.innerHTML = (data.success ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>') + ' ' + data.message;
                if (data.success) { setTimeout(function() { location.reload(); }, 2000); }
            })
            .catch(function() {
                msgBox.className = 'form-error-msg show error';
                msgBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> Network error. Please try again.';
            })
            .finally(function() {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                setTimeout(function() {
                    msgBox.className = 'form-error-msg';
                    msgBox.style.display = 'none';
                }, 8000);
            });
    });
});
</script>
@endsection
