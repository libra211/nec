<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\Setting;
use App\Models\User;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $user = User::find(session('admin_user_id'));
        $allLoginLogs = LoginLog::latest('logged_at')->paginate(50);

        return view('admin.settings.index', compact('settings', 'user', 'allLoginLogs'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('_tab', 'general');

        if ($tab === 'profile') {
            return $this->updateProfile($request);
        }

        if ($tab === 'public-display') {
            return $this->updatePublicDisplay($request);
        }

        if ($tab === 'voter-education') {
            return $this->updateVoterEducation($request);
        }

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
            'public-content' => [
                'hero_heading' => 'nullable|string|max:200',
                'hero_subheading' => 'nullable|string|max:500',
                'hero_cta_text' => 'nullable|string|max:50',
                'hero_cta_url' => 'nullable|string|max:255',
                'about_title' => 'nullable|string|max:200',
                'about_subtitle' => 'nullable|string|max:200',
                'about_content' => 'nullable|string|max:2000',
                'mission_text' => 'nullable|string|max:1000',
                'vision_text' => 'nullable|string|max:1000',
                'core_values_title' => 'nullable|string|max:100',
                'core_value_1_name' => 'nullable|string|max:100',
                'core_value_1_desc' => 'nullable|string|max:200',
                'core_value_2_name' => 'nullable|string|max:100',
                'core_value_2_desc' => 'nullable|string|max:200',
                'core_value_3_name' => 'nullable|string|max:100',
                'core_value_3_desc' => 'nullable|string|max:200',
                'core_value_4_name' => 'nullable|string|max:100',
                'core_value_4_desc' => 'nullable|string|max:200',
                'core_value_5_name' => 'nullable|string|max:100',
                'core_value_5_desc' => 'nullable|string|max:200',
                'core_value_6_name' => 'nullable|string|max:100',
                'core_value_6_desc' => 'nullable|string|max:200',
                'footer_about' => 'nullable|string|max:500',
                'footer_copyright' => 'nullable|string|max:200',
            ],
            'seo' => [
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'google_analytics_id' => 'nullable|string|max:50',
                'google_tag_manager_id' => 'nullable|string|max:50',
                'facebook_pixel_id' => 'nullable|string|max:50',
            ],
            'notifications' => [
                'notify_email' => 'nullable|email|max:255',
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
                'password_min_length' => 'nullable|integer|min:6|max:64',
                'recaptcha_enabled' => 'nullable|string|in:0,1,on,off,true,false',
                'recaptcha_site_key' => 'nullable|string|max:500',
                'recaptcha_secret_key' => 'nullable|string|max:500',
                'allowed_ips' => 'nullable|string|max:2000',
            ],
            default => [],
        };

        $validated = $request->validate($rules);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            $validated['logo'] = Storage::url($path);
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $validated['favicon'] = Storage::url($path);
        }

        // Handle boolean toggles
        $boolFields = [
            'sms' => ['sms_enabled'],
            'security' => ['recaptcha_enabled', 'ip_whitelist_enabled', 'password_require_special'],
            'notifications' => ['notify_new_contact', 'notify_new_voter_registration', 'notify_new_observer_application', 'notify_new_complaint'],
        ];
        if (isset($boolFields[$tab])) {
            foreach ($boolFields[$tab] as $field) {
                $validated[$field] = $request->boolean($field) ? '1' : '0';
            }
        }

        foreach ($validated as $key => $value) {
            \App\Helpers\NecHelper::setting_set($key, $value ?? '');
        }

        $this->logActivity('settings_updated', "Updated settings tab: {$tab}");

        return back()->with('success', 'Settings updated successfully.')->with('active_tab', $tab);
    }

    public function tool(Request $request)
    {
        $tool = $request->input('tool');
        $result = ['success' => true, 'message' => ''];

        try {
            match ($tool) {
                'view' => (function () use (&$result) {
                    Artisan::call('view:clear');
                    $result['message'] = 'View cache cleared successfully. All Blade templates will be recompiled.';
                })(),
                'config' => (function () use (&$result) {
                    Artisan::call('config:clear');
                    $result['message'] = 'Config cache cleared. Configuration will be reloaded from source files.';
                })(),
                'route' => (function () use (&$result) {
                    Artisan::call('route:clear');
                    $result['message'] = 'Route cache cleared. Routes will be reloaded.';
                })(),
                'app' => (function () use (&$result) {
                    Cache::flush();
                    Artisan::call('view:clear');
                    Artisan::call('config:clear');
                    $result['message'] = 'All application cache cleared (view, config, data cache).';
                })(),
                'maintenance' => (function () use (&$result) {
                    if (app()->isDownForMaintenance()) {
                        Artisan::call('up');
                        $result['message'] = 'Site is now live. Maintenance mode disabled.';
                    } else {
                        Artisan::call('down', ['--retry' => 60]);
                        $result['message'] = 'Site is now in maintenance mode (503). Only users with the IP whitelist can access it.';
                    }
                })(),
                'backup' => (function () use (&$result) {
                    $result['success'] = false;
                    $result['message'] = 'Database backup is not yet implemented. This will use your configured backup driver.';
                })(),
                'logs' => (function () use (&$result) {
                    $logFile = storage_path('logs/laravel.log');
                    if (file_exists($logFile)) {
                        $size = round(filesize($logFile) / 1024 / 1024, 2);
                        $lines = tail($logFile, 20);
                        $result['message'] = "Log file size: {$size} MB. Last 20 lines:\n\n" . $lines;
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'No log file found at storage/logs/laravel.log';
                    }
                })(),
                'info' => (function () use (&$result) {
                    $result['success'] = true;
                    $software = $_SERVER['SERVER_SOFTWARE'] ?? 'CLI';
                    $result['message'] = 'PHP ' . phpversion() . ' | ' . php_uname('s') . ' ' . php_uname('r') . ' | ' . $software;
                })(),
                default => (function () use (&$result) {
                    $result['success'] = false;
                    $result['message'] = 'Unknown tool: ' . $tool;
                })(),
            };
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = 'Error: ' . $e->getMessage();
        }

        $this->logActivity('tool_executed', "Ran tool: {$tool}");

        return response()->json($result);
    }

    private function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:nec_users,email,' . session('admin_user_id'),
            'phone' => 'nullable|string|max:50',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::find(session('admin_user_id'));

        if (!$user) {
            return back()->with('error', 'User not found.')->with('active_tab', 'profile');
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->with('error', 'Current password is incorrect.')->with('active_tab', 'profile');
            }
        }

        $updateData = [
            'name' => InputSanitizer::clean($validated['name']),
            'email' => InputSanitizer::clean($validated['email']),
        ];

        if ($request->filled('phone')) {
            $updateData['phone'] = InputSanitizer::clean($validated['phone']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->input('remove_avatar') === '1') {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = null;
        }

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        session(['admin_user_name' => $updateData['name'], 'admin_email' => $updateData['email']]);

        $this->logActivity('profile_updated', "Updated profile: {$user->name}", $user);

        return back()->with('success', 'Profile updated successfully.')->with('active_tab', 'profile');
    }

    private function updateVoterEducation(Request $request)
    {
        $section = $request->input('_section');

        $allowed = [
            'baseline_en', 'baseline_ar', 'strategy', 'curriculum',
            'manual_en', 'manual_ar', 'booklet_en', 'booklet_ar',
        ];

        if (!in_array($section, $allowed, true)) {
            return back()->with('error', 'Unknown voter education section.')->with('active_tab', 'voter-education');
        }

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'title' => 'nullable|string|max:200',
            'desc' => 'nullable|string|max:500',
        ]);

        $prefix = "cve_{$section}";

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('voter-education', 'public');
            \App\Helpers\NecHelper::setting_set("{$prefix}_image", Storage::url($path));
        } elseif ($request->boolean('remove_image')) {
            \App\Helpers\NecHelper::setting_set("{$prefix}_image", '');
        }

        \App\Helpers\NecHelper::setting_set("{$prefix}_title", $validated['title'] ?? '');
        \App\Helpers\NecHelper::setting_set("{$prefix}_desc", $validated['desc'] ?? '');

        $this->logActivity('voter_education_updated', "Updated voter education resource: {$section}");

        return back()->with('success', 'Voter education section saved successfully.')->with('active_tab', 'voter-education');
    }

    private function updatePublicDisplay(Request $request)
    {
        $allStats = [
            'election_date', 'registration_deadline', 'election_type',
            'total_voters', 'gender_split', 'registration_type', 'age_distribution', 'weekly_trend',
            'constituencies', 'polling_stations', 'counties', 'payams', 'states_with_data',
            'parties', 'candidates', 'observers', 'ballot_types',
            'agents', 'commissioners', 'polling_staff', 'trained_staff',
            'news', 'events', 'gallery', 'downloads', 'speeches', 'subscribers',
        ];

        $numericStats = [
            'total_voters', 'constituencies', 'polling_stations', 'counties', 'payams',
            'states_with_data', 'parties', 'candidates', 'observers', 'ballot_types',
            'agents', 'commissioners', 'polling_staff', 'trained_staff',
            'news', 'events', 'gallery', 'downloads', 'speeches', 'subscribers',
        ];

        $textStats = ['election_type'];
        $dateStats = ['election_date', 'registration_deadline'];

        $featureToggles = [
            'public_feature_parties', 'public_feature_candidates', 'public_feature_results',
            'public_feature_voter_registration', 'public_feature_voter_inquiry', 'public_feature_voter_transfer',
            'public_feature_observers', 'public_feature_gallery', 'public_feature_downloads',
            'public_feature_education', 'public_feature_news', 'public_feature_events',
            'public_feature_speeches', 'public_feature_videos',
        ];
        foreach ($featureToggles as $key) {
            \App\Helpers\NecHelper::setting_set($key, $request->boolean($key) ? '1' : '0');
        }

        \App\Helpers\NecHelper::setting_set('public_show_stats', $request->boolean('public_show_stats') ? '1' : '0');

        foreach ($allStats as $stat) {
            \App\Helpers\NecHelper::setting_set("public_show_{$stat}", $request->boolean("public_show_{$stat}") ? '1' : '0');
        }

        foreach ($numericStats as $stat) {
            $source = $request->input("public_stat_{$stat}_source", 'auto');
            \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_source", $source);
            if ($source === 'manual') {
                \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_value", $request->input("public_stat_{$stat}_value", ''));
            }
        }

        foreach ($textStats as $stat) {
            $source = $request->input("public_stat_{$stat}_source", 'auto');
            \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_source", $source);
            if ($source === 'manual') {
                \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_value", $request->input("public_stat_{$stat}_value", ''));
            }
        }

        foreach ($dateStats as $stat) {
            $source = $request->input("public_stat_{$stat}_source", 'auto');
            \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_source", $source);
            if ($source === 'manual') {
                \App\Helpers\NecHelper::setting_set("public_stat_{$stat}_value", $request->input("public_stat_{$stat}_value", ''));
            }
        }

        $this->logActivity('public_display_updated', "Updated public display settings");

        return back()->with('success', 'Public display settings updated.')->with('active_tab', 'public-display');
    }

}

if (!function_exists('tail')) {
    function tail($file, $lines = 10) {
        $fp = fopen($file, 'r');
        if (!$fp) return '';
        fseek($fp, -1, SEEK_END);
        $pos = ftell($fp);
        $output = '';
        $count = 0;
        while ($pos > 0 && $count < $lines) {
            $char = fgetc($fp);
            if ($char === "\n") $count++;
            if ($count <= $lines) $output = $char . $output;
            fseek($fp, --$pos);
        }
        fclose($fp);
        return trim($output);
    }
}