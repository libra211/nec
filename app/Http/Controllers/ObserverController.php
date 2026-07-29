<?php

namespace App\Http\Controllers;

use App\Models\ObserverApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function apply()
    {
        abort_unless(feature_enabled('public_feature_observers'), 404);
        return view('observers.apply');
    }

    public function applySubmit(Request $request)
    {
        $validated = $request->validate([
            'title'                      => 'nullable|in:Mr,Mrs,Ms,Dr,Prof,Hon,Other',
            'first_name'                 => 'required|string|max:100',
            'last_name'                  => 'required|string|max:100',
            'other_names'                => 'nullable|string|max:150',
            'gender'                     => 'required|in:male,female',
            'dob'                        => 'required|date|before:today',
            'nationality'                => 'required|string|max:100',
            'national_id'                => 'required|string|max:100',
            'email'                      => 'required|email|max:255',
            'phone'                      => 'required|string|min:9|max:20',
            'residential_address'        => 'nullable|string|max:500',
            'postal_address'             => 'nullable|string|max:255',
            'emergency_contact_name'     => 'nullable|string|max:200',
            'emergency_contact_phone'    => 'nullable|string|min:9|max:20',
            'employer'                   => 'nullable|string|max:255',
            'job_title'                  => 'nullable|string|max:255',
            'employment_duration'        => 'nullable|string|max:100',
            'languages'                  => 'nullable|string|max:500',
            'observer_type'              => 'required|in:domestic,international,regional',
            'organization_name'          => 'nullable|string|max:255',
            'organization_registration'  => 'nullable|string|max:100',
            'org_address'                => 'nullable|string|max:500',
            'sponsoring_org'             => 'nullable|string|max:255',
            'observer_count'             => 'nullable|integer|min:1|max:100',
            'deployment_areas'           => 'nullable|string|max:500',
            'previous_missions'          => 'nullable|string|max:1000',
            'election_experience'        => 'nullable|string|max:1000',
            'passport_photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv_biography'               => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'letter_of_appointment'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'proof_registration'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'agree_code'                 => 'accepted',
        ]);

        $data = collect($validated)->except([
            'passport_photo', 'cv_biography', 'letter_of_appointment', 'proof_registration', 'agree_code',
        ])->toArray();

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

        $application = ObserverApplication::create($data);

        return redirect()->route('observers.apply.success', $application->id)
            ->with('success', 'Application submitted successfully.');
    }

    public function applySuccess($id)
    {
        $application = ObserverApplication::findOrFail($id);

        return view('observers.apply-success', compact('application'));
    }
}
