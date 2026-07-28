<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoliticalParty;
use Illuminate\Http\Request;

class AdminPartyController extends Controller
{
    public function index(Request $request)
    {
        $query = PoliticalParty::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $parties = $query->orderBy('name')->paginate(15);

        return view('admin.parties.index', compact('parties'));
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
            'logo' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'status' => 'required|in:active,inactive,trash',
        ]);

        PoliticalParty::create($validated);

        return redirect()->route('admin.parties.index')->with('success', 'Party created.');
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
            'logo' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $party->update($validated);

        return redirect()->route('admin.parties.index')->with('success', 'Party updated.');
    }

    public function destroy($id)
    {
        $party = PoliticalParty::findOrFail($id);
        $party->delete();

        return redirect()->route('admin.parties.index')->with('success', 'Party deleted.');
    }
}
