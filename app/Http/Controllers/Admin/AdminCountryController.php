<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DiasporaMission;
use App\Models\Voter;
use Illuminate\Http\Request;

class AdminCountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();
        $status = $request->input('status');
        $continent = $request->input('continent');

        if ($status === 'active') $query->where('status', 'active');
        elseif ($status === 'inactive') $query->where('status', 'inactive');

        if ($continent) $query->where('continent', $continent);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('iso3', 'LIKE', "%{$search}%")
                  ->orWhere('nationality', 'LIKE', "%{$search}%");
            });
        }

        $countries = $query->orderBy('name')->paginate(50)->withQueryString();

        $counts = [
            'all' => Country::count(),
            'active' => Country::where('status', 'active')->count(),
            'inactive' => Country::where('status', 'inactive')->count(),
        ];

        $continents = Country::whereNotNull('continent')->distinct()->orderBy('continent')->pluck('continent');

        return view('admin.countries.index', compact('countries', 'counts', 'status', 'continent', 'continents'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:nec_countries,name',
            'code' => 'required|string|size:2|unique:nec_countries,code',
            'iso3' => 'nullable|string|size:3',
            'nationality' => 'nullable|string|max:120',
            'continent' => 'nullable|string|max:60',
            'calling_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $country = Country::create($validated);

        $this->logActivity('country_created', "Created country: {$country->name}", $country);

        return redirect()->route('admin.countries.index')
            ->with('success', "Country {$country->name} created.");
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:nec_countries,name,' . $country->id,
            'code' => 'required|string|size:2|unique:nec_countries,code,' . $country->id,
            'iso3' => 'nullable|string|size:3',
            'nationality' => 'nullable|string|max:120',
            'continent' => 'nullable|string|max:60',
            'calling_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $country->update($validated);

        $this->logActivity('country_updated', "Updated country: {$country->name}", $country);

        return redirect()->route('admin.countries.index')
            ->with('success', "Country {$country->name} updated.");
    }

    public function toggleStatus($id)
    {
        $country = Country::findOrFail($id);
        $country->status = $country->status === 'active' ? 'inactive' : 'active';
        $country->save();

        $this->logActivity('country_status_changed', "Changed country {$country->name} status to {$country->status}", $country);

        return back()->with('success', "Country {$country->name} status updated.");
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        $inUse = Voter::where('country_id', $id)->exists()
            || DiasporaMission::where('country_id', $id)->exists();

        if ($inUse) {
            $country->update(['status' => 'inactive']);
            $this->logActivity('country_deactivated', "Country in use, deactivated: {$country->name}", $country);
            return back()->with('warning', "{$country->name} is referenced by voters/missions and was deactivated instead of deleted.");
        }

        $this->logActivity('country_deleted', "Deleted country: {$country->name}", $country);
        $country->delete();

        return back()->with('success', "Country {$country->name} deleted.");
    }
}