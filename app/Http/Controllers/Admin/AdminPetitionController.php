<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPetition;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminPetitionController extends Controller
{
    public function index(Request $request)
    {
        $query = ElectionPetition::query();

        if ($request->filled('status')) {
            $query->where('status', InputSanitizer::clean($request->input('status')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('petitioner_name', 'LIKE', "%{$search}%")
                  ->orWhere('respondent_name', 'LIKE', "%{$search}%")
                  ->orWhere('petition_number', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%");
            });
        }

        $petitions = $query->latest('filing_date')->paginate(20)->appends($request->query());

        return view('admin.petitions.index', compact('petitions'));
    }

    public function create()
    {
        return view('admin.petitions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'petition_number' => 'required|string|max:50|unique:nec_election_petitions,petition_number',
            'petitioner_name' => 'required|string|max:255',
            'respondent_name' => 'required|string|max:255',
            'election_name' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:100',
            'filing_date' => 'required|date',
            'grounds' => 'required|string|max:5000',
            'relief_sought' => 'nullable|string|max:5000',
            'court_name' => 'nullable|string|max:255',
            'case_number' => 'nullable|string|max:100',
            'verdict' => 'nullable|string|max:5000',
            'verdict_date' => 'nullable|date',
            'status' => 'required|in:filed,hearing,decided,dismissed,withdrawn',
        ]);

        $petition = ElectionPetition::create(InputSanitizer::clean($validated));

        $this->logActivity('petition_created', "Created petition: {$petition->petition_number}", $petition);

        return redirect()->route('admin.petitions.index')->with('success', 'Election petition filed successfully.');
    }

    public function edit(ElectionPetition $petition)
    {
        return view('admin.petitions.edit', compact('petition'));
    }

    public function update(Request $request, ElectionPetition $petition)
    {
        $validated = $request->validate([
            'petition_number' => 'required|string|max:50',
            'petitioner_name' => 'required|string|max:255',
            'respondent_name' => 'required|string|max:255',
            'election_name' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:100',
            'filing_date' => 'required|date',
            'grounds' => 'required|string|max:5000',
            'relief_sought' => 'nullable|string|max:5000',
            'court_name' => 'nullable|string|max:255',
            'case_number' => 'nullable|string|max:100',
            'verdict' => 'nullable|string|max:5000',
            'verdict_date' => 'nullable|date',
            'status' => 'required|in:filed,hearing,decided,dismissed,withdrawn',
        ]);

        $petition->update(InputSanitizer::clean($validated));

        $this->logActivity('petition_updated', "Updated petition: {$petition->petition_number}", $petition);

        return redirect()->route('admin.petitions.index')->with('success', 'Petition updated successfully.');
    }

    public function destroy(ElectionPetition $petition)
    {
        $petition->delete();
        $this->logActivity('petition_deleted', "Deleted petition: {$petition->petition_number}", $petition);
        return back()->with('success', 'Petition record deleted.');
    }
}
