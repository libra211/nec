<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Constituency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminConstituencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Constituency::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('state', 'LIKE', "%{$search}%")
                  ->orWhere('county', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        $constituencies = $query->orderByDesc('created_at')->paginate(15);

        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->pluck('name');

        return view('admin.constituencies.index', compact('constituencies', 'states'));
    }

    public function create()
    {
        return view('admin.constituencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:nec_constituencies,code',
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $constituency = Constituency::create($validated);

        $this->logActivity('constituency_created', "Created constituency: {$constituency->name}", $constituency);

        return redirect()->route('admin.constituencies.index')->with('success', 'Constituency created.');
    }

    public function edit($id)
    {
        $constituency = Constituency::findOrFail($id);

        return view('admin.constituencies.edit', compact('constituency'));
    }

    public function update(Request $request, $id)
    {
        $constituency = Constituency::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:nec_constituencies,code,' . $id,
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $constituency->update($validated);

        $this->logActivity('constituency_updated', "Updated constituency: {$constituency->name}", $constituency);

        return redirect()->route('admin.constituencies.index')->with('success', 'Constituency updated.');
    }

    public function destroy($id)
    {
        $constituency = Constituency::findOrFail($id);
        $constituency->delete();

        $this->logActivity('constituency_deleted', "Deleted constituency: {$constituency->name}", $constituency);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Constituency deleted.']);
        }

        return redirect()->route('admin.constituencies.index')->with('success', 'Constituency deleted.');
    }
}
