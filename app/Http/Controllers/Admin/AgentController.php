<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = Agent::withCount(['registeredVoters']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($state = $request->input('state')) {
            $query->where('assigned_state', $state);
        }

        $agents = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Agent::count(),
            'active' => Agent::where('status', 'active')->count(),
            'inactive' => Agent::where('status', 'inactive')->count(),
            'suspended' => Agent::where('status', 'suspended')->count(),
            'total_voters' => Agent::sum('voters_registered'),
        ];

        return view('admin.agents.index', compact('agents', 'stats'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50|unique:nec_agents,phone',
            'email' => 'nullable|email|unique:nec_agents,email',
            'national_id' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:255',
            'state' => 'required|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:255',
            'payam' => 'nullable|string|max:100',
            'boma' => 'nullable|string|max:100',
            'assigned_state' => 'nullable|string|max:100',
            'assigned_county' => 'nullable|string|max:100',
            'assigned_constituency' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['voters_registered'] = 0;

        $agent = Agent::create($validated);
        $this->logActivity('agent_created', "Created registration agent: {$agent->full_name}", $agent);

        return redirect()->route('admin.agents.index')->with('success', 'Registration agent created successfully.');
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => "required|string|max:50|unique:nec_agents,phone,{$agent->id}",
            'email' => "nullable|email|unique:nec_agents,email,{$agent->id}",
            'national_id' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:255',
            'state' => 'required|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:255',
            'payam' => 'nullable|string|max:100',
            'boma' => 'nullable|string|max:100',
            'assigned_state' => 'nullable|string|max:100',
            'assigned_county' => 'nullable|string|max:100',
            'assigned_constituency' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $agent->update($validated);
        $this->logActivity('agent_updated', "Updated registration agent: {$agent->full_name}", $agent);

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        $this->logActivity('agent_deleted', "Deleted registration agent: {$agent->full_name}", $agent);

        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted.');
    }

    public function updateStatus(Agent $agent, Request $request)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended']);
        $agent->update(['status' => $request->input('status')]);
        $this->logActivity('agent_status_changed', "Agent {$agent->full_name} status -> {$request->input('status')}", $agent);

        return redirect()->back()->with('success', 'Agent status updated.');
    }

    public function voters(Agent $agent)
    {
        $voters = $agent->registeredVoters()
            ->latest('registered_at')
            ->paginate(20);

        return view('admin.agents.voters', compact('agent', 'voters'));
    }

    public function trashed(Request $request)
    {
        $query = Agent::onlyTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $agents = $query->orderByDesc('deleted_at')->paginate(20);

        return view('admin.agents.trashed', compact('agents'));
    }

    public function restore($id)
    {
        $agent = Agent::onlyTrashed()->findOrFail($id);
        $agent->update(['deleted_at' => null, 'updated_at' => now()]);

        $this->logActivity('agent_restored', "Restored agent: {$agent->full_name}", $agent);

        return redirect()->route('admin.agents.index')->with('success', 'Agent restored successfully.');
    }

    public function forceDelete($id)
    {
        $agent = Agent::onlyTrashed()->findOrFail($id);
        $agent->forceDelete();

        $this->logActivity('agent_force_deleted', "Permanently deleted agent: {$agent->full_name}", $agent);

        return redirect()->route('admin.agents.trashed')->with('success', 'Agent permanently deleted.');
    }
}
