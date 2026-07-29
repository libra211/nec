@extends('admin.layouts.app', ['title' => 'Settings'])

@php
    $activeTab = session('active_tab', request('tab', 'general'));
    $tabs = [
        'general' => ['icon' => 'fa-cog', 'label' => 'General', 'desc' => 'Site identity, logo & contact'],
        'public-content' => ['icon' => 'fa-file-alt', 'label' => 'Public Content', 'desc' => 'Homepage, about, footer text'],
        'seo' => ['icon' => 'fa-chart-line', 'label' => 'SEO & Analytics', 'desc' => 'Meta tags, tracking codes'],
        'notifications' => ['icon' => 'fa-bell', 'label' => 'Notifications', 'desc' => 'Email alert preferences'],
        'sms' => ['icon' => 'fa-sms', 'label' => 'SMS', 'desc' => 'OTP & notification provider'],
        'email' => ['icon' => 'fa-envelope', 'label' => 'Email', 'desc' => 'SMTP & mail settings'],
        'social' => ['icon' => 'fa-share-alt', 'label' => 'Social Media', 'desc' => 'Social links & sharing'],
        'elections' => ['icon' => 'fa-vote-yea', 'label' => 'Elections', 'desc' => 'Dates, types & deadlines'],
        'public-display' => ['icon' => 'fa-globe', 'label' => 'Public Display', 'desc' => 'Stats visibility & features'],
        'profile' => ['icon' => 'fa-user-circle', 'label' => 'Profile', 'desc' => 'Name, email & password'],
        'login-logs' => ['icon' => 'fa-history', 'label' => 'Login Logs', 'desc' => 'Recent login activity'],
        'security' => ['icon' => 'fa-shield-alt', 'label' => 'Security', 'desc' => 'OTP, captcha, access control'],
        'system-tools' => ['icon' => 'fa-wrench', 'label' => 'System Tools', 'desc' => 'Cache, backup, maintenance'],
    ];
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-0 fw-bold" style="font-size:20px;"><i class="fas fa-cogs me-2 text-primary"></i>Settings &amp; Configuration</h2>
        <p class="text-muted small mb-0 mt-1">Manage all system-wide preferences, features, and tools</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3">
    <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3">
    <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="settings-panel">
    {{-- Horizontal Tab Navigation --}}
    <div class="settings-tab-bar">
        <div class="settings-tab-scroll">
            @foreach($tabs as $key => $tab)
            <button class="settings-tab {{ $activeTab === $key ? 'active' : '' }}" data-tab="{{ $key }}">
                <i class="fas {{ $tab['icon'] }}"></i>
                <span>{{ $tab['label'] }}</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="settings-content" id="settingsContent">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
            @csrf
            @method('PUT')

            @foreach($tabs as $key => $tab)
            <div class="settings-tab-pane" id="pane-{{ $key }}" style="display:{{ $activeTab === $key ? 'block' : 'none' }};">
                <div class="settings-content-header">
                    <h3><i class="fas {{ $tab['icon'] }} me-2 text-primary"></i>{{ $tab['label'] }}</h3>
                    <p>{{ $tab['desc'] }}</p>
                </div>

                {{-- ===== GENERAL ===== --}}
                @if($key === 'general')
                <div class="settings-block">
                    <div class="settings-block-title">Site Identity</div>
                    <div class="settings-block-desc">Your public site name, tagline, and branding assets.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Site Name</label>
                                <input type="text" name="site_name" class="form-control setting-input" value="{{ $settings['site_name']->value ?? 'National Elections Commission' }}">
                                <div class="settings-field-hint">Appears in the browser title bar and header</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Site Tagline</label>
                                <input type="text" name="site_tagline" class="form-control setting-input" value="{{ $settings['site_tagline']->value ?? 'Free, Fair, and Transparent Elections' }}">
                                <div class="settings-field-hint">Brief description under the site name</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Branding Assets</div>
                    <div class="settings-block-desc">Upload your site logo and favicon.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Site Logo</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="settings-field-hint">PNG, SVG or JPG, max 2MB. Recommended: 200&times;60px</div>
                                @if($settings['logo']->value ?? false)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ $settings['logo']->value }}" alt="Logo" height="36" class="border rounded p-1">
                                    <small class="text-muted">Current logo</small>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control" accept=".ico,.png,.jpg,.jpeg,.svg,.webp">
                                <div class="settings-field-hint">ICO or PNG, max 1MB. Shown in browser tab</div>
                                @if($settings['favicon']->value ?? false)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ $settings['favicon']->value }}" alt="Favicon" height="24" class="border rounded p-1">
                                    <small class="text-muted">Current favicon</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Contact Information</div>
                    <div class="settings-block-desc">Displayed on the public contact page and footer.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control setting-input" value="{{ $settings['contact_email']->value ?? 'info@necss.org.ss' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control setting-input" value="{{ $settings['contact_phone']->value ?? '+211 920 000 000' }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Contact Address</label>
                                <input type="text" name="contact_address" class="form-control setting-input" value="{{ $settings['contact_address']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Office Hours</label>
                                <input type="text" name="office_hours" class="form-control setting-input" value="{{ $settings['office_hours']->value ?? 'Mon – Fri: 8:00 AM – 5:00 PM' }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== PUBLIC CONTENT ===== --}}
                @if($key === 'public-content')
                <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:12px;border:none;background:rgba(59,130,246,0.06);">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span>These values appear on the public-facing pages. Leave blank to use defaults from your Blade templates.</span>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Hero Section</div>
                    <div class="settings-block-desc">The main banner on the public homepage.</div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="settings-field">
                                <label class="settings-field-label">Hero Heading</label>
                                <input type="text" name="hero_heading" class="form-control setting-input" value="{{ $settings['hero_heading']->value ?? 'Welcome to the National Elections Commission' }}" maxlength="200" placeholder="Main headline">
                                <div class="settings-field-hint">Large text shown on the homepage hero banner</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Hero CTA Text</label>
                                <input type="text" name="hero_cta_text" class="form-control setting-input" value="{{ $settings['hero_cta_text']->value ?? 'Learn More' }}" maxlength="50">
                                <div class="settings-field-hint">Call-to-action button label</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Hero Subheading</label>
                                <textarea name="hero_subheading" class="form-control setting-input" rows="2" maxlength="500" placeholder="Supporting text below the heading">{{ $settings['hero_subheading']->value ?? 'Ensuring free, fair, and transparent elections in South Sudan.' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">CTA Button URL</label>
                                <input type="text" name="hero_cta_url" class="form-control setting-input" value="{{ $settings['hero_cta_url']->value ?? '/about' }}" placeholder="/about">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">About / Mission Section</div>
                    <div class="settings-block-desc">Information about the commission.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Section Title</label>
                                <input type="text" name="about_title" class="form-control setting-input" value="{{ $settings['about_title']->value ?? 'About the Commission' }}" maxlength="200">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Section Subtitle</label>
                                <input type="text" name="about_subtitle" class="form-control setting-input" value="{{ $settings['about_subtitle']->value ?? 'Our commitment to democracy' }}" maxlength="200">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">About Content</label>
                                <textarea name="about_content" class="form-control setting-input" rows="4" maxlength="2000" placeholder="Describe the commission's role and mission...">{{ $settings['about_content']->value ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Mission Statement</label>
                                <textarea name="mission_text" class="form-control setting-input" rows="3" maxlength="1000" placeholder="Our mission...">{{ $settings['mission_text']->value ?? 'To conduct free, fair, and transparent elections in South Sudan.' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Vision Statement</label>
                                <textarea name="vision_text" class="form-control setting-input" rows="3" maxlength="1000" placeholder="Our vision...">{{ $settings['vision_text']->value ?? 'A democratic South Sudan where every vote counts.' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Footer Content</div>
                    <div class="settings-block-desc">Text shown in the public site footer.</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Footer About Text</label>
                                <textarea name="footer_about" class="form-control setting-input" rows="2" maxlength="500" placeholder="Brief description in the footer...">{{ $settings['footer_about']->value ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Copyright Text</label>
                                <input type="text" name="footer_copyright" class="form-control setting-input" value="{{ $settings['footer_copyright']->value ?? '&copy; ' . date('Y') . ' National Elections Commission. All rights reserved.' }}" maxlength="200">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== SEO & ANALYTICS ===== --}}
                @if($key === 'seo')
                <div class="settings-block">
                    <div class="settings-block-title">Meta Tags</div>
                    <div class="settings-block-desc">Default meta tags for search engines and social sharing.</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control setting-input" rows="2" maxlength="500" placeholder="Brief description for search engines...">{{ $settings['meta_description']->value ?? '' }}</textarea>
                                <div class="settings-field-hint">Appears in search engine results, 150-160 characters recommended</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control setting-input" value="{{ $settings['meta_keywords']->value ?? '' }}" placeholder="election, south sudan, nec, voting">
                                <div class="settings-field-hint">Comma-separated keywords (less important for modern SEO)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Tracking &amp; Analytics</div>
                    <div class="settings-block-desc">Insert tracking codes from analytics providers.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Google Analytics ID</label>
                                <input type="text" name="google_analytics_id" class="form-control setting-input" value="{{ $settings['google_analytics_id']->value ?? '' }}" placeholder="G-XXXXXXXXXX">
                                <div class="settings-field-hint">Google Analytics 4 measurement ID</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Google Tag Manager ID</label>
                                <input type="text" name="google_tag_manager_id" class="form-control setting-input" value="{{ $settings['google_tag_manager_id']->value ?? '' }}" placeholder="GTM-XXXXXXX">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Facebook Pixel ID</label>
                                <input type="text" name="facebook_pixel_id" class="form-control setting-input" value="{{ $settings['facebook_pixel_id']->value ?? '' }}" placeholder="1234567890">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== NOTIFICATIONS ===== --}}
                @if($key === 'notifications')
                <div class="settings-block">
                    <div class="settings-block-title">Email Notification Preferences</div>
                    <div class="settings-block-desc">Choose which events send email alerts.</div>
                    <div class="d-flex flex-column gap-2">
                        @php
                            $notifFields = [
                                'notify_new_contact' => ['label' => 'New Contact Message', 'desc' => 'When someone submits the contact form', 'icon' => 'fa-envelope'],
                                'notify_new_voter_registration' => ['label' => 'New Voter Registration', 'desc' => 'When a voter registers online', 'icon' => 'fa-user-plus'],
                                'notify_new_observer_application' => ['label' => 'New Observer Application', 'desc' => 'When an observer applies for accreditation', 'icon' => 'fa-eye'],
                                'notify_new_complaint' => ['label' => 'New Complaint', 'desc' => 'When a complaint is submitted', 'icon' => 'fa-exclamation-triangle'],
                            ];
                        @endphp
                        @foreach($notifFields as $fkey => $finfo)
                        <div class="d-flex align-items-center gap-3 py-2 px-3 rounded-2" style="background:var(--body-bg);">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input setting-input" type="checkbox" name="{{ $fkey }}" role="switch" id="{{ $fkey }}" value="1" {{ ($settings[$fkey]->value ?? '1') === '1' ? 'checked' : '' }}>
                            </div>
                            <i class="fas {{ $finfo['icon'] }} text-muted"></i>
                            <div class="flex-fill">
                                <label class="fw-semibold small mb-0" for="{{ $fkey }}">{{ $finfo['label'] }}</label>
                                <div class="text-muted" style="font-size:11px">{{ $finfo['desc'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Notification Recipient</div>
                    <div class="settings-block-desc">All email notifications will be sent to this address.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Notification Email</label>
                                <input type="email" name="notify_email" class="form-control setting-input" value="{{ $settings['notify_email']->value ?? '' }}" placeholder="admin@nec.gov.ss">
                                <div class="settings-field-hint">Leave blank to use the contact email</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== SMS GATEWAY ===== --}}
                @if($key === 'sms')
                <div class="settings-block">
                    <div class="settings-block-title">Provider Configuration</div>
                    <div class="settings-block-desc">Connect your SMS provider for sending OTP codes and alerts.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">SMS Provider</label>
                                <select name="sms_provider" class="form-select setting-input">
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
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="sms_enabled" role="switch" id="smsEnabled" value="1" {{ ($settings['sms_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="smsEnabled">Enable SMS Sending</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">API Key / Username</label>
                                <input type="text" name="sms_api_key" class="form-control setting-input" value="{{ $settings['sms_api_key']->value ?? '' }}" placeholder="Provider API key">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">API Secret / Password</label>
                                <input type="password" name="sms_api_secret" class="form-control setting-input" value="{{ $settings['sms_api_secret']->value ?? '' }}" placeholder="API secret">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">From Number</label>
                                <input type="text" name="sms_from_number" class="form-control setting-input" value="{{ $settings['sms_from_number']->value ?? '' }}" placeholder="+211921234567">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Sender ID</label>
                                <input type="text" name="sms_sender_id" class="form-control setting-input" value="{{ $settings['sms_sender_id']->value ?? 'NEC-SS' }}" maxlength="20">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Balance Check URL</label>
                                <input type="url" name="sms_balance_url" class="form-control setting-input" value="{{ $settings['sms_balance_url']->value ?? '' }}" placeholder="Optional API endpoint">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">OTP SMS Template</label>
                                <textarea name="sms_otp_template" class="form-control setting-input" rows="2" placeholder="Use {code} as placeholder">{{ $settings['sms_otp_template']->value ?? 'Your NEC verification code is: {code}. Valid for 10 minutes.' }}</textarea>
                                <div class="settings-field-hint">Use <code>{code}</code> for OTP, <code>{name}</code> for user name</div>
                            </div>
                        </div>
                    </div>
                    <hr class="settings-block-divider">
                    <div class="settings-info-card">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        <strong>OTP Integration:</strong> The SMS gateway powers the OTP login system. Demo code <code>000000</code> bypasses SMS for testing.
                    </div>
                </div>
                @endif

                {{-- ===== EMAIL ===== --}}
                @if($key === 'email')
                <div class="settings-block">
                    <div class="settings-block-title">SMTP Settings</div>
                    <div class="settings-block-desc">Configure the email server for system emails, OTP codes, and notifications.</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Mail Driver</label>
                                <select name="mail_driver" class="form-select setting-input">
                                    @php $driver = $settings['mail_driver']->value ?? 'smtp'; @endphp
                                    <option value="smtp" @selected($driver === 'smtp')>SMTP</option>
                                    <option value="sendmail" @selected($driver === 'sendmail')>Sendmail</option>
                                    <option value="mailgun" @selected($driver === 'mailgun')>Mailgun</option>
                                    <option value="ses" @selected($driver === 'ses')>Amazon SES</option>
                                    <option value="log" @selected($driver === 'log')>Log (testing)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">SMTP Host</label>
                                <input type="text" name="mail_host" class="form-control setting-input" value="{{ $settings['mail_host']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">SMTP Port</label>
                                <input type="text" name="mail_port" class="form-control setting-input" value="{{ $settings['mail_port']->value ?? '587' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Username</label>
                                <input type="text" name="mail_username" class="form-control setting-input" value="{{ $settings['mail_username']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Password</label>
                                <input type="password" name="mail_password" class="form-control setting-input" value="{{ $settings['mail_password']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Encryption</label>
                                <select name="mail_encryption" class="form-select setting-input">
                                    @php $enc = $settings['mail_encryption']->value ?? 'tls'; @endphp
                                    <option value="tls" @selected($enc === 'tls')>TLS</option>
                                    <option value="ssl" @selected($enc === 'ssl')>SSL</option>
                                    <option value="" @selected($enc === '')>None</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">From Address</label>
                                <input type="email" name="mail_from_address" class="form-control setting-input" value="{{ $settings['mail_from_address']->value ?? 'noreply@necss.org.ss' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">From Name</label>
                                <input type="text" name="mail_from_name" class="form-control setting-input" value="{{ $settings['mail_from_name']->value ?? 'NEC South Sudan' }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== SOCIAL MEDIA ===== --}}
                @if($key === 'social')
                <div class="settings-block">
                    <div class="settings-block-title">Social Links</div>
                    <div class="settings-block-desc">Connect your social media profiles. Icons will appear on the public site.</div>
                    <div class="row g-3">
                        @php
                            $socItems = [
                                'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook', 'color' => '#1877f2'],
                                'twitter' => ['label' => 'Twitter / X', 'icon' => 'fa-x-twitter', 'color' => '#000'],
                                'youtube' => ['label' => 'YouTube', 'icon' => 'fa-youtube', 'color' => '#ff0000'],
                                'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram', 'color' => '#e4405f'],
                                'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fa-linkedin', 'color' => '#0a66c2'],
                            ];
                        @endphp
                        @foreach($socItems as $skey => $sinfo)
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">
                                    <i class="fab {{ $sinfo['icon'] }}" style="color:{{ $sinfo['color'] }}"></i>
                                    {{ $sinfo['label'] }} URL
                                </label>
                                <input type="url" name="{{ $skey }}_url" class="form-control setting-input" value="{{ $settings[$skey.'_url']->value ?? '' }}" placeholder="https://{{ $skey }}.com/...">
                            </div>
                        </div>
                        @endforeach
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label"><i class="fab fa-whatsapp text-success"></i> WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control setting-input" value="{{ $settings['whatsapp_number']->value ?? '' }}" placeholder="+211912345678">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== ELECTIONS ===== --}}
                @if($key === 'elections')
                <div class="settings-block">
                    <div class="settings-block-title">Timeline &amp; Dates</div>
                    <div class="settings-block-desc">Key election dates shown on the public site.</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Next Election Date</label>
                                <input type="date" name="election_date" class="form-control setting-input" value="{{ $settings['election_date']->value ?? '2026-12-22' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Election Year</label>
                                <input type="text" name="election_year" class="form-control setting-input" value="{{ $settings['election_year']->value ?? '2026' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Election Type</label>
                                <input type="text" name="election_type" class="form-control setting-input" value="{{ $settings['election_type']->value ?? 'General Elections' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Voter Registration Deadline</label>
                                <input type="date" name="voter_registration_deadline" class="form-control setting-input" value="{{ $settings['voter_registration_deadline']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Nomination Deadline</label>
                                <input type="date" name="nomination_deadline" class="form-control setting-input" value="{{ $settings['nomination_deadline']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Campaign Start</label>
                                <input type="date" name="campaign_start" class="form-control setting-input" value="{{ $settings['campaign_start']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Campaign End</label>
                                <input type="date" name="campaign_end" class="form-control setting-input" value="{{ $settings['campaign_end']->value ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== PUBLIC DISPLAY ===== --}}
                @if($key === 'public-display')
                <div class="settings-info-card success mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Control which stats appear on the public homepage. <strong>Auto</strong> fetches live data, <strong>Manual</strong> shows custom values. Feature toggles publish/unpublish entire sections.
                </div>

                @php
                    $autoV = function($stat) {
                        return match($stat) {
                            'election_date' => \App\Helpers\NecHelper::setting_get('election_date', '2026-12-22'),
                            'registration_deadline' => \App\Helpers\NecHelper::setting_get('voter_registration_deadline', ''),
                            'election_type' => \App\Helpers\NecHelper::setting_get('election_type', 'General Elections'),
                            'total_voters' => number_format(\App\Models\Voter::count()),
                            'constituencies' => \App\Models\Constituency::count(),
                            'polling_stations' => \App\Models\PollingStation::count(),
                            'counties' => \Illuminate\Support\Facades\DB::table('nec_counties')->count(),
                            'payams' => \Illuminate\Support\Facades\DB::table('nec_payams')->count(),
                            'states_with_data' => \App\Models\Voter::whereNotNull('state')->distinct()->count('state'),
                            'parties' => \App\Models\PoliticalParty::count(),
                            'candidates' => \App\Models\Candidate::count(),
                            'observers' => \App\Models\ObserverApplication::count(),
                            'ballot_types' => \App\Models\Ballot::count(),
                            'agents' => \App\Models\Agent::where('status', 'active')->count(),
                            'commissioners' => \App\Models\Commissioner::count(),
                            'polling_staff' => \App\Models\PollingStaff::count(),
                            'trained_staff' => \App\Models\PollingStaff::where('trained', true)->count(),
                            'news' => \App\Models\News::where('status', 'published')->count(),
                            'events' => \App\Models\ElectionEvent::where('start_date', '>=', now())->count(),
                            'gallery' => \App\Models\Gallery::count(),
                            'downloads' => \App\Models\Download::count(),
                            'speeches' => \App\Models\Speech::count(),
                            'subscribers' => \App\Models\Subscriber::count(),
                            default => '',
                        };
                    };

                    $statGroups = [
                        'Election Information' => ['icon' => 'fa-calendar-check', 'color' => 'primary', 'items' => [
                            'election_date' => ['label' => 'Election Date', 'type' => 'date'],
                            'registration_deadline' => ['label' => 'Registration Deadline', 'type' => 'date'],
                            'election_type' => ['label' => 'Election Type', 'type' => 'text'],
                        ]],
                        'Voter Statistics' => ['icon' => 'fa-users', 'color' => 'success', 'items' => [
                            'total_voters' => ['label' => 'Total Voters', 'type' => 'number'],
                            'gender_split' => ['label' => 'Gender Split', 'type' => 'toggle'],
                            'registration_type' => ['label' => 'Registration Type', 'type' => 'toggle'],
                            'age_distribution' => ['label' => 'Age Distribution', 'type' => 'toggle'],
                            'weekly_trend' => ['label' => 'Weekly Trend', 'type' => 'toggle'],
                        ]],
                        'Election Infrastructure' => ['icon' => 'fa-map-marked-alt', 'color' => 'info', 'items' => [
                            'constituencies' => ['label' => 'Constituencies', 'type' => 'number'],
                            'polling_stations' => ['label' => 'Polling Stations', 'type' => 'number'],
                            'counties' => ['label' => 'Counties', 'type' => 'number'],
                            'payams' => ['label' => 'Payams', 'type' => 'number'],
                            'states_with_data' => ['label' => 'States with Data', 'type' => 'number'],
                        ]],
                        'Political Landscape' => ['icon' => 'fa-flag', 'color' => 'warning', 'items' => [
                            'parties' => ['label' => 'Political Parties', 'type' => 'number'],
                            'candidates' => ['label' => 'Candidates', 'type' => 'number'],
                            'observers' => ['label' => 'Observers', 'type' => 'number'],
                            'ballot_types' => ['label' => 'Ballot Types', 'type' => 'number'],
                        ]],
                        'Personnel' => ['icon' => 'fa-user-hard-hat', 'color' => 'secondary', 'items' => [
                            'agents' => ['label' => 'Registration Agents', 'type' => 'number'],
                            'commissioners' => ['label' => 'Commissioners', 'type' => 'number'],
                            'polling_staff' => ['label' => 'Polling Staff', 'type' => 'number'],
                            'trained_staff' => ['label' => 'Trained Staff', 'type' => 'number'],
                        ]],
                        'Content & Engagement' => ['icon' => 'fa-newspaper', 'color' => 'danger', 'items' => [
                            'news' => ['label' => 'News Articles', 'type' => 'number'],
                            'events' => ['label' => 'Upcoming Events', 'type' => 'number'],
                            'gallery' => ['label' => 'Gallery Items', 'type' => 'number'],
                            'downloads' => ['label' => 'Downloads', 'type' => 'number'],
                            'speeches' => ['label' => 'Speeches', 'type' => 'number'],
                            'subscribers' => ['label' => 'Subscribers', 'type' => 'number'],
                        ]],
                    ];
                @endphp

                @foreach($statGroups as $gName => $g)
                @php
                    $visibleCount = collect($g['items'])->filter(fn($info, $stat) => ($settings["public_show_{$stat}"]->value ?? '1') === '1')->count();
                    $totalCount = count($g['items']);
                    $pct = $totalCount > 0 ? round(($visibleCount / $totalCount) * 100) : 0;
                @endphp
                <div class="settings-block">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="settings-block-title mb-0">
                            <i class="fas {{ $g['icon'] }} text-{{ $g['color'] }} me-1"></i>{{ $gName }}
                        </div>
                        <span class="badge bg-{{ $pct === 100 ? 'success' : ($pct >= 50 ? 'warning' : 'secondary') }} rounded-pill" style="font-size:10px">{{ $visibleCount }}/{{ $totalCount }} visible</span>
                    </div>
                    <div class="progress mb-3" style="height:3px;background:var(--border-color);">
                        <div class="progress-bar bg-{{ $pct === 100 ? 'success' : ($pct >= 50 ? 'warning' : 'secondary') }}" role="progressbar" style="width:{{ $pct }}%;border-radius:2px;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @foreach($g['items'] as $stat => $info)
                    @php
                        $showKey = "public_show_{$stat}";
                        $isVisible = ($settings[$showKey]->value ?? '1') === '1';
                        $sourceKey = "public_stat_{$stat}_source";
                        $source = $settings[$sourceKey]->value ?? 'auto';
                        $manualKey = "public_stat_{$stat}_value";
                        $manualVal = $settings[$manualKey]->value ?? '';
                        $autoVal = $autoV($stat);
                    @endphp
                    <div class="stat-row d-flex align-items-start gap-3 py-2">
                        <div class="d-flex align-items-center gap-2 flex-shrink-0" style="min-width:180px;">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input form-check-input-sm setting-input" type="checkbox" name="{{ $showKey }}" role="switch" id="{{ $showKey }}" value="1" {{ $isVisible ? 'checked' : '' }}>
                            </div>
                            <label class="small fw-semibold mb-0 text-nowrap" for="{{ $showKey }}">{{ $info['label'] }}</label>
                        </div>
                        @if(in_array($info['type'], ['number', 'text', 'date']))
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-light text-muted fw-normal px-2 py-1 small">Auto: <strong class="live-stat-value" data-stat="{{ $stat }}">{{ $autoVal }}</strong></span>
                            <div class="btn-group btn-group-sm">
                                <input type="radio" class="btn-check setting-input" name="{{ $sourceKey }}" id="{{ $sourceKey }}_auto" value="auto" autocomplete="off" {{ $source === 'auto' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="{{ $sourceKey }}_auto">Auto</label>
                                <input type="radio" class="btn-check setting-input" name="{{ $sourceKey }}" id="{{ $sourceKey }}_manual" value="manual" autocomplete="off" {{ $source === 'manual' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="{{ $sourceKey }}_manual">Manual</label>
                            </div>
                            <div class="manual-input-{{ $stat }}" style="{{ $source === 'manual' ? '' : 'display:none' }}">
                                @if($info['type'] === 'number')
                                <input type="number" name="{{ $manualKey }}" class="form-control form-control-sm setting-input" style="width:110px" value="{{ $manualVal }}">
                                @elseif($info['type'] === 'date')
                                <input type="date" name="{{ $manualKey }}" class="form-control form-control-sm setting-input" style="width:140px" value="{{ $manualVal }}">
                                @else
                                <input type="text" name="{{ $manualKey }}" class="form-control form-control-sm setting-input" style="width:140px" value="{{ $manualVal }}">
                                @endif
                            </div>
                        </div>
                        @else
                        <span class="badge bg-light text-muted fw-normal px-2 py-1 small">{{ $autoVal }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endforeach

                {{-- Feature Toggles --}}
                @php
                    $features = [
                        'public_feature_parties' => ['label' => 'Political Parties', 'icon' => 'fa-flag', 'color' => 'warning'],
                        'public_feature_candidates' => ['label' => 'Candidates', 'icon' => 'fa-user-tie', 'color' => 'danger'],
                        'public_feature_results' => ['label' => 'Election Results', 'icon' => 'fa-poll', 'color' => 'primary'],
                        'public_feature_voter_registration' => ['label' => 'Voter Registration', 'icon' => 'fa-vote-yea', 'color' => 'success'],
                        'public_feature_voter_inquiry' => ['label' => 'Voter Inquiry', 'icon' => 'fa-search', 'color' => 'info'],
                        'public_feature_voter_transfer' => ['label' => 'Voter Transfer', 'icon' => 'fa-exchange-alt', 'color' => 'secondary'],
                        'public_feature_observers' => ['label' => 'Observers', 'icon' => 'fa-eye', 'color' => 'info'],
                        'public_feature_news' => ['label' => 'News', 'icon' => 'fa-newspaper', 'color' => 'danger'],
                        'public_feature_events' => ['label' => 'Events', 'icon' => 'fa-calendar-alt', 'color' => 'warning'],
                        'public_feature_gallery' => ['label' => 'Gallery', 'icon' => 'fa-images', 'color' => 'success'],
                        'public_feature_videos' => ['label' => 'Videos', 'icon' => 'fa-video', 'color' => 'primary'],
                        'public_feature_speeches' => ['label' => 'Speeches', 'icon' => 'fa-comment-dots', 'color' => 'secondary'],
                        'public_feature_downloads' => ['label' => 'Downloads', 'icon' => 'fa-download', 'color' => 'info'],
                        'public_feature_education' => ['label' => 'Voter Education', 'icon' => 'fa-graduation-cap', 'color' => 'success'],
                    ];
                    $enabledFeatures = collect($features)->filter(fn($f, $fkey) => ($settings[$fkey]->value ?? '1') === '1')->count();
                    $totalFeatures = count($features);
                    $featPct = round(($enabledFeatures / $totalFeatures) * 100);
                @endphp
                <div class="settings-block">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="settings-block-title mb-0"><i class="fas fa-toggle-on text-dark me-1"></i>Feature Access &mdash; Publish / Unpublish</div>
                        <span class="badge bg-{{ $featPct === 100 ? 'success' : ($featPct >= 50 ? 'warning' : 'secondary') }} rounded-pill" style="font-size:10px">{{ $enabledFeatures }}/{{ $totalFeatures }} enabled</span>
                    </div>
                    <div class="settings-block-desc">Globally enable or disable entire public sections. Disabled sections return 404.</div>
                    <div class="progress mb-3" style="height:4px;background:var(--border-color);">
                        <div class="progress-bar bg-{{ $featPct === 100 ? 'success' : ($featPct >= 50 ? 'warning' : 'secondary') }}" role="progressbar" style="width:{{ $featPct }}%;border-radius:2px;" aria-valuenow="{{ $featPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row g-2">
                        @foreach($features as $fkey => $feat)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-2 rounded-2 feature-toggle-item">
                                <div class="form-check form-switch mb-0 me-2">
                                    <input class="form-check-input setting-input" type="checkbox" name="{{ $fkey }}" role="switch" id="{{ $fkey }}" value="1" {{ ($settings[$fkey]->value ?? '1') === '1' ? 'checked' : '' }}>
                                </div>
                                <i class="fas {{ $feat['icon'] }} text-{{ $feat['color'] }} me-1" style="font-size:13px"></i>
                                <label class="small fw-semibold mb-0" for="{{ $fkey }}">{{ $feat['label'] }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ===== PROFILE ===== --}}
                @if($key === 'profile')
                <div class="settings-block">
                    <div class="settings-block-title">Profile Picture</div>
                    <div class="settings-block-desc">Upload a photo to personalise your admin account.</div>
                    <div class="d-flex align-items-start gap-4 flex-wrap">
                        <div id="avatarPreviewContainer" style="position:relative;width:100px;height:100px;border-radius:20px;overflow:hidden;border:3px solid #e2e8f0;flex-shrink:0;background:#f1f5f9;">
                            <img id="avatarPreviewImg" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/default-avatar.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            <div id="avatarRemoveOverlay" style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.5);padding:2px;text-align:center;{{ $user->avatar ? '' : 'display:none;' }}">
                                <button type="button" id="removeAvatarBtn" style="background:none;border:none;color:#fff;font-size:0.6rem;cursor:pointer;"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div style="flex-grow:1;min-width:200px;">
                            <div class="drop-zone" id="avatarDropZone" onclick="document.getElementById('avatarInput').click()" style="border:2px dashed #d1d5db;border-radius:12px;padding:1.25rem;text-align:center;cursor:pointer;transition:all 0.25s;background:#fafbfc;">
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept=".jpg,.jpeg,.png,.webp" onchange="handleAvatar(this)">
                                <input type="hidden" name="remove_avatar" id="removeAvatarField" value="0">
                                <div id="avatarPlaceholder">
                                    <i class="fas fa-cloud-upload-alt" style="font-size:1.4rem;color:#94a3b8;"></i>
                                    <div class="small text-muted mt-1">Drag &amp; drop or click to change photo</div>
                                    <div class="small text-muted" style="font-size:0.65rem;">JPG, PNG, WebP (max 2MB)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Account Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? session('admin_user_name')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? session('admin_email')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Change Password</div>
                    <div class="settings-block-desc">Leave blank to keep your current password.</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Current password">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Min 8 characters">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repeat new password">
                            </div>
                        </div>
                    </div>
                    <hr class="settings-block-divider">
                    <div class="settings-info-card">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.
                    </div>
                </div>
                @endif

                {{-- ===== LOGIN LOGS ===== --}}
                @if($key === 'login-logs')
                <div class="settings-info-card mb-3">
                    <i class="fas fa-history text-info me-1"></i>
                    Showing the most recent login attempts across the system.
                    <strong>{{ $allLoginLogs->total() }}</strong> total records.
                </div>
                <div class="settings-block" style="padding:0;overflow:hidden;">
                    <div class="table-responsive">
                        <table class="table log-table mb-0">
                            <thead>
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
                                    <td class="text-nowrap small">{{ $log->logged_at->format('M d, Y H:i') }}</td>
                                    <td><code class="small">{{ $log->identifier }}</code></td>
                                    <td class="small">{{ $log->name ?? '—' }}</td>
                                    <td><code class="small">{{ $log->ip_address ?? '—' }}</code></td>
                                    <td class="small">{{ $log->location ?? '—' }}</td>
                                    <td>
                                        @if($log->success)
                                        <span class="badge bg-success-subtle text-success" style="font-size:10px;">Success</span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">Failed</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 40) ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No login logs recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($allLoginLogs->hasPages())
                    <div class="p-3 border-top">{{ $allLoginLogs->links() }}</div>
                    @endif
                </div>
                @endif

                {{-- ===== SECURITY ===== --}}
                @if($key === 'security')
                @php
                    $checks = [
                        'OTP configured' => ($settings['otp_expiry_minutes']->value ?? '') !== '',
                        'Failed logins after 4+ attempts' => ($settings['max_login_attempts']->value ?? 0) >= 4 && ($settings['max_login_attempts']->value ?? 5) <= 10,
                        'Session timeout set' => ($settings['session_lifetime']->value ?? '') !== '',
                    ];
                    $score = collect($checks)->filter(fn($v) => $v)->count();
                    $maxScore = count($checks);
                    $pct = round(($score / $maxScore) * 100);
                    $level = $pct >= 80 ? 'high' : ($pct >= 50 ? 'medium' : 'low');
                    $levelLabel = $pct >= 80 ? 'Good' : ($pct >= 50 ? 'Fair' : 'Needs Improvement');
                @endphp

                <div class="security-score {{ $level }}">
                    <div class="security-score-number">{{ $pct }}%</div>
                    <div class="security-score-info">
                        <div class="security-score-label">Security Posture: {{ $levelLabel }}</div>
                        <div class="security-score-desc">{{ $score }}/{{ $maxScore }} critical checks passing. Configure the settings below to improve your security score.</div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Authentication</div>
                    <div class="settings-block-desc">OTP and login security configuration.</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">OTP Expiry (minutes)</label>
                                <input type="number" name="otp_expiry_minutes" class="form-control setting-input" value="{{ $settings['otp_expiry_minutes']->value ?? '10' }}" min="1" max="60">
                                <div class="settings-field-hint">How long OTP codes remain valid</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Max Login Attempts</label>
                                <input type="number" name="max_login_attempts" class="form-control setting-input" value="{{ $settings['max_login_attempts']->value ?? '5' }}" min="1" max="50">
                                <div class="settings-field-hint">Before temporary lockout</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Brute Force Window (min)</label>
                                <input type="number" name="brute_force_window" class="form-control setting-input" value="{{ $settings['brute_force_window']->value ?? '15' }}" min="1" max="120">
                                <div class="settings-field-hint">Time window for counting attempts</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Session Lifetime (min)</label>
                                <input type="number" name="session_lifetime" class="form-control setting-input" value="{{ $settings['session_lifetime']->value ?? '120' }}" min="5" max="1440">
                                <div class="settings-field-hint">Auto-logout after inactivity</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-field">
                                <label class="settings-field-label">Password Min Length</label>
                                <input type="number" name="password_min_length" class="form-control setting-input" value="{{ $settings['password_min_length']->value ?? '8' }}" min="6" max="64">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input setting-input" type="checkbox" name="password_require_special" role="switch" id="pwdSpecial" value="1" {{ ($settings['password_require_special']->value ?? '1') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="pwdSpecial">Require Special Characters</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">reCAPTCHA</div>
                    <div class="settings-block-desc">Protect login forms from bots.</div>
                    <div class="row g-3">
                        <div class="col-md-8 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input setting-input" type="checkbox" name="recaptcha_enabled" role="switch" id="recaptchaEnabled" value="1" {{ ($settings['recaptcha_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="recaptchaEnabled">Enable reCAPTCHA</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Site Key</label>
                                <input type="text" name="recaptcha_site_key" class="form-control setting-input" value="{{ $settings['recaptcha_site_key']->value ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-field">
                                <label class="settings-field-label">Secret Key</label>
                                <input type="password" name="recaptcha_secret_key" class="form-control setting-input" value="{{ $settings['recaptcha_secret_key']->value ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">IP Access Control</div>
                    <div class="settings-block-desc">Restrict admin access to trusted IP addresses.</div>
                    <div class="row g-3">
                        <div class="col-md-8 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input setting-input" type="checkbox" name="ip_whitelist_enabled" role="switch" id="ipWhitelist" value="1" {{ ($settings['ip_whitelist_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="ipWhitelist">Enable IP Whitelist</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="settings-field">
                                <label class="settings-field-label">Allowed IP Addresses</label>
                                <textarea name="allowed_ips" class="form-control setting-input" rows="3" placeholder="One IP per line. Supports CIDR notation (e.g. 192.168.1.0/24)">{{ $settings['allowed_ips']->value ?? '' }}</textarea>
                                <div class="settings-field-hint">Leave empty to allow all IPs when whitelist is enabled</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== SYSTEM TOOLS ===== --}}
                @if($key === 'system-tools')
                <div class="settings-block">
                    <div class="settings-block-title"><i class="fas fa-broom text-warning me-1"></i>Cache Management</div>
                    <div class="settings-block-desc">Clear cached data to force the system to reload fresh content.</div>
                    <div class="row g-2">
                        @php
                            $cacheTools = [
                                ['key' => 'view', 'label' => 'View Cache', 'icon' => 'fa-file-code', 'color' => '#6366f1', 'desc' => 'Clears compiled Blade templates'],
                                ['key' => 'config', 'label' => 'Config Cache', 'icon' => 'fa-cog', 'color' => '#f59e0b', 'desc' => 'Clears cached configuration files'],
                                ['key' => 'route', 'label' => 'Route Cache', 'icon' => 'fa-route', 'color' => '#10b981', 'desc' => 'Clears cached route definitions'],
                                ['key' => 'app', 'label' => 'Application Cache', 'icon' => 'fa-sync', 'color' => '#ef4444', 'desc' => 'Clears all application cache'],
                            ];
                        @endphp
                        @foreach($cacheTools as $tool)
                        <div class="col-md-6">
                            <div class="tool-card" onclick="runTool('{{ $tool['key'] }}')">
                                <div class="tool-card-icon" style="background:{{ $tool['color'] }}15;color:{{ $tool['color'] }};">
                                    <i class="fas {{ $tool['icon'] }}"></i>
                                </div>
                                <div class="tool-card-body">
                                    <div class="tool-card-title">{{ $tool['label'] }}</div>
                                    <div class="tool-card-desc">{{ $tool['desc'] }}</div>
                                </div>
                                <div class="tool-card-action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation();runTool('{{ $tool['key'] }}')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title"><i class="fas fa-tools text-info me-1"></i>Maintenance &amp; Utilities</div>
                    <div class="settings-block-desc">System maintenance operations.</div>
                    <div class="row g-2">
                        @php
                            $utilTools = [
                                ['key' => 'maintenance', 'label' => 'Toggle Maintenance Mode', 'icon' => 'fa-power-off', 'color' => '#ef4444', 'desc' => app()->isDownForMaintenance() ? 'Site is currently offline — click to bring it back up' : 'Put the site into maintenance mode with a 503 status'],
                                ['key' => 'backup', 'label' => 'Backup Database', 'icon' => 'fa-database', 'color' => '#10b981', 'desc' => 'Create a snapshot of the current database'],
                                ['key' => 'logs', 'label' => 'View System Logs', 'icon' => 'fa-clipboard-list', 'color' => '#6366f1', 'desc' => 'Access the latest application error logs'],
                                ['key' => 'info', 'label' => 'PHP Info', 'icon' => 'fa-info-circle', 'color' => '#f59e0b', 'desc' => 'View detailed PHP configuration and server info'],
                            ];
                        @endphp
                        @foreach($utilTools as $tool)
                        <div class="col-md-6">
                            <div class="tool-card" onclick="runTool('{{ $tool['key'] }}')">
                                <div class="tool-card-icon" style="background:{{ $tool['color'] }}15;color:{{ $tool['color'] }};">
                                    <i class="fas {{ $tool['icon'] }}"></i>
                                </div>
                                <div class="tool-card-body">
                                    <div class="tool-card-title">{{ $tool['label'] }}</div>
                                    <div class="tool-card-desc">{{ $tool['desc'] }}</div>
                                </div>
                                <div class="tool-card-action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation();runTool('{{ $tool['key'] }}')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title"><i class="fas fa-server text-primary me-1"></i>System Information</div>
                    <div class="row g-2 mt-2">
                        @php
                            $sysInfo = [
                                'PHP Version' => ['val' => phpversion(), 'icon' => 'fa-brands fa-php', 'color' => '#777bb3'],
                                'Laravel Version' => ['val' => app()->version(), 'icon' => 'fas fa-code-branch', 'color' => '#ff2d20'],
                                'Server OS' => ['val' => php_uname('s') . ' ' . php_uname('r'), 'icon' => 'fas fa-desktop', 'color' => '#10b981'],
                                'Environment' => ['val' => config('app.debug') ? 'Debug ON' : 'Production', 'icon' => 'fas fa-shield-alt', 'color' => config('app.debug') ? '#f59e0b' : '#10b981'],
                                'Database' => ['val' => 'MariaDB', 'icon' => 'fas fa-database', 'color' => '#00758f'],
                                'Cache Driver' => ['val' => config('cache.default') ?? 'file', 'icon' => 'fas fa-bolt', 'color' => '#6366f1'],
                                'Session Driver' => ['val' => config('session.driver') ?? 'file', 'icon' => 'fas fa-clock', 'color' => '#8b5cf6'],
                                'Queue Driver' => ['val' => config('queue.default') ?? 'sync', 'icon' => 'fas fa-tasks', 'color' => '#ec4899'],
                            ];
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0" style="font-size:12px;">
                                <tbody>
                                    @foreach($sysInfo as $label => $info)
                                    <tr>
                                        <td style="width:36px;" class="py-1"><i class="{{ $info['icon'] }}" style="color:{{ $info['color'] }};width:16px;text-align:center;"></i></td>
                                        <td class="text-muted py-1">{{ $label }}</td>
                                        <td class="fw-semibold text-end py-1">{{ $info['val'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            @endforeach

            <input type="hidden" name="_tab" id="activeTabInput" value="{{ $activeTab }}">

            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ms-2"><i class="fas fa-arrow-left me-1"></i> Back</a>
                </div>
                <div id="saveIndicator" class="text-muted small" style="opacity:0;transition:opacity 0.3s;">
                    <i class="fas fa-check-circle text-success me-1"></i> All changes saved
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    // Horizontal tab switching
    $('.settings-tab').on('click', function () {
        var tab = $(this).data('tab');
        $('.settings-tab').removeClass('active');
        $(this).addClass('active');
        $('.settings-tab-pane').fadeOut(150, function() {
            $('#pane-' + tab).fadeIn(200);
        });
        $('#activeTabInput').val(tab);
        history.replaceState(null, null, '?tab=' + tab);
        // Scroll content to top
        $('#settingsContent').scrollTop(0);
    });

    // Public Display: toggle manual input visibility on source change
    $(document).on('change', 'input[type="radio"][name^="public_stat_"][name$="_source"]', function () {
        var name = $(this).attr('name');
        var stat = name.replace('public_stat_', '').replace('_source', '');
        $('.manual-input-' + stat).toggle($(this).val() === 'manual');
    });

    // SMS provider toggle
    var smsFields = $('#pane-sms input.setting-input, #pane-sms select.setting-input').not('#smsEnabled');
    function toggleSmsFields() { smsFields.prop('disabled', !$('#smsEnabled').is(':checked')); }
    toggleSmsFields();
    $('#smsEnabled').on('change', toggleSmsFields);

    // ===== LIVE AUTO-SAVE with debounce =====
    var saveTimer;
    var $indicator = $('#saveIndicator');
    var pendingTab = null;
    var saving = false;

    function doSave() {
        if (saving) return;
        saving = true;
        var tab = pendingTab || $('#activeTabInput').val();
        // Temporarily set the tab input to the captured tab so validation matches
        $('#activeTabInput').val(tab);
        var data = $('#settingsForm').find('input, select, textarea').serialize();
        $indicator.html('<i class="fas fa-spinner fa-spin text-primary me-1"></i> Saving...').css('opacity', '1');
        $.ajax({
            url: '{{ route("admin.settings.update") }}',
            type: 'POST',
            data: data + '&_token=' + '{{ csrf_token() }}',
            headers: { 'X-HTTP-Method-Override': 'PUT' },
            success: function () {
                saving = false;
                $indicator.html('<i class="fas fa-check-circle text-success me-1"></i> All changes saved');
                setTimeout(function() { $indicator.css('opacity', '0'); }, 3000);
            },
            error: function () {
                saving = false;
                $indicator.html('<i class="fas fa-exclamation-circle text-danger me-1"></i> Save failed');
                setTimeout(function() { $indicator.css('opacity', '0'); }, 5000);
            }
        });
    }

    // Debounced save on input change (exclude file inputs and password/profile fields)
    $(document).on('change keyup', '.setting-input', function () {
        if ($(this).attr('type') === 'file') return;
        if ($('#activeTabInput').val() === 'profile') return;

        clearTimeout(saveTimer);
        // Capture the current tab at the time of the input event
        pendingTab = $('#activeTabInput').val();
        var immediate = $(this).is(':checkbox') || $(this).is(':radio') || $(this).is('select');
        var delay = immediate ? 50 : 800;
        saveTimer = setTimeout(doSave, delay);
    });

    // Recalculate feature toggle progress bars on checkbox change
    $(document).on('change', '#pane-public-display input[type="checkbox"]', function () {
        // Will refresh on next page load (server-side rendered stats)
        // Visual feedback: pulse the save indicator
    });

    // Character counters for textareas with maxlength
    $('textarea[maxlength]').each(function () {
        var $ta = $(this);
        var max = $ta.attr('maxlength');
        var $counter = $('<small class="text-muted" style="float:right;font-size:10px;margin-top:2px;">0 / ' + max + '</small>');
        $ta.closest('.settings-field').append($counter);
        $ta.on('input', function () {
            var len = $ta.val().length;
            $counter.text(len + ' / ' + max);
            $counter.css('color', len > max * 0.9 ? '#ef4444' : '');
        }).trigger('input');
    });

    // Live stat value updater: refresh auto values every 30s
    if ($('#pane-public-display').length) {
        setInterval(function () {
            $.get('{{ route("admin.settings.index") }}?tab=public-display&refresh=1', function (html) {
                // Update auto stat values in-place without full reload
                var $newHtml = $('<div>' + html + '</div>');
                $('.live-stat-value').each(function () {
                    var stat = $(this).data('stat');
                    var $newVal = $newHtml.find('.live-stat-value[data-stat="' + stat + '"]');
                    if ($newVal.length) {
                        $(this).text($newVal.text());
                    }
                });
            });
        }, 30000);
    }

    // Tooltip for security score items
    $('.security-score').on('mouseenter', function() {
        $(this).css('transform', 'scale(1.01)');
    }).on('mouseleave', function() {
        $(this).css('transform', 'scale(1)');
    });
});

// Avatar upload
function handleAvatar(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('avatarPreviewImg').src = e.target.result;
        document.getElementById('avatarRemoveOverlay').style.display = '';
        document.getElementById('removeAvatarField').value = '0';
    };
    reader.readAsDataURL(file);
}

document.getElementById('removeAvatarBtn')?.addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('avatarInput').value = '';
    document.getElementById('avatarPreviewImg').src = '{{ asset("assets/images/default-avatar.png") }}';
    document.getElementById('avatarRemoveOverlay').style.display = 'none';
    document.getElementById('removeAvatarField').value = '1';
});

(function() {
    var dz = document.getElementById('avatarDropZone');
    if (!dz) return;
    dz.addEventListener('dragover', function(e) { e.preventDefault(); dz.style.borderColor = 'var(--nec-green)'; dz.style.background = 'rgba(46,139,87,0.04)'; });
    dz.addEventListener('dragleave', function() { dz.style.borderColor = '#d1d5db'; dz.style.background = '#fafbfc'; });
    dz.addEventListener('drop', function(e) {
        e.preventDefault();
        dz.style.borderColor = '#d1d5db';
        dz.style.background = '#fafbfc';
        var files = e.dataTransfer.files;
        if (files.length) { document.getElementById('avatarInput').files = files; handleAvatar(document.getElementById('avatarInput')); }
    });
})();

// System tool runner (AJAX)
function runTool(key) {
    var labels = {
        view: 'View Cache', config: 'Config Cache', route: 'Route Cache', app: 'Application Cache',
        maintenance: 'Maintenance Mode', backup: 'Database Backup', logs: 'System Logs', info: 'PHP Info'
    };
    var msg = key === 'maintenance' ? 'Toggling maintenance mode...' :
              key === 'backup' ? 'Backing up database...' :
              'Clearing ' + labels[key] + '...';

    Swal.fire({ title: 'Processing...', text: msg, allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });

    $.ajax({
        url: '{{ route("admin.settings.tool") }}',
        type: 'POST',
        data: { tool: key, _token: '{{ csrf_token() }}' },
        success: function (res) {
            Swal.fire({ icon: res.success ? 'success' : 'info', title: res.success ? 'Done!' : 'Info', text: res.message, timer: 2500, showConfirmButton: true });
            if (key === 'maintenance' && res.success) { setTimeout(function() { location.reload(); }, 500); }
            if (key === 'info' && res.content) {
                var win = window.open('', '_blank');
                if (win) { win.document.write(res.content); win.document.close(); }
            }
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
        }
    });
}
</script>
@endsection
