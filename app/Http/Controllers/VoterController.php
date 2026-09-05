<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Complaint;
use App\Models\Constituency;
use App\Models\Country;
use App\Models\DiasporaMission;
use App\Models\OtpCode;
use App\Models\Voter;
use App\Models\VoterTransfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VoterController extends Controller
{
    protected function sendOtp(string $identifier, string $type, string $purpose): OtpCode
    {
        $otp = OtpCode::generate($identifier, $type, 10);

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            Mail::to($identifier)->send(new \App\Mail\OtpNotification($otp->code, $purpose));
        } else {
            // No SMS gateway configured yet — log the code for phone verification.
            \Log::info("NEC OTP for {$identifier}: {$otp->code}");
        }

        return $otp;
    }

    protected function registerOtpView(array $extra = []): \Illuminate\Http\Response
    {
        return response()
            ->view('voter.verify-otp', $extra)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function index()
    {
        return view('voter.index');
    }

    public function register(Request $request)
    {
        abort_unless(feature_enabled('public_feature_voter_registration'), 404);
        $regions = DB::table('nec_regions')->where('status', 'active')->orderBy('sort_order')->get();
        $agents = Agent::where('status', 'active')->orderBy('first_name')->get();
        $countries = Country::where('status', 'active')->orderBy('name')->get();
        $diasporaMissions = DiasporaMission::with('country')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('voter.register', compact('regions', 'agents', 'countries', 'diasporaMissions'));
    }

    public function checkDuplicate(Request $request): JsonResponse
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }

        $allowed = ['national_id', 'phone', 'email', 'passport_number'];
        if (!in_array($field, $allowed)) {
            return response()->json(['exists' => false]);
        }

        $exists = Voter::where($field, $value)->exists();

        $message = '';
        if ($exists) {
            $messages = [
                'national_id' => 'This National ID is already registered.',
                'phone' => 'This phone number is already registered.',
                'email' => 'This email is already registered.',
                'passport_number' => 'This passport number is already registered.',
            ];
            $message = $messages[$field] ?? 'This value is already registered.';
        }

        return response()->json(['exists' => $exists, 'message' => $message]);
    }

    public function autoSave(Request $request): JsonResponse
    {
        $data = $request->only([
            'full_name', 'gender', 'dob', 'national_id', 'passport_number', 'phone', 'email',
            'location_type', 'country_id', 'country_name', 'nationality', 'city', 'address',
            'postal_code', 'diaspora_mission_id',
            'state', 'county', 'constituency', 'payam', 'boma',
            'polling_station', 'registration_center',
            'registration_type', 'agent_id', 'preferred_language',
        ]);

        $request->session()->put('voter_draft', $data);

        return response()->json(['saved' => true]);
    }

    public function store(Request $request)
    {
        $minAge = \App\Helpers\NecHelper::minimum_registration_age();
        $cutoff = \App\Helpers\NecHelper::election_cutoff()->format('j F Y');
        $maxDob = \App\Helpers\NecHelper::max_dob_for_registration()->format('Y-m-d');

        $common = [
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'dob' => "required|date|before:today|after:1900-01-01|before_or_equal:{$maxDob}",
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'location_type' => 'required|in:ss,diaspora',
            'nationality' => 'nullable|string|max:120',
            'preferred_language' => 'nullable|in:English,Arabic,Other|max:50',
            'registration_type' => 'required|in:self,agent',
            'agent_id' => 'nullable|required_if:registration_type,agent|exists:nec_agents,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'id_document' => 'nullable|image|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ];

        $ss = [
            'national_id' => 'required|string|max:50',
            'state' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'constituency' => 'required|string|max:255',
            'payam' => 'required|string|max:100',
            'boma' => 'nullable|string|max:100',
            'polling_station' => 'required|string|max:255',
            'registration_center' => 'nullable|string|max:255',
        ];

        $diaspora = [
            'national_id' => 'nullable|string|max:50',
            'passport_number' => 'required|string|max:60',
            'country_id' => 'required|exists:nec_countries,id',
            'city' => 'required|string|max:120',
            'address' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:30',
            'diaspora_mission_id' => 'required|exists:nec_diaspora_missions,id',
        ];

        $validated = $request->validate(
            array_merge($common, $request->input('location_type') === 'diaspora' ? $diaspora : $ss),
            [
                'dob.before_or_equal' => "You must be at least {$minAge} years old by {$cutoff} to register or pre-register.",
                'dob.before' => 'Date of birth must be in the past.',
            ]
        );

        $isDiaspora = ($validated['location_type'] ?? 'ss') === 'diaspora';

        if (!$isDiaspora) {
            $validated['country_id'] = null;
            $validated['country_name'] = 'South Sudan';
            $validated['nationality'] = $validated['nationality'] ?: 'South Sudanese';
            $validated['city'] = null;
            $validated['address'] = null;
            $validated['postal_code'] = null;
            $validated['diaspora_mission_id'] = null;
            $validated['passport_number'] = null;
        } else {
            $country = Country::find($validated['country_id']);
            $validated['country_name'] = $country->name ?? $validated['country_name'] ?? null;
            $validated['nationality'] = $validated['nationality'] ?: ($country->nationality ?? null);
            $validated['state'] = null;
            $validated['county'] = null;
            $validated['constituency'] = null;
            $validated['payam'] = null;
            $validated['boma'] = null;
            $validated['polling_station'] = $this->diasporaPollingLabel($validated['diaspora_mission_id']);
            $validated['national_id'] = $validated['national_id'] ?? null;
        }

        $identifier = $this->uniqueChecks($validated, $isDiaspora);
        if ($identifier instanceof \Illuminate\Http\RedirectResponse) {
            return $identifier;
        }

        $pending = $validated;
        $pending['is_diaspora'] = $isDiaspora;

        if ($request->hasFile('photo')) {
            $pending['photo'] = $request->file('photo')->store('voter-verification', 'public');
        }
        if ($request->hasFile('id_document')) {
            $pending['document_photo'] = $request->file('id_document')->store('voter-verification', 'public');
            $pending['document_type'] = $isDiaspora ? 'passport' : 'national_id';
        }

        $request->session()->put('voter_pending', $pending);

        $otp = $this->sendOtp($identifier, 'voter_registration', 'voter_registration');
        $request->session()->put('voter_otp_identifier', $identifier);

        return $this->registerOtpView([
            'identifier' => $identifier,
            'mode' => $isDiaspora ? 'diaspora' : 'ss',
            'otpSent' => true,
            'voterName' => $validated['full_name'],
            'voterDraft' => $pending,
        ]);
    }

    protected function diasporaPollingLabel(int $missionId): string
    {
        $mission = DiasporaMission::with('country')->find($missionId);
        if (!$mission) {
            return '';
        }
        return ($mission->name ?: 'Diaspora Mission')
            . ($mission->city ? ', ' . $mission->city : '')
            . ($mission->country ? ', ' . $mission->country->name : '');
    }

    protected function uniqueChecks(array $validated, bool $isDiaspora): \Illuminate\Http\RedirectResponse|string
    {
        $dupFields = ['phone'];
        if (!empty($validated['national_id'])) {
            $dupFields[] = 'national_id';
        }
        if (!empty($validated['passport_number'])) {
            $dupFields[] = 'passport_number';
        }
        if (!empty($validated['email'])) {
            $dupFields[] = 'email';
        }

        foreach ($dupFields as $field) {
            $value = $validated[$field] ?? null;
            if (!$value) {
                continue;
            }
            if (Voter::where($field, $value)->exists()) {
                $labels = [
                    'national_id' => 'National ID',
                    'passport_number' => 'passport number',
                    'phone' => 'phone number',
                    'email' => 'email',
                ];
                return back()->withInput()->with('error', 'A voter with this ' . $labels[$field] . ' is already registered.');
            }
        }

        return !empty($validated['email']) && filter_var($validated['email'], FILTER_VALIDATE_EMAIL)
            ? $validated['email']
            : $validated['phone'];
    }

    public function verifyRegistrationOtp(Request $request)
    {
        $pending = $request->session()->get('voter_pending');
        $identifier = $request->session()->get('voter_otp_identifier');

        if ($request->isMethod('GET')) {
            if (!$pending || !$identifier) {
                return redirect()->route('voter.register')->with('error', 'Please start your registration again.');
            }
            return $this->registerOtpView([
                'identifier' => $identifier,
                'voterName' => $pending['full_name'] ?? '',
            ]);
        }

        if (!$pending || !$identifier) {
            return redirect()->route('voter.register')->with('error', 'Session expired. Please register again.');
        }

        $validated = $request->validate(['otp' => 'required|string|size:6']);

        if (!OtpCode::verify($identifier, $validated['otp'], 'voter_registration')) {
            return $this->registerOtpView([
                'identifier' => $identifier,
                'voterName' => $pending['full_name'] ?? '',
                'error' => 'Invalid or expired code. Please try again or request a new code.',
            ]);
        }

        $year = date('Y');
        $voterId = \App\Helpers\NecHelper::generate_voter_id($pending['gender'], $year);

        $voterData = [
            'voter_id' => $voterId,
            'full_name' => $pending['full_name'],
            'gender' => $pending['gender'],
            'dob' => $pending['dob'],
            'national_id' => $pending['national_id'] ?? null,
            'passport_number' => $pending['passport_number'] ?? null,
            'phone' => $pending['phone'],
            'email' => $pending['email'] ?? null,
            'country_id' => $pending['country_id'] ?? null,
            'country_name' => $pending['country_name'] ?? null,
            'nationality' => $pending['nationality'] ?? null,
            'city' => $pending['city'] ?? null,
            'address' => $pending['address'] ?? null,
            'postal_code' => $pending['postal_code'] ?? null,
            'is_diaspora' => $pending['is_diaspora'] ?? false,
            'diaspora_mission_id' => $pending['diaspora_mission_id'] ?? null,
            'state' => $pending['state'] ?? null,
            'county' => $pending['county'] ?? null,
            'constituency' => $pending['constituency'] ?? null,
            'payam' => $pending['payam'] ?? null,
            'boma' => $pending['boma'] ?? null,
            'polling_station' => $pending['polling_station'] ?? null,
            'registration_center' => $pending['registration_center'] ?? null,
            'photo' => $pending['photo'] ?? null,
            'document_photo' => $pending['document_photo'] ?? null,
            'document_type' => $pending['document_type'] ?? ($pending['is_diaspora'] ? 'passport' : 'national_id'),
            'preferred_language' => $pending['preferred_language'] ?? 'English',
            'registration_type' => $pending['registration_type'],
            'status' => 'active',
            'registered_at' => now(),
            'verified_at' => now(),
        ];

        if ($pending['registration_type'] === 'agent' && !empty($pending['agent_id'])) {
            $agent = Agent::find($pending['agent_id']);
            if ($agent) {
                $voterData['registered_by_user_id'] = $agent->id;
                $voterData['registered_by_code'] = $agent->agent_code;
                $voterData['registered_by_name'] = $agent->full_name;
                $voterData['registered_by_title'] = $agent->title;
                $voterData['registered_by_location'] = $agent->assigned_state . ($agent->assigned_constituency ? ', ' . $agent->assigned_constituency : '');
                $agent->increment('voters_registered');
            }
        } else {
            $voterData['registered_by_name'] = 'Self-Registration Portal';
            $voterData['registered_by_title'] = 'Online System';
            $voterData['registered_by_location'] = 'NEC Voter Portal';
        }

        $dob = $pending['dob'] ? \Carbon\Carbon::parse($pending['dob']) : null;
        $voterData['eligibility_date'] = \App\Helpers\NecHelper::eligibility_date($dob);
        $voterData['eligible_to_vote'] = $dob && \App\Helpers\NecHelper::age_at($dob) >= \App\Helpers\NecHelper::voting_age();
        $voterData['pre_registered'] = $dob ? $dob->age < \App\Helpers\NecHelper::voting_age() : false;

        $voter = Voter::create($voterData);

        \App\Models\Notification::notifyAdmins(
            "New voter registered: {$voter->full_name} ({$voter->voter_id})",
            [
                'title' => 'Voter Registration',
                'type' => 'voter',
                'icon' => 'user-plus',
                'color' => 'success',
                'link' => route('admin.voters.show', $voter->id),
            ]
        );

        $request->session()->forget(['voter_pending', 'voter_draft', 'voter_otp_identifier']);
        $request->session()->put('new_voter_id', $voterId);
        $request->session()->put('new_voter_name', $pending['full_name']);
        $request->session()->put('new_voter_gender', $pending['gender'] === 'M' ? 'Male' : 'Female');
        $request->session()->put('new_voter_state', $pending['country_name'] ?? $pending['state'] ?? '');
        $request->session()->put('new_voter_county', $pending['county'] ?? $pending['city'] ?? '');
        $request->session()->put('new_voter_station', $pending['polling_station'] ?? 'N/A');

        return redirect()->route('voter.registration-success')
            ->with('success', 'Registration successful! Your Voter ID is: ' . $voterId)
            ->with('voter_id', $voterId);
    }

    public function resendRegistrationOtp(Request $request)
    {
        $pending = $request->session()->get('voter_pending');
        $identifier = $request->session()->get('voter_otp_identifier');

        if (!$pending || !$identifier) {
            return redirect()->route('voter.register')->with('error', 'Session expired. Please register again.');
        }

        $this->sendOtp($identifier, 'voter_registration', 'voter_registration');

        return $this->registerOtpView([
            'identifier' => $identifier,
            'voterName' => $pending['full_name'] ?? '',
            'otpSent' => true,
            'success' => 'A new code has been sent.',
        ]);
    }

    /* ─── STATUS CHECK ───────────────────────────────────────── */

    public function status(Request $request)
    {
        abort_unless(feature_enabled('public_feature_voter_inquiry'), 404);
        $voter = null;
        $error = null;
        $searched = false;

        if ($request->isMethod('post')) {
            $searched = true;
            $query = $request->input('query', '');
            if ($query) {
                $voter = Voter::where('voter_id', $query)
                    ->orWhere('national_id', $query)
                    ->orWhere('phone', $query)
                    ->first();
                if (!$voter) {
                    $error = 'No voter found matching: <strong>' . htmlspecialchars($query) . '</strong>';
                }
            }
        }

        return view('voter.status', compact('voter', 'error', 'searched'));
    }

    /* ─── VERIFY ─────────────────────────────────────────────── */

    public function verify(Request $request)
    {
        $voterResult = null;
        $voterError = null;
        $searched = false;

        if ($request->isMethod('post')) {
            $searched = true;
            $voterId = $request->input('voter_id', '');
            if ($voterId) {
                $voterResult = Voter::where('voter_id', $voterId)->first();
                if (!$voterResult) {
                    $voterError = 'No voter found with ID: <strong>' . htmlspecialchars($voterId) . '</strong>';
                }
            }
        }

        return view('voter.verify', compact('voterResult', 'voterError', 'searched'));
    }

    /* ─── POLLING FINDER ─────────────────────────────────────── */

    public function pollingFinder(Request $request)
    {
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();
        $results = null;
        $searched = false;

        if ($request->isMethod('post')) {
            $searched = true;
            $state = $request->input('state', '');
            $county = $request->input('county', '');
            $constituency = $request->input('constituency', '');

            $query = DB::table('nec_polling_stations')->where('status', 'active');
            if ($state) {
                $stateCode = DB::table('nec_states')->where('name', $state)->value('code');
                $query->where(function ($q) use ($state, $stateCode) {
                    $q->where('state', $state);
                    if ($stateCode) $q->orWhere('state', $stateCode);
                });
            }
            if ($county) {
                $countyBase = preg_replace('/\s*County$/i', '', $county);
                $query->where(function ($q) use ($county, $countyBase) {
                    $q->where('county', $county)->orWhere('county', $countyBase);
                });
            }
            if ($constituency) {
                $query->where('constituency', $constituency);
            }

            $results = $query->orderBy('name')->get();
        }

        return view('voter.polling-finder', compact('states', 'results', 'searched'));
    }

    /* ─── TRANSFER ───────────────────────────────────────────── */

    public function transfer(Request $request)
    {
        abort_unless(feature_enabled('public_feature_voter_transfer'), 404);
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'voter_id' => 'required|string',
                'full_name' => 'required|string|max:255',
                'from_state' => 'required|string',
                'from_constituency' => 'required|string',
                'to_state' => 'required|string',
                'to_constituency' => 'required|string',
                'reason' => 'required|string|max:1000',
            ]);

            $voter = Voter::where('voter_id', $validated['voter_id'])->first();
            if (!$voter) {
                return back()->withInput()->with('error', 'Voter ID not found. Please check your ID.');
            }

            VoterTransfer::create([
                'voter_id' => $voter->voter_id,
                'voter_identifier' => $validated['voter_id'],
                'full_name' => $validated['full_name'],
                'from_state' => $validated['from_state'],
                'from_constituency' => $validated['from_constituency'],
                'to_state' => $validated['to_state'],
                'to_constituency' => $validated['to_constituency'],
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            return back()->with('success', 'Transfer request submitted successfully! It will be reviewed by NEC staff.');
        }

        return view('voter.transfer', compact('states'));
    }

    /* ─── INQUIRY ────────────────────────────────────────────── */

    public function inquiry(Request $request)
    {
        $voterResult = null;
        $voterError = null;

        if ($request->isMethod('get') && $request->filled('voter_id')) {
            $voterId = $request->input('voter_id');
            $voterResult = Voter::where('voter_id', $voterId)->first();
            if (!$voterResult) {
                $voterError = 'No voter found with ID: <strong>' . htmlspecialchars($voterId) . '</strong>. Please check and try again.';
            }
        }

        return view('voter.inquiry', compact('voterResult', 'voterError'));
    }

    /* ─── FORGOT ID ──────────────────────────────────────────── */

    public function forgotId(Request $request)
    {
        $foundId = null;
        $foundName = null;
        $error = null;

        if ($request->isMethod('post')) {
            $query = $request->input('query', '');
            if ($query) {
                $voter = Voter::where('national_id', $query)
                    ->orWhere('phone', $query)
                    ->orWhere('full_name', 'LIKE', '%' . $query . '%')
                    ->first();
                if ($voter) {
                    $foundId = $voter->voter_id;
                    $foundName = $voter->full_name;
                } else {
                    $error = 'No voter found matching: <strong>' . htmlspecialchars($query) . '</strong>. Try your National ID, phone number, or full name.';
                }
            }
        }

        return view('voter.forgot-id', compact('foundId', 'foundName', 'error'));
    }

    /* ─── REPORT ISSUE ───────────────────────────────────────── */

    public function reportIssue(Request $request)
    {
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'category' => 'required|in:registration,voter_card,polling_station,results,observer,staff,other',
                'subject' => 'required|string|max:255',
                'description' => 'required|string|max:5000',
            ]);

            $validated['status'] = 'new';
            $validated['priority'] = 'medium';
            $validated['created_at'] = now();
            $validated['updated_at'] = now();

            DB::table('nec_complaints')->insert($validated);

            \App\Models\Notification::notifyAdmins(
                "New {$validated['category']} report from {$validated['full_name']}: {$validated['subject']}",
                [
                    'title' => 'Issue Reported',
                    'type' => 'complaint',
                    'icon' => 'exclamation-circle',
                    'color' => 'warning',
                    'link' => route('admin.complaints.index'),
                ]
            );

            return back()->with('success', 'Your report has been submitted. Reference will be sent to your email/phone.');
        }

        return view('voter.report-issue', compact('states'));
    }

    /* ─── STATIC PAGES ───────────────────────────────────────── */

    public function education()
    {
        $materials = \App\Models\EducationMaterial::where('status', 'published')->orderBy('title')->get();

        $sections = [
            'baseline_en' => ['title' => 'Baseline Survey', 'lang' => 'English', 'icon' => 'fa-chart-bar'],
            'baseline_ar' => ['title' => 'Baseline Survey', 'lang' => 'Arabic', 'icon' => 'fa-chart-bar'],
            'strategy' => ['title' => 'Civic Voter Education Strategy', 'lang' => 'English', 'icon' => 'fa-route'],
            'curriculum' => ['title' => 'Civic Voter Education Curriculum', 'lang' => 'English', 'icon' => 'fa-book-open'],
            'manual_en' => ['title' => 'Training Manual', 'lang' => 'English', 'icon' => 'fa-chalkboard-teacher'],
            'manual_ar' => ['title' => 'Training Manual', 'lang' => 'Arabic', 'icon' => 'fa-chalkboard-teacher'],
            'booklet_en' => ['title' => 'Civic Education Booklet', 'lang' => 'English', 'icon' => 'fa-book'],
            'booklet_ar' => ['title' => 'Civic Education Booklet', 'lang' => 'Arabic', 'icon' => 'fa-book'],
        ];

        $keywords = [
            'baseline_en' => ['Baseline Survey', 'English'],
            'baseline_ar' => ['Baseline Survey', 'Arabic'],
            'strategy' => ['Civic Voter Education Strategy'],
            'curriculum' => ['Civic Voter Education Curriculum'],
            'manual_en' => ['Training Manual', 'English'],
            'manual_ar' => ['Training Manual', 'Arabic'],
            'booklet_en' => ['Booklet', 'English'],
            'booklet_ar' => ['Booklet', 'Arabic'],
        ];

        $resources = [];
        foreach ($sections as $key => $info) {
            $material = $materials->first(function ($m) use ($keywords, $key) {
                $need = $keywords[$key];
                return collect($need)->every(fn($w) => str_contains(strtolower($m->title), strtolower($w)));
            });

            $resources[] = [
                'key' => $key,
                'icon' => $info['icon'],
                'lang' => $info['lang'],
                'title' => \App\Helpers\NecHelper::setting_get("cve_{$key}_title") ?: $info['title'],
                'desc' => \App\Helpers\NecHelper::setting_get("cve_{$key}_desc"),
                'image' => \App\Helpers\NecHelper::setting_get("cve_{$key}_image"),
                'material' => $material,
            ];
        }

        return view('voter.education', compact('resources'));
    }

    public function howToVote()
    {
        return view('voter.how-to-vote');
    }

    public function idCard()
    {
        return view('voter.id-card');
    }
}
