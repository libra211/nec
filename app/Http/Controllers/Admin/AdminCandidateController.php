<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Constituency;
use App\Models\PoliticalParty;
use Illuminate\Http\Request;

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

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $candidates = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        $parties = PoliticalParty::where('status', 'active')->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.candidates.create', compact('parties', 'constituencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_id' => 'nullable|exists:nec_political_parties,id',
            'position' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'photo' => 'nullable|string|max:500',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        Candidate::create($validated);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate created.');
    }

    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        $parties = PoliticalParty::where('status', 'active')->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.candidates.edit', compact('candidate', 'parties', 'constituencies'));
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
            'photo' => 'nullable|string|max:500',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $candidate->update($validated);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate updated.');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted.');
    }
}
