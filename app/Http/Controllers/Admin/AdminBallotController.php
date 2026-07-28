<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ballot;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminBallotController extends Controller
{
    public function index(Request $request)
    {
        $query = Ballot::query();

        if ($request->filled('status')) {
            $query->where('status', InputSanitizer::clean($request->input('status')));
        }
        if ($request->filled('election_type')) {
            $query->where('election_type', InputSanitizer::clean($request->input('election_type')));
        }
        if ($request->filled('state')) {
            $query->where('state', InputSanitizer::clean($request->input('state')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('election_name', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%")
                  ->orWhere('printer', 'LIKE', "%{$search}%");
            });
        }

        $ballots = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.ballots.index', compact('ballots'));
    }

    public function create()
    {
        return view('admin.ballots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_name' => 'required|string|max:255',
            'election_type' => 'required|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'ballot_design' => 'nullable|string|max:5000',
            'total_printed' => 'nullable|integer|min:0',
            'serial_start' => 'nullable|string|max:50',
            'serial_end' => 'nullable|string|max:50',
            'printer' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'received_date' => 'nullable|date',
            'status' => 'required|in:planned,printing,delivered,deployed,archived',
            'notes' => 'nullable|string|max:2000',
        ]);

        Ballot::create(InputSanitizer::clean($validated));

        return redirect()->route('admin.ballots.index')->with('success', 'Ballot record created successfully.');
    }

    public function edit(Ballot $ballot)
    {
        return view('admin.ballots.edit', compact('ballot'));
    }

    public function update(Request $request, Ballot $ballot)
    {
        $validated = $request->validate([
            'election_name' => 'required|string|max:255',
            'election_type' => 'required|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'ballot_design' => 'nullable|string|max:5000',
            'total_printed' => 'nullable|integer|min:0',
            'serial_start' => 'nullable|string|max:50',
            'serial_end' => 'nullable|string|max:50',
            'printer' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'received_date' => 'nullable|date',
            'status' => 'required|in:planned,printing,delivered,deployed,archived',
            'notes' => 'nullable|string|max:2000',
        ]);

        $ballot->update(InputSanitizer::clean($validated));

        return redirect()->route('admin.ballots.index')->with('success', 'Ballot record updated successfully.');
    }

    public function destroy(Ballot $ballot)
    {
        $ballot->delete();
        return back()->with('success', 'Ballot record deleted.');
    }
}
