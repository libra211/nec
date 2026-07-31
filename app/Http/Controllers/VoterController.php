<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Complaint;
use App\Models\Constituency;
use App\Models\Voter;
use App\Models\VoterTransfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterController extends Controller
{
    public function index()
    {
        return view('voter.index');
    }

    public function register(Request $request)
    {
        abort_unless(feature_enabled('public_feature_voter_registration'), 404);
        $regions = DB::table('nec_regions')->where('status', 'active')->orderBy('sort_order')->get();
        $agents = Agent::where('status', 'active')->orderBy('first_name')->get();

        return view('voter.register', compact('regions', 'agents'));
    }

    public function checkDuplicate(Request $request): JsonResponse
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }

        $allowed = ['national_id', 'phone', 'email'];
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
            ];
            $message = $messages[$field] ?? 'This value is already registered.';
        }

        return response()->json(['exists' => $exists, 'message' => $message]);
    }

    public function autoSave(Request $request): JsonResponse
    {
        $data = $request->only([
            'full_name', 'gender', 'dob', 'national_id', 'phone', 'email',
            'state', 'county', 'constituency', 'payam', 'boma',
            'polling_station', 'registration_center',
            'registration_type', 'agent_id',
        ]);

        $request->session()->put('voter_draft', $data);

        return response()->json(['saved' => true]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'dob' => 'required|date|before:today',
            'national_id' => 'required|string|max:50',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'state' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'constituency' => 'required|string|max:255',
            'payam' => 'required|string|max:100',
            'boma' => 'nullable|string|max:100',
            'polling_station' => 'required|string|max:255',
            'registration_center' => 'nullable|string|max:255',
            'registration_type' => 'required|in:self,agent',
            'agent_id' => 'nullable|required_if:registration_type,agent|exists:nec_agents,id',
        ]);

        $existing = Voter::where('national_id', $validated['national_id'])->first();
        if ($existing) {
            return back()->withInput()->with('error', 'A voter with this National ID is already registered.');
        }

        $existingPhone = Voter::where('phone', $validated['phone'])->first();
        if ($existingPhone) {
            return back()->withInput()->with('error', 'A voter with this phone number is already registered.');
        }

        if (!empty($validated['email'])) {
            $existingEmail = Voter::where('email', $validated['email'])->first();
            if ($existingEmail) {
                return back()->withInput()->with('error', 'A voter with this email is already registered.');
            }
        }

        $year = date('Y');
        $voterId = \App\Helpers\NecHelper::generate_voter_id($validated['gender'], $year);

        $voterData = [
            'voter_id' => $voterId,
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'national_id' => $validated['national_id'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'state' => $validated['state'],
            'county' => $validated['county'],
            'constituency' => $validated['constituency'],
            'payam' => $validated['payam'],
            'boma' => $validated['boma'] ?? null,
            'polling_station' => $validated['polling_station'],
            'registration_center' => $validated['registration_center'] ?? null,
            'registration_type' => $validated['registration_type'],
            'status' => 'active',
            'registered_at' => now(),
        ];

        if ($validated['registration_type'] === 'agent' && !empty($validated['agent_id'])) {
            $agent = Agent::find($validated['agent_id']);
            if ($agent) {
                $voterData['registered_by_user_id'] = $agent->id;
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

        Voter::create($voterData);

        $request->session()->forget('voter_draft');
        $request->session()->put('new_voter_id', $voterId);
        $request->session()->put('new_voter_name', $validated['full_name']);
        $request->session()->put('new_voter_gender', $validated['gender'] === 'M' ? 'Male' : 'Female');
        $request->session()->put('new_voter_state', $validated['state']);
        $request->session()->put('new_voter_county', $validated['county']);
        $request->session()->put('new_voter_station', $validated['polling_station'] ?? 'N/A');

        return redirect()->route('voter.registration-success')
            ->with('success', 'Registration successful! Your Voter ID is: ' . $voterId)
            ->with('voter_id', $voterId);
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
