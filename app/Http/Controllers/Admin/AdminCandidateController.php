<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Constituency;
use App\Models\PoliticalParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::with(['politicalParty']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%");
            });
        }

        if ($partyId = $request->input('party_id')) {
            $query->where('party_id', $partyId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $candidates = $query->orderByDesc('created_at')->paginate(15);
        $parties = PoliticalParty::where('status', 1)->orderBy('name')->get();

        $stats = [
            'total' => Candidate::count(),
            'active' => Candidate::where('status', 'active')->count(),
            'parties' => Candidate::distinct('party_id')->count('party_id'),
        ];

        return view('admin.candidates.index', compact('candidates', 'parties', 'stats'));
    }

    public function create()
    {
        $parties = PoliticalParty::where('status', 1)->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();
        $states = \App\Models\State::orderBy('name')->get();
        $regions = \App\Models\Region::where('status', 'active')->orderBy('sort_order')->get();

        $geoData = [
            'states' => \App\Models\State::where('status', 'active')->get(['id', 'name', 'region_id']),
            'counties' => \App\Models\County::where('status', 'active')->get(['id', 'name', 'state_id']),
            'constituencies' => Constituency::where('status', 'active')->get(['id', 'name', 'county_id']),
        ];

        return view('admin.candidates.create', compact('parties', 'constituencies', 'states', 'regions', 'geoData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_id' => 'nullable|exists:nec_political_parties,id',
            'position' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('candidates/photos', 'public');
        }

        $candidate = Candidate::create($validated);

        $this->logActivity('candidate_created', "Created candidate: {$candidate->name}", $candidate);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate created.');
    }

    public function show($id)
    {
        $candidate = Candidate::with(['politicalParty'])->findOrFail($id);

        return view('admin.candidates.show', compact('candidate'));
    }

    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        $parties = PoliticalParty::where('status', 1)->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();
        $states = \App\Models\State::orderBy('name')->get();
        $regions = \App\Models\Region::where('status', 'active')->orderBy('sort_order')->get();

        $geoData = [
            'states' => \App\Models\State::where('status', 'active')->get(['id', 'name', 'region_id']),
            'counties' => \App\Models\County::where('status', 'active')->get(['id', 'name', 'state_id']),
            'constituencies' => Constituency::where('status', 'active')->get(['id', 'name', 'county_id']),
        ];

        $candidateRegionId = null;
        $candidateCountyId = null;
        if ($candidate->state) {
            $stateObj = \App\Models\State::where('name', $candidate->state)->first();
            if ($stateObj) {
                $candidateRegionId = $stateObj->region_id;
            }
        }
        if ($candidate->constituency) {
            $constituencyObj = Constituency::where('name', $candidate->constituency)->first();
            if ($constituencyObj) {
                $candidateCountyId = $constituencyObj->county_id;
            }
        }

        return view('admin.candidates.edit', compact('candidate', 'parties', 'constituencies', 'states', 'regions', 'geoData', 'candidateRegionId', 'candidateCountyId'));
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_id' => 'nullable|exists:nec_political_parties,id',
            'position' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        if ($request->hasFile('photo')) {
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $validated['photo'] = $request->file('photo')->store('candidates/photos', 'public');
        } elseif ($request->input('remove_photo') === '1') {
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $validated['photo'] = null;
        }

        $candidate->update($validated);

        $this->logActivity('candidate_updated', "Updated candidate: {$candidate->name}", $candidate);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate updated.');
    }

    public function toggleStatus($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->status = $candidate->status === 'active' ? 'inactive' : 'active';
        $candidate->save();

        $this->logActivity('candidate_status_changed', "Changed candidate {$candidate->name} status to {$candidate->status}", $candidate);

        return response()->json([
            'status' => $candidate->status,
            'label' => ucfirst($candidate->status),
        ]);
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);

        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        $this->logActivity('candidate_deleted', "Deleted candidate: {$candidate->name}", $candidate);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted.');
    }

    public function trashed(Request $request)
    {
        $query = Candidate::onlyTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%");
            });
        }

        $candidates = $query->orderByDesc('deleted_at')->paginate(20);

        return view('admin.candidates.trashed', compact('candidates'));
    }

    public function restore($id)
    {
        $candidate = Candidate::onlyTrashed()->findOrFail($id);
        $candidate->update(['deleted_at' => null, 'updated_at' => now()]);

        $this->logActivity('candidate_restored', "Restored candidate: {$candidate->name}", $candidate);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate restored successfully.');
    }

    public function forceDelete($id)
    {
        $candidate = Candidate::onlyTrashed()->findOrFail($id);

        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->forceDelete();

        $this->logActivity('candidate_force_deleted', "Permanently deleted candidate: {$candidate->name}", $candidate);

        return redirect()->route('admin.candidates.trashed')->with('success', 'Candidate permanently deleted.');
    }
}
