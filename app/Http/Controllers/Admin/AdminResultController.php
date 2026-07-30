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

        if ($electionType = $request->input('election_type')) {
            $query->where('election_type', $electionType);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $results = $query->orderByDesc('created_at')->paginate(15);

        $stats = [
            'total' => Result::count(),
            'active' => Result::where('status', 'active')->count(),
            'total_votes' => Result::sum('total_votes'),
            'total_registered' => Result::sum('registered_voters'),
            'avg_turnout' => Result::where('status', 'active')->avg('turnout'),
        ];

        return view('admin.results.index', compact('results', 'stats'));
    }

    public function show($id)
    {
        $result = Result::with(['constituency', 'electionEvent', 'candidateResults'])->findOrFail($id);

        return view('admin.results.show', compact('result'));
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

        if ($request->filled('total_votes') && $request->filled('registered_voters') && $request->registered_voters > 0 && !$request->filled('turnout')) {
            $validated['turnout'] = round(($request->total_votes / $request->registered_voters) * 100, 2);
        }

        $result = Result::create($validated);

        $this->logActivity('result_created', "Created result: {$result->election_name}", $result);

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

        if ($request->filled('total_votes') && $request->filled('registered_voters') && $request->registered_voters > 0 && !$request->filled('turnout')) {
            $validated['turnout'] = round(($request->total_votes / $request->registered_voters) * 100, 2);
        }

        $result->update($validated);

        $this->logActivity('result_updated', "Updated result: {$result->election_name}", $result);

        return redirect()->route('admin.results.index')->with('success', 'Result updated.');
    }

    public function toggleStatus($id)
    {
        $result = Result::findOrFail($id);
        $result->status = $result->status === 'active' ? 'inactive' : 'active';
        $result->save();

        $this->logActivity('result_status_changed', "Changed result {$result->election_name} status to {$result->status}", $result);

        return response()->json([
            'status' => $result->status,
            'label' => ucfirst($result->status),
        ]);
    }

    public function destroy($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();

        $this->logActivity('result_deleted', "Deleted result: {$result->election_name}", $result);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Result deleted.']);
        }

        return redirect()->route('admin.results.index')->with('success', 'Result deleted.');
    }
}
