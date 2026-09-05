<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DiasporaMission;
use App\Models\Voter;
use Illuminate\Http\Request;

class AdminDiasporaMissionController extends Controller
{
    public function index(Request $request)
    {
        $query = DiasporaMission::query();
        $status = $request->input('status');
        $countryId = $request->input('country_id');

        if ($status === 'active') $query->where('status', 'active');
        elseif ($status === 'inactive') $query->where('status', 'inactive');
        elseif ($status === 'trash') $query->onlyTrashed();

        if ($countryId) $query->where('country_id', $countryId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $missions = $query->with('country')->orderBy('name')->paginate(50)->withQueryString();

        $counts = [
            'all' => DiasporaMission::count(),
            'active' => DiasporaMission::where('status', 'active')->count(),
            'inactive' => DiasporaMission::where('status', 'inactive')->count(),
            'trash' => DiasporaMission::onlyTrashed()->count(),
        ];

        $countries = Country::where('status', 'active')->orderBy('name')->get();

        return view('admin.diaspora-missions.index', compact('missions', 'counts', 'status', 'countryId', 'countries'));
    }

    public function create()
    {
        $countries = Country::where('status', 'active')->orderBy('name')->get();
        return view('admin.diaspora-missions.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:nec_countries,id',
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:20|unique:nec_diaspora_missions,code',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:active,inactive',
        ]);

        $mission = DiasporaMission::create($validated);

        $this->logActivity('mission_created', "Created diaspora mission: {$mission->name}", $mission);

        return redirect()->route('admin.diaspora-missions.index')
            ->with('success', "Mission {$mission->name} created.");
    }

    public function edit($id)
    {
        $mission = DiasporaMission::findOrFail($id);
        $countries = Country::where('status', 'active')->orderBy('name')->get();
        return view('admin.diaspora-missions.edit', compact('mission', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $mission = DiasporaMission::findOrFail($id);

        $validated = $request->validate([
            'country_id' => 'required|exists:nec_countries,id',
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:20|unique:nec_diaspora_missions,code,' . $mission->id,
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:active,inactive',
        ]);

        $mission->update($validated);

        $this->logActivity('mission_updated', "Updated diaspora mission: {$mission->name}", $mission);

        return redirect()->route('admin.diaspora-missions.index')
            ->with('success', "Mission {$mission->name} updated.");
    }

    public function toggleStatus($id)
    {
        $mission = DiasporaMission::findOrFail($id);
        $mission->status = $mission->status === 'active' ? 'inactive' : 'active';
        $mission->save();

        $this->logActivity('mission_status_changed', "Changed mission {$mission->name} status to {$mission->status}", $mission);

        return back()->with('success', "Mission {$mission->name} status updated.");
    }

    public function destroy($id)
    {
        $mission = DiasporaMission::findOrFail($id);

        $inUse = Voter::where('diaspora_mission_id', $id)->exists();

        if ($inUse) {
            $mission->update(['status' => 'inactive']);
            $this->logActivity('mission_deactivated', "Mission in use, deactivated: {$mission->name}", $mission);
            return back()->with('warning', "{$mission->name} has registered voters and was deactivated instead of deleted.");
        }

        $this->logActivity('mission_deleted', "Deleted diaspora mission: {$mission->name}", $mission);
        $mission->delete();

        return back()->with('success', "Mission {$mission->name} deleted.");
    }

    public function restore($id)
    {
        $mission = DiasporaMission::onlyTrashed()->findOrFail($id);
        $mission->status = 'active';
        $mission->save();
        $mission->restore();

        $this->logActivity('mission_restored', "Restored diaspora mission: {$mission->name}", $mission);

        return redirect()->route('admin.diaspora-missions.index')
            ->with('success', "Mission {$mission->name} restored.");
    }
}