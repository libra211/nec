<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('_tab', 'general');

        // Build validation rules by tab
        $rules = match ($tab) {
            'general' => [
                'site_name' => 'nullable|string|max:255',
                'site_tagline' => 'nullable|string|max:500',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:50',
                'contact_address' => 'nullable|string|max:1000',
                'office_hours' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
            ],
            'sms' => [
                'sms_provider' => 'nullable|string|max:50',
                'sms_api_key' => 'nullable|string|max:500',
                'sms_api_secret' => 'nullable|string|max:500',
                'sms_from_number' => 'nullable|string|max:50',
                'sms_sender_id' => 'nullable|string|max:20',
                'sms_enabled' => 'nullable|string|in:0,1,on,off,true,false',
                'sms_otp_template' => 'nullable|string|max:500',
                'sms_balance_url' => 'nullable|url|max:500',
            ],
            'email' => [
                'mail_driver' => 'nullable|string|max:100',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|string|max:10',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:500',
                'mail_encryption' => 'nullable|string|max:50',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
            ],
            'social' => [
                'facebook_url' => 'nullable|url|max:500',
                'twitter_url' => 'nullable|url|max:500',
                'youtube_url' => 'nullable|url|max:500',
                'instagram_url' => 'nullable|url|max:500',
                'linkedin_url' => 'nullable|url|max:500',
                'whatsapp_number' => 'nullable|string|max:50',
            ],
            'elections' => [
                'election_date' => 'nullable|date',
                'election_year' => 'nullable|string|max:10',
                'election_type' => 'nullable|string|max:100',
                'voter_registration_deadline' => 'nullable|date',
                'nomination_deadline' => 'nullable|date',
                'campaign_start' => 'nullable|date',
                'campaign_end' => 'nullable|date',
            ],
            'security' => [
                'otp_expiry_minutes' => 'nullable|integer|min:1|max:60',
                'max_login_attempts' => 'nullable|integer|min:1|max:50',
                'brute_force_window' => 'nullable|integer|min:1|max:120',
                'session_lifetime' => 'nullable|integer|min:5|max:1440',
                'recaptcha_enabled' => 'nullable|string|in:0,1,on,off,true,false',
                'recaptcha_site_key' => 'nullable|string|max:500',
                'recaptcha_secret_key' => 'nullable|string|max:500',
            ],
            default => [],
        };

        $validated = $request->validate($rules);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            $validated['logo'] = Storage::url($path);
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $validated['favicon'] = Storage::url($path);
        }

        // Handle toggle/checkbox fields explicitly
        if ($tab === 'sms') {
            $validated['sms_enabled'] = $request->boolean('sms_enabled') ? '1' : '0';
        }
        if ($tab === 'security') {
            $validated['recaptcha_enabled'] = $request->boolean('recaptcha_enabled') ? '1' : '0';
        }

        foreach ($validated as $key => $value) {
            \App\Helpers\NecHelper::setting_set($key, $value ?? '');
        }

        return back()->with('success', 'Settings updated successfully.')->with('active_tab', $tab);
    }
}
