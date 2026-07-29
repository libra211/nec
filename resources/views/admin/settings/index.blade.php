@extends('admin.layouts.app', ['title' => 'Settings'])

@php
    $activeTab = session('active_tab', request('tab', 'general'));
    $tabs = [
        'general' => ['icon' => 'fa-cog', 'label' => 'General'],
        'sms' => ['icon' => 'fa-sms', 'label' => 'SMS Gateway'],
        'email' => ['icon' => 'fa-envelope', 'label' => 'Email'],
        'social' => ['icon' => 'fa-share-alt', 'label' => 'Social Media'],
        'elections' => ['icon' => 'fa-vote-yea', 'label' => 'Elections'],
        'profile' => ['icon' => 'fa-user-circle', 'label' => 'My Profile'],
        'login-logs' => ['icon' => 'fa-history', 'label' => 'Login Logs'],
        'public-display' => ['icon' => 'fa-globe', 'label' => 'Public Display'],
        'security' => ['icon' => 'fa-shield-alt', 'label' => 'Security'],
    ];
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold"><i class="fas fa-cogs me-2 text-primary"></i>Site Settings</h2>
        <p class="text-muted small mb-0 mt-1">Configure system-wide preferences and features</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-0 border-bottom-0">
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
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Site Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <div class="form-text">Recommended: PNG or SVG, max 2MB</div>
                                    @if($settings['logo']->value ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $settings['logo']->value }}" alt="Logo" height="40" class="border rounded p-1">
                                        <small class="text-muted ms-2">Current logo</small>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Favicon</label>
                                    <input type="file" name="favicon" class="form-control" accept=".ico,.png,.jpg,.jpeg,.svg,.webp">
                                    <div class="form-text">ICO or PNG, max 1MB</div>
                                    @if($settings['favicon']->value ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $settings['favicon']->value }}" alt="Favicon" height="24" class="border rounded p-1">
                                        <small class="text-muted ms-2">Current favicon</small>
                                    </div>
                                    @endif
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

                        {{-- TAB: MY PROFILE --}}
                        <div class="tab-pane fade {{ $activeTab === 'profile' ? 'show active' : '' }}" id="tab-profile" role="tabpanel">
                            <h5 class="fw-bold mb-3"><i class="fas fa-user-circle me-2"></i>My Profile</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? session('admin_user_name')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? session('admin_email')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                                </div>
                                <div class="col-12"><hr><h6 class="fw-bold text-muted">Change Password</h6></div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Leave blank to keep current">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Min 8 characters">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repeat new password">
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info small mb-0">
                                        <i class="fas fa-info-circle me-1"></i> Leave password fields blank to keep your current password.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB: LOGIN LOGS --}}
                        <div class="tab-pane fade {{ $activeTab === 'login-logs' ? 'show active' : '' }}" id="tab-login-logs" role="tabpanel">
                            <h5 class="fw-bold mb-3"><i class="fas fa-history me-2"></i>Login Activity</h5>
                            <p class="text-muted small mb-3">Recent login attempts across the system.</p>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Identifier</th>
                                            <th>Name</th>
                                            <th>IP Address</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>User Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allLoginLogs as $log)
                                        <tr>
                                            <td class="text-nowrap">{{ $log->logged_at->format('M d, Y H:i') }}</td>
                                            <td><code>{{ $log->identifier }}</code></td>
                                            <td>{{ $log->name ?? '—' }}</td>
                                            <td><code>{{ $log->ip_address ?? '—' }}</code></td>
                                            <td class="small">{{ $log->location ?? '—' }}</td>
                                            <td>
                                                @if($log->success)
                                                    <span class="badge bg-success-subtle text-success">Success</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 50) ?? '—' }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="7" class="text-center text-muted py-4">No login logs recorded yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($allLoginLogs->hasPages())
                            <div class="mt-3">{{ $allLoginLogs->links() }}</div>
                            @endif
                        </div>

                        {{-- TAB: PUBLIC DISPLAY --}}
                        <div class="tab-pane fade {{ $activeTab === 'public-display' ? 'show active' : '' }}" id="tab-public-display" role="tabpanel">
                            <h5 class="fw-bold mb-3"><i class="fas fa-globe me-2"></i>Public Page Stats Visibility</h5>
                            <p class="text-muted small mb-3">Control which statistics and information are visible on the public-facing pages.</p>
                            @php
                                $publicToggles = [
                                    'public_show_election_date' => ['label' => 'Election Date', 'desc' => 'Show the next election date on homepage'],
                                    'public_show_registration_deadline' => ['label' => 'Registration Deadline', 'desc' => 'Show voter registration deadline'],
                                    'public_show_registration_stats' => ['label' => 'Registration Stats', 'desc' => 'Show total registered voters count'],
                                    'public_show_voter_count' => ['label' => 'Voter Count', 'desc' => 'Show voter demographic breakdown'],
                                    'public_show_party_count' => ['label' => 'Political Parties', 'desc' => 'Show number of registered political parties'],
                                    'public_show_candidate_count' => ['label' => 'Candidates', 'desc' => 'Show number of candidates'],
                                    'public_show_constituency_count' => ['label' => 'Constituencies', 'desc' => 'Show number of constituencies'],
                                    'public_show_polling_station_count' => ['label' => 'Polling Stations', 'desc' => 'Show number of polling stations'],
                                    'public_show_observer_count' => ['label' => 'Observers', 'desc' => 'Show number of election observers'],
                                    'public_show_agent_count' => ['label' => 'Agents', 'desc' => 'Show number of registration agents'],
                                    'public_show_commissioner_count' => ['label' => 'Commissioners', 'desc' => 'Show number of commissioners'],
                                    'public_show_staff_count' => ['label' => 'Staff', 'desc' => 'Show number of election staff'],
                                    'public_show_news_count' => ['label' => 'News Articles', 'desc' => 'Show total published news count'],
                                    'public_show_event_count' => ['label' => 'Events', 'desc' => 'Show number of upcoming events'],
                                    'public_show_gallery_count' => ['label' => 'Gallery', 'desc' => 'Show number of gallery items'],
                                    'public_show_download_count' => ['label' => 'Downloads', 'desc' => 'Show number of downloadable resources'],
                                ];
                            @endphp
                            <div class="row g-3">
                                @foreach($publicToggles as $key => $info)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <div class="form-check form-switch me-3">
                                            <input class="form-check-input" type="checkbox" name="{{ $key }}" role="switch" id="toggle-{{ $key }}" value="1" {{ ($settings[$key]->value ?? '1') === '1' ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <label class="fw-semibold mb-0" for="toggle-{{ $key }}">{{ $info['label'] }}</label>
                                            <div class="small text-muted">{{ $info['desc'] }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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

                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="hidden" name="_tab" value="{{ $activeTab }}">
                        <div>
                            <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Save Settings</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ms-2"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quick Stats & System Info at bottom --}}
        <div class="row g-3 mt-2">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body pt-0">
                        @php
                            $statCards = [
                                'Total Voters' => ['value' => number_format($settings['stat_total_voters']->value ?? \App\Models\User::count()), 'icon' => 'fa-users', 'color' => 'primary'],
                                'Political Parties' => ['value' => $stats['parties'] ?? \App\Models\PoliticalParty::count(), 'icon' => 'fa-flag', 'color' => 'success'],
                                'Candidates' => ['value' => $stats['candidates'] ?? \App\Models\Candidate::count(), 'icon' => 'fa-user-tie', 'color' => 'warning'],
                                'Observers' => ['value' => $stats['observers'] ?? \App\Models\Observer::count(), 'icon' => 'fa-eye', 'color' => 'info'],
                                'News Articles' => ['value' => \App\Models\News::count(), 'icon' => 'fa-newspaper', 'color' => 'danger'],
                                'Contact Messages' => ['value' => \App\Models\Contact::count(), 'icon' => 'fa-envelope', 'color' => 'secondary'],
                            ];
                        @endphp
                        <div class="row g-2">
                            @foreach($statCards as $label => $info)
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-center p-3 rounded-3 border bg-light bg-opacity-25">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(var(--bs-{{ $info['color'] }}-rgb),0.1);flex-shrink:0;">
                                        <i class="fas {{ $info['icon'] }} text-{{ $info['color'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-5">{{ $info['value'] }}</div>
                                        <div class="small text-muted">{{ $label }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-server text-info me-2"></i>System Information</h5>
                    </div>
                    <div class="card-body pt-0">
                        @php
                            $sysInfo = [
                                ['label' => 'PHP Version', 'value' => phpversion(), 'icon' => 'fa-brands fa-php', 'color' => '#777bb3'],
                                ['label' => 'Laravel Version', 'value' => app()->version(), 'icon' => 'fas fa-code-branch', 'color' => '#ff2d20'],
                                ['label' => 'Server OS', 'value' => php_uname('s'), 'icon' => 'fas fa-desktop', 'color' => 'var(--text-muted)'],
                                ['label' => 'Environment', 'value' => config('app.debug') ? 'Debug ON' : 'Production', 'icon' => 'fas fa-shield-alt', 'color' => config('app.debug') ? '#f59e0b' : '#10b981'],
                                ['label' => 'Database', 'value' => 'MariaDB', 'icon' => 'fas fa-database', 'color' => '#00758f'],
                                ['label' => 'Cache Driver', 'value' => config('cache.default') ?? 'file', 'icon' => 'fas fa-bolt', 'color' => '#6366f1'],
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    @foreach($sysInfo as $info)
                                    <tr>
                                        <td class="ps-0 py-2" style="width:40px;">
                                            <i class="{{ $info['icon'] }}" style="color:{{ $info['color'] }};width:18px;text-align:center;"></i>
                                        </td>
                                        <td class="text-muted small py-2">{{ $info['label'] }}</td>
                                        <td class="text-end fw-semibold small py-2">{{ $info['value'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
