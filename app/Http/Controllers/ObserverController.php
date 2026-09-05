<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ObserverApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ObserverController extends Controller
{
    public function index()
    {
        abort_unless(feature_enabled('public_feature_observers'), 404);
        return view('observers.index');
    }

    public function accredit()
    {
        abort_unless(feature_enabled('public_feature_observers'), 404);
        return view('observers.accredit');
    }

    public function apply(Request $request)
    {
        abort_unless(feature_enabled('public_feature_observers'), 404);

        $type = in_array($request->query('type'), ['domestic', 'international'], true)
            ? $request->query('type')
            : (old('form_type') ?: 'domestic');

        $countries = Country::where('status', 'active')
            ->orderBy('continent')->orderBy('name')
            ->get(['id', 'name', 'nationality', 'continent', 'calling_code'])
            ->groupBy('continent');

        return view('observers.apply', compact('type', 'countries'));
    }

    public function applySubmit(Request $request)
    {
        $formType = $request->input('form_type', 'domestic');

        if ($formType === 'domestic') {
            return $this->processDomestic($request);
        }

        return $this->processInternational($request);
    }

    protected function processDomestic(Request $request)
    {
        $validated = $request->validate([
            'title'                     => 'nullable|in:Mr,Mrs,Ms,Dr,Prof,Hon,Other',
            'first_name'                => 'required|string|max:100',
            'last_name'                 => 'required|string|max:100',
            'other_names'               => 'nullable|string|max:150',
            'gender'                    => 'required|in:male,female,other',
            'dob'                       => 'required|date|before:today',
            'nationality'               => ['required', Rule::in([config('observer.domestic_nationality')])],
            'national_id'               => 'required|string|max:100',
            'email'                     => 'required|email|max:255',
            'phone'                     => ['required', 'string', 'max:20', 'regex:/^\+211[\s\-]?[0-9]{9}$/'],
            'languages'                 => ['required', 'array', 'min:1'],
            'languages.*'               => ['required', Rule::in(config('observer.south_sudanese_languages'))],
            'form_type'                 => 'required|in:domestic',
            'residential_address'       => 'nullable|string|max:500',
            'postal_address'            => 'nullable|string|max:255',
            'emergency_contact_name'    => 'nullable|string|max:200',
            'emergency_contact_phone'   => 'nullable|string|min:9|max:20',
            'employer'                  => 'nullable|string|max:255',
            'job_title'                 => 'nullable|string|max:255',
            'employment_duration'       => 'nullable|string|max:100',
            'organization_name'         => 'nullable|string|max:255',
            'organization_registration' => 'nullable|string|max:100',
            'org_address'               => 'nullable|string|max:500',
            'sponsoring_org'            => 'nullable|string|max:255',
            'observer_count'            => 'nullable|integer|min:1|max:100',
            'deployment_areas'          => 'nullable|string|max:500',
            'previous_missions'         => 'nullable|string|max:1000',
            'election_experience'       => 'nullable|string|max:1000',
            'agree_code'                => 'accepted',
        ]);

        $data = collect($validated)
            ->only([
                'title', 'first_name', 'last_name', 'other_names', 'gender', 'dob',
                'nationality', 'national_id', 'email', 'phone', 'languages',
                'residential_address', 'postal_address', 'emergency_contact_name',
                'emergency_contact_phone', 'employer', 'job_title', 'employment_duration',
                'organization_name', 'organization_registration', 'org_address',
                'sponsoring_org', 'observer_count', 'deployment_areas',
                'previous_missions', 'election_experience',
            ])->toArray();

        $data['form_type'] = 'domestic';
        $data['observer_type'] = 'domestic';
        $data['nationality_id'] = null;
        $data['continent'] = 'Africa';
        $data['languages'] = implode(', ', $validated['languages']);

        return $this->persist($request, $data);
    }

    protected function processInternational(Request $request)
    {
        $validated = $request->validate([
            'title'                     => 'nullable|in:Mr,Mrs,Ms,Dr,Prof,Hon,Other',
            'first_name'                => 'required|string|max:100',
            'last_name'                 => 'required|string|max:100',
            'other_names'               => 'nullable|string|max:150',
            'gender'                    => 'required|in:male,female,other',
            'dob'                       => 'required|date|before:today',
            'nationality_id'            => 'required|integer|exists:nec_countries,id',
            'passport_number'           => 'required|string|max:100',
            'email'                     => 'required|email|max:255',
            'phone'                     => ['required', 'string', 'max:20', 'regex:/^\+[1-9][0-9\s\-]{6,14}$/'],
            'languages'                 => ['required', 'array', 'min:1'],
            'languages.*'               => ['required', Rule::in(config('observer.world_languages'))],
            'form_type'                 => 'required|in:international',
            'residential_address'       => 'nullable|string|max:500',
            'postal_address'            => 'nullable|string|max:255',
            'emergency_contact_name'    => 'nullable|string|max:200',
            'emergency_contact_phone'   => 'nullable|string|min:9|max:20',
            'employer'                  => 'nullable|string|max:255',
            'job_title'                 => 'nullable|string|max:255',
            'employment_duration'       => 'nullable|string|max:100',
            'organization_name'         => 'nullable|string|max:255',
            'organization_registration' => 'nullable|string|max:100',
            'org_address'               => 'nullable|string|max:500',
            'sponsoring_org'            => 'nullable|string|max:255',
            'observer_count'            => 'nullable|integer|min:1|max:100',
            'deployment_areas'          => 'nullable|string|max:500',
            'previous_missions'         => 'nullable|string|max:1000',
            'election_experience'       => 'nullable|string|max:1000',
            'agree_code'                => 'accepted',
        ]);

        $country = Country::find($validated['nationality_id']);

        if (! $country) {
            return back()->withErrors(['nationality_id' => 'Selected nationality is not recognised.'])->withInput();
        }

        $data = collect($validated)
            ->only([
                'title', 'first_name', 'last_name', 'other_names', 'gender', 'dob',
                'passport_number', 'email', 'phone', 'languages',
                'residential_address', 'postal_address', 'emergency_contact_name',
                'emergency_contact_phone', 'employer', 'job_title', 'employment_duration',
                'organization_name', 'organization_registration', 'org_address',
                'sponsoring_org', 'observer_count', 'deployment_areas',
                'previous_missions', 'election_experience',
            ])->toArray();

        $data['form_type'] = 'international';
        $data['observer_type'] = 'international';
        $data['nationality'] = $country->name;
        $data['nationality_id'] = $country->id;
        $data['continent'] = $country->continent;
        $data['country_code'] = $country->calling_code;
        $data['national_id'] = null;
        $data['languages'] = implode(', ', $validated['languages']);

        return $this->persist($request, $data);
    }

    protected function persist(Request $request, array $data)
    {
        $uploadMap = [
            'passport_photo'        => 'observer_uploads/photos',
            'cv_biography'          => 'observer_uploads/cv',
            'letter_of_appointment' => 'observer_uploads/letters',
            'proof_registration'    => 'observer_uploads/proofs',
        ];

        foreach ($uploadMap as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store($directory, 'public');
            }
        }

        $data['status'] = 'pending';
        $data['verification_token'] = Str::random(40);

        $application = ObserverApplication::create($data);

        return redirect()->route('observers.apply.success', $application->id)
            ->with('success', 'Application submitted successfully.');
    }

    public function applySuccess($id)
    {
        $application = ObserverApplication::findOrFail($id);

        return view('observers.apply-success', compact('application'));
    }

    public function verifyAccreditation($token)
    {
        $application = ObserverApplication::where('verification_token', $token)->first();

        return view('observers.verify-accreditation', ['application' => $application, 'token' => $token]);
    }
}