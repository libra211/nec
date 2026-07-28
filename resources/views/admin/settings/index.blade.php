@extends('admin.layouts.app', ['title' => 'Settings'])

@php
    $activeTab = session('active_tab', request('tab', 'general'));
    $tabs = [
        'general' => ['icon' => 'fa-cog', 'label' => 'General'],
        'sms' => ['icon' => 'fa-sms', 'label' => 'SMS Gateway'],
        'email' => ['icon' => 'fa-envelope', 'label' => 'Email'],
        'social' => ['icon' => 'fa-share-alt', 'label' => 'Social Media'],
        'elections' => ['icon' => 'fa-vote-yea', 'label' => 'Elections'],
        'security' => ['icon' => 'fa-shield-alt', 'label' => 'Security'],
    ];
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-cogs me-2"></i>Site Settings</h2>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2" role="tablist">
                    @foreach($tabs as $key => $tab)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === $key ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $key }}" type="button" role="tab">
                            <i class="fas {{ $tab['icon'] }} me-1"></i> {{ $tab['label'] }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="tab-content">

                        {{-- TAB: GENERAL --}}
                        <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="tab-general" role="tabpanel">
                            <h5 class="fw-bold mb-3">General Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Site Name</label>
                                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name']->value ?? 'National Elections Commission' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Site Tagline</label>
                                    <input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline']->value ?? 'Free, Fair, and Transparent Elections' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email']->value ?? 'info@necss.org.ss' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Phone</label>
                                    <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone']->value ?? '+211 920 000 000' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Contact Address</label>
                                    <input type="text" name="contact_address" class="form-control" value="{{ $settings['contact_address']->value ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Office Hours</label>
                                    <input type="text" name="office_hours" class="form-control" value="{{ $settings['office_hours']->value ?? 'Mon – Fri: 8:00 AM – 5:00 PM' }}">
                                </div>
                            </div>
                        </div>

                        {{-- TAB: SMS GATEWAY --}}
                        <div class="tab-pane fade {{ $activeTab === 'sms' ? 'show active' : '' }}" id="tab-sms" role="tabpanel">
                            <h5 class="fw-bold mb-1">SMS Gateway Configuration</h5>
                            <p class="text-muted small mb-3">Configure the SMS provider for sending OTP codes and notifications to voters and users.</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">SMS Provider</label>
                                    <select name="sms_provider" class="form-select">
                                        @php $provider = $settings['sms_provider']->value ?? ''; @endphp
                                        <option value="">-- Select Provider --</option>
                                        <option value="twilio" @selected($provider === 'twilio')>Twilio</option>
                                        <option value="africas_talking" @selected($provider === 'africas_talking')>Africa's Talking</option>
                                        <option value="vonage" @selected($provider === 'vonage')>Vonage (Nexmo)</option>
                                        <option value="messagebird" @selected($provider === 'messagebird')>MessageBird</option>
                                        <option value="bulksms" @selected($provider === 'bulksms')>BulkSMS</option>
                                        <option value="custom" @selected($provider === 'custom')>Custom HTTP API</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="sms_enabled" role="switch" id="smsEnabled" value="1" {{ ($settings['sms_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="smsEnabled">Enable SMS Sending</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">API Key / Username</label>
                                    <input type="text" name="sms_api_key" class="form-control" value="{{ $settings['sms_api_key']->value ?? '' }}" placeholder="Your SMS provider API key">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">API Secret / Password</label>
                                    <input type="password" name="sms_api_secret" class="form-control" value="{{ $settings['sms_api_secret']->value ?? '' }}" placeholder="API secret or password">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">From Number</label>
                                    <input type="text" name="sms_from_number" class="form-control" value="{{ $settings['sms_from_number']->value ?? '' }}" placeholder="e.g. +211921234567">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Sender ID</label>
                                    <input type="text" name="sms_sender_id" class="form-control" value="{{ $settings['sms_sender_id']->value ?? 'NEC-SS' }}" placeholder="e.g. NEC-SS" maxlength="20">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Balance Check URL</label>
                                    <input type="url" name="sms_balance_url" class="form-control" value="{{ $settings['sms_balance_url']->value ?? '' }}" placeholder="Optional balance API endpoint">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">OTP SMS Template</label>
                                    <textarea name="sms_otp_template" class="form-control" rows="2" placeholder="Use {code} as placeholder">{{ $settings['sms_otp_template']->value ?? 'Your NEC verification code is: {code}. Valid for 10 minutes.' }}</textarea>
                                    <div class="form-text">Use <code>{code}</code> for the OTP code, <code>{name}</code> for user's name.</div>
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="fw-bold"><i class="fas fa-info-circle me-1"></i> OTP Integration</h6>
                                <p class="small text-muted mb-0">The SMS gateway is used by the OTP login system. When sending OTP codes, the system will use the configured provider with the template above. Demo OTP <code>000000</code> bypasses the SMS gateway.</p>
                            </div>
                        </div>

                        {{-- TAB: EMAIL --}}
                        <div class="tab-pane fade {{ $activeTab === 'email' ? 'show active' : '' }}" id="tab-email" role="tabpanel">
                            <h5 class="fw-bold mb-3">Email Configuration</h5>
                            <p class="text-muted small mb-3">Configure SMTP settings for sending emails (OTP codes, notifications, newsletters).</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Mail Driver</label>
                                    <select name="mail_driver" class="form-select">
                                        @php $driver = $settings['mail_driver']->value ?? 'smtp'; @endphp
                                        <option value="smtp" @selected($driver === 'smtp')>SMTP</option>
                                        <option value="sendmail" @selected($driver === 'sendmail')>Sendmail</option>
                                        <option value="mailgun" @selected($driver === 'mailgun')>Mailgun</option>
                                        <option value="ses" @selected($driver === 'ses')>Amazon SES</option>
                                        <option value="log" @selected($driver === 'log')>Log (for testing)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">SMTP Host</label>
                                    <input type="text" name="mail_host" class="form-control" value="{{ $settings['mail_host']->value ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">SMTP Port</label>
                                    <input type="text" name="mail_port" class="form-control" value="{{ $settings['mail_port']->value ?? '587' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Username</label>
                                    <input type="text" name="mail_username" class="form-control" value="{{ $settings['mail_username']->value ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input type="password" name="mail_password" class="form-control" value="{{ $settings['mail_password']->value ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Encryption</label>
                                    <select name="mail_encryption" class="form-select">
                                        @php $enc = $settings['mail_encryption']->value ?? 'tls'; @endphp
                                        <option value="tls" @selected($enc === 'tls')>TLS</option>
                                        <option value="ssl" @selected($enc === 'ssl')>SSL</option>
                                        <option value="" @selected($enc === '')>None</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Address</label>
                                    <input type="email" name="mail_from_address" class="form-control" value="{{ $settings['mail_from_address']->value ?? 'noreply@necss.org.ss' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Name</label>
                                    <input type="text" name="mail_from_name" class="form-control" value="{{ $settings['mail_from_name']->value ?? 'NEC South Sudan' }}">
                                </div>
                            </div>
                        </div>

                        {{-- TAB: SOCIAL MEDIA --}}
                        <div class="tab-pane fade {{ $activeTab === 'social' ? 'show active' : '' }}" id="tab-social" role="tabpanel">
                            <h5 class="fw-bold mb-3">Social Media Links</h5>
                            <div class="row g-3">
                                @php
                                    $socials = ['facebook' => 'Facebook', 'twitter' => 'Twitter / X', 'youtube' => 'YouTube', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'];
                                    $icons = ['facebook' => 'fa-facebook', 'twitter' => 'fa-x-twitter', 'youtube' => 'fa-youtube', 'instagram' => 'fa-instagram', 'linkedin' => 'fa-linkedin'];
                                @endphp
                                @foreach($socials as $key => $label)
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="fab {{ $icons[$key] }} me-1"></i> {{ $label }} URL</label>
                                    <input type="url" name="{{ $key }}_url" class="form-control" value="{{ $settings[$key.'_url']->value ?? '' }}" placeholder="https://{{ $key }}.com/...">
                                </div>
                                @endforeach
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="fab fa-whatsapp me-1"></i> WhatsApp Number</label>
                                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number']->value ?? '' }}" placeholder="+211912345678">
                                </div>
                            </div>
                        </div>

                        {{-- TAB: ELECTIONS --}}
                        <div class="tab-pane fade {{ $activeTab === 'elections' ? 'show active' : '' }}" id="tab-elections" role="tabpanel">
                            <h5 class="fw-bold mb-3">Election Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Next Election Date</label>
                                    <input type="date" name="election_date" class="form-control" value="{{ $settings['election_date']->value ?? '2026-12-22' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Election Year</label>
                                    <input type="text" name="election_year" class="form-control" value="{{ $settings['election_year']->value ?? '2026' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Election Type</label>
                                    <input type="text" name="election_type" class="form-control" value="{{ $settings['election_type']->value ?? 'General Elections' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Voter Registration Deadline</label>
                                    <input type="date" name="voter_registration_deadline" class="form-control" value="{{ $settings['voter_registration_deadline']->value ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomination Deadline</label>
                                    <input type="date" name="nomination_deadline" class="form-control" value="{{ $settings['nomination_deadline']->value ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Campaign Start</label>
                                    <input type="date" name="campaign_start" class="form-control" value="{{ $settings['campaign_start']->value ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Campaign End</label>
                                    <input type="date" name="campaign_end" class="form-control" value="{{ $settings['campaign_end']->value ?? '' }}">
                                </div>
                            </div>
                        </div>

                        {{-- TAB: SECURITY --}}
                        <div class="tab-pane fade {{ $activeTab === 'security' ? 'show active' : '' }}" id="tab-security" role="tabpanel">
                            <h5 class="fw-bold mb-3">Security Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">OTP Expiry (minutes)</label>
                                    <input type="number" name="otp_expiry_minutes" class="form-control" value="{{ $settings['otp_expiry_minutes']->value ?? '10' }}" min="1" max="60">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Max Login Attempts</label>
                                    <input type="number" name="max_login_attempts" class="form-control" value="{{ $settings['max_login_attempts']->value ?? '5' }}" min="1" max="50">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Brute Force Window (min)</label>
                                    <input type="number" name="brute_force_window" class="form-control" value="{{ $settings['brute_force_window']->value ?? '15' }}" min="1" max="120">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Session Lifetime (min)</label>
                                    <input type="number" name="session_lifetime" class="form-control" value="{{ $settings['session_lifetime']->value ?? '120' }}" min="5" max="1440">
                                </div>
                                <div class="col-md-8 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="recaptcha_enabled" role="switch" id="recaptchaEnabled" value="1" {{ ($settings['recaptcha_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="recaptchaEnabled">Enable reCAPTCHA on login forms</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">reCAPTCHA Site Key</label>
                                    <input type="text" name="recaptcha_site_key" class="form-control" value="{{ $settings['recaptcha_site_key']->value ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">reCAPTCHA Secret Key</label>
                                    <input type="password" name="recaptcha_secret_key" class="form-control" value="{{ $settings['recaptcha_secret_key']->value ?? '' }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="hidden" name="_tab" value="{{ $activeTab }}">
                        <button type="submit" class="btn btn-nec-green btn-lg px-5"><i class="fas fa-save me-1"></i> Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-1"></i> Quick Stats</h5></div>
            <div class="card-body">
                @php
                    $stats = [
                        'Total Voters' => number_format($settings['stat_total_voters']->value ?? \App\Models\User::count()),
                        'Political Parties' => $stats['parties'] ?? \App\Models\Party::count(),
                        'Candidates' => $stats['candidates'] ?? \App\Models\Candidate::count(),
                        'Observers' => $stats['observers'] ?? \App\Models\Observer::count(),
                        'News Articles' => \App\Models\News::count(),
                        'Contact Messages' => \App\Models\Contact::count(),
                    ];
                @endphp
                @foreach($stats as $label => $value)
                <div class="d-flex justify-content-between py-1 small border-bottom"><span class="text-muted">{{ $label }}</span><strong>{{ $value }}</strong></div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-1"></i> System Info</h5></div>
            <div class="card-body small">
                <div class="d-flex justify-content-between py-1"><span class="text-muted">PHP</span><strong>{{ phpversion() }}</strong></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Laravel</span><strong>{{ app()->version() }}</strong></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Server</span><strong>{{ php_uname('s') }}</strong></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Debug</span><strong>{{ config('app.debug') ? 'ON' : 'OFF' }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    // Preserve tab state across page loads
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var tabId = $(e.target).data('bs-target').replace('#tab-', '');
        $('input[name="_tab"]').val(tabId);
        history.replaceState(null, null, '?tab=' + tabId);
    });

    // SMS provider toggle
    var smsFields = $('#tab-sms input, #tab-sms select').not('#smsEnabled, #smsEnabled + .form-check-label');
    function toggleSmsFields() {
        var enabled = $('#smsEnabled').is(':checked');
        smsFields.prop('disabled', !enabled);
    }
    toggleSmsFields();
    $('#smsEnabled').on('change', toggleSmsFields);
});
</script>
@endsection
