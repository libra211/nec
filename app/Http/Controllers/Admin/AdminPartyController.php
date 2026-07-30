<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoliticalParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPartyController extends Controller
{
    public function index(Request $request)
    {
        $query = PoliticalParty::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('acronym', 'LIKE', "%{$search}%")
                  ->orWhere('leader', 'LIKE', "%{$search}%");
            });
        }

        $parties = $query->orderBy('name')->paginate(15);

        $stats = [
            'total' => PoliticalParty::count(),
            'active' => PoliticalParty::where('status', 1)->count(),
            'with_candidates' => PoliticalParty::whereHas('candidates')->count(),
            'new_this_year' => PoliticalParty::whereYear('created_at', date('Y'))->count(),
        ];

        return view('admin.parties.index', compact('parties', 'stats'));
    }

    public function create()
    {
        return view('admin.parties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'nullable|string|max:20',
            'leader' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'color' => 'nullable|string|max:7',
            'status' => 'required|boolean',
            'founded' => 'nullable|integer|min:1900|max:' . date('Y'),
            'registration_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('parties/logos', 'public');
        }

        if ($request->hasFile('registration_document')) {
            $validated['registration_document'] = $request->file('registration_document')->store('parties/documents', 'public');
        }

        $party = PoliticalParty::create($validated);

        $this->logActivity('party_created', "Created party: {$party->name}", $party);

        return redirect()->route('admin.parties.index')->with('success', 'Party created.');
    }

    public function show($id)
    {
        $party = PoliticalParty::withCount('candidates')->findOrFail($id);
        $candidates = $party->candidates()->limit(10)->get();

        return view('admin.parties.show', compact('party', 'candidates'));
    }

    public function edit($id)
    {
        $party = PoliticalParty::findOrFail($id);

        return view('admin.parties.edit', compact('party'));
    }

    public function update(Request $request, $id)
    {
        $party = PoliticalParty::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'nullable|string|max:20',
            'leader' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'color' => 'nullable|string|max:7',
            'status' => 'required|boolean',
            'founded' => 'nullable|integer|min:1900|max:' . date('Y'),
            'registration_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            if ($party->logo) {
                Storage::disk('public')->delete($party->logo);
            }
            $validated['logo'] = $request->file('logo')->store('parties/logos', 'public');
        } elseif ($request->input('remove_logo') === '1') {
            if ($party->logo) {
                Storage::disk('public')->delete($party->logo);
            }
            $validated['logo'] = null;
        }

        if ($request->hasFile('registration_document')) {
            if ($party->registration_document) {
                Storage::disk('public')->delete($party->registration_document);
            }
            $validated['registration_document'] = $request->file('registration_document')->store('parties/documents', 'public');
        }

        $party->update($validated);

        $this->logActivity('party_updated', "Updated party: {$party->name}", $party);

        return redirect()->route('admin.parties.index')->with('success', 'Party updated.');
    }

    public function toggleStatus($id)
    {
        $party = PoliticalParty::findOrFail($id);
        $party->status = $party->status ? 0 : 1;
        $party->save();

        $this->logActivity('party_status_changed', "Changed party {$party->name} status to " . ($party->status ? 'active' : 'inactive'), $party);

        return response()->json([
            'status' => $party->status,
            'label' => $party->status ? 'Active' : 'Inactive',
        ]);
    }

    public function destroy($id)
    {
        $party = PoliticalParty::findOrFail($id);

        if ($party->logo) {
            Storage::disk('public')->delete($party->logo);
        }
        if ($party->registration_document) {
            Storage::disk('public')->delete($party->registration_document);
        }

        $party->delete();

        $this->logActivity('party_deleted', "Deleted party: {$party->name}", $party);

        return redirect()->route('admin.parties.index')->with('success', 'Party deleted.');
    }

    public function trashed(Request $request)
    {
        $query = PoliticalParty::onlyTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $parties = $query->orderByDesc('deleted_at')->paginate(20);

        return view('admin.parties.trashed', compact('parties'));
    }

    public function restore($id)
    {
        $party = PoliticalParty::onlyTrashed()->findOrFail($id);
        $party->update(['deleted_at' => null, 'updated_at' => now()]);

        $this->logActivity('party_restored', "Restored party: {$party->name}", $party);

        return redirect()->route('admin.parties.index')->with('success', 'Party restored successfully.');
    }

    public function forceDelete($id)
    {
        $party = PoliticalParty::onlyTrashed()->findOrFail($id);

        if ($party->logo) {
            Storage::disk('public')->delete($party->logo);
        }
        if ($party->registration_document) {
            Storage::disk('public')->delete($party->registration_document);
        }

        $party->forceDelete();

        $this->logActivity('party_force_deleted', "Permanently deleted party: {$party->name}", $party);

        return redirect()->route('admin.parties.trashed')->with('success', 'Party permanently deleted.');
    }
}
