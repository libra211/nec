<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Constituency;
use App\Models\ElectionEvent;
use Illuminate\Http\Request;

class AdminResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Result::with(['constituency', 'electionEvent']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('election_name', 'LIKE', "%{$search}%")
                  ->orWhere('election_type', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $results = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.results.index', compact('results'));
    }

    public function create()
    {
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();
        $electionEvents = ElectionEvent::orderByDesc('start_date')->get();

        return view('admin.results.create', compact('constituencies', 'electionEvents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_name' => 'required|string|max:255',
            'election_type' => 'required|string|max:100',
            'constituency_id' => 'nullable|exists:nec_constituencies,id',
            'total_votes' => 'nullable|integer|min:0',
            'registered_voters' => 'nullable|integer|min:0',
            'turnout' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,trash',
        ]);

        Result::create($validated);

        return redirect()->route('admin.results.index')->with('success', 'Result created.');
    }

    public function edit($id)
    {
        $result = Result::findOrFail($id);
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();
        $electionEvents = ElectionEvent::orderByDesc('start_date')->get();

        return view('admin.results.edit', compact('result', 'constituencies', 'electionEvents'));
    }

    public function update(Request $request, $id)
    {
        $result = Result::findOrFail($id);

        $validated = $request->validate([
            'election_name' => 'required|string|max:255',
            'election_type' => 'required|string|max:100',
            'constituency_id' => 'nullable|exists:nec_constituencies,id',
            'total_votes' => 'nullable|integer|min:0',
            'registered_voters' => 'nullable|integer|min:0',
            'turnout' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $result->update($validated);

        return redirect()->route('admin.results.index')->with('success', 'Result updated.');
    }

    public function destroy($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();

        return redirect()->route('admin.results.index')->with('success', 'Result deleted.');
    }
}
