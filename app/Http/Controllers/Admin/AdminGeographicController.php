<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boma;
use App\Models\County;
use App\Models\Constituency;
use App\Models\Payam;
use App\Models\PollingStation;
use App\Models\Region;
use App\Models\State;
use Illuminate\Http\Request;

class AdminGeographicController extends Controller
{
    public function index()
    {
        $regions = Region::withCount(['states' => function ($q) {
            $q->withCount(['counties', 'constituencies', 'pollingStations']);
        }])->get();

        $totals = [
            'regions' => $regions->count(),
            'states' => State::states()->count(),
            'admin_areas' => State::adminAreas()->count(),
            'counties' => County::count(),
            'constituencies' => Constituency::count(),
            'payams' => Payam::count(),
            'bomas' => Boma::count(),
            'polling_stations' => PollingStation::count(),
        ];

        return view('admin.geographic.index', compact('regions', 'totals'));
    }

    public function overview()
    {
        $states = State::states()->withCount(['counties', 'constituencies', 'pollingStations', 'payams'])
            ->with('region')
            ->orderBy('name')
            ->get();
        $adminAreas = State::adminAreas()->withCount(['counties', 'constituencies', 'pollingStations', 'payams'])
            ->with('region')
            ->orderBy('name')
            ->get();

        $stateIds = $states->pluck('id');
        $countyIds = County::whereIn('state_id', $stateIds)->pluck('id', 'state_id');
        $payamIds = Payam::whereIn('county_id', $countyIds->values())->pluck('id');
        $bomaCountsPerState = Boma::whereIn('payam_id', $payamIds)
            ->join('nec_payams', 'nec_payams.id', '=', 'nec_bomas.payam_id')
            ->join('nec_counties', 'nec_counties.id', '=', 'nec_payams.county_id')
            ->selectRaw('nec_counties.state_id, COUNT(*) as total')
            ->groupBy('nec_counties.state_id')
            ->pluck('total', 'state_id');

        $states->each(function ($state) use ($bomaCountsPerState) {
            $state->bomas_count = $bomaCountsPerState[$state->id] ?? 0;
        });

        $totals = [
            'states' => $states->count(),
            'admin_areas' => $adminAreas->count(),
            'counties' => County::count(),
            'constituencies' => Constituency::count(),
            'payams' => Payam::count(),
            'bomas' => Boma::count(),
            'polling_stations' => PollingStation::count(),
            'registered_voters' => PollingStation::sum('registered_voters'),
        ];

        return view('admin.geographic.overview', compact('states', 'adminAreas', 'totals'));
    }

    public function state($id)
    {
        $state = State::withCount(['counties', 'constituencies', 'pollingStations', 'payams'])
            ->with('region')
            ->findOrFail($id);

        $state->bomas_count = Boma::whereIn('payam_id', Payam::whereIn('county_id', County::where('state_id', $id)->pluck('id'))->pluck('id'))->count();

        $counties = County::where('state_id', $id)
            ->withCount(['constituencies', 'pollingStations'])
            ->orderBy('name')
            ->get();

        $constituencies = Constituency::whereHas('county', function ($q) use ($id) {
            $q->where('state_id', $id);
        })
            ->withCount('pollingStations')
            ->orderBy('name')
            ->get();

        $pollingStations = PollingStation::whereHas('constituency.county', function ($q) use ($id) {
            $q->where('state_id', $id);
        })
            ->with('constituency')
            ->orderBy('name')
            ->paginate(20);

        $totals = [
            'counties' => $counties->count(),
            'constituencies' => $constituencies->count(),
            'polling_stations' => $pollingStations->total(),
            'payams' => Payam::whereIn('county_id', County::where('state_id', $id)->pluck('id'))->count(),
            'bomas' => Boma::whereIn('payam_id', Payam::whereIn('county_id', County::where('state_id', $id)->pluck('id'))->pluck('id'))->count(),
            'registered_voters' => PollingStation::whereHas('constituency.county', function ($q) use ($id) {
                $q->where('state_id', $id);
            })->sum('registered_voters'),
        ];

        return view('admin.geographic.state', compact('state', 'counties', 'constituencies', 'pollingStations', 'totals'));
    }

    public function storeState(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:states,code',
            'capital' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        State::create($validated);

        return redirect()->route('admin.geographic.index')->with('success', 'State created successfully.');
    }

    public function updateState(Request $request, $id)
    {
        $state = State::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capital' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $state->update($validated);

        return redirect()->back()->with('success', 'State updated successfully.');
    }

    public function destroyState($id)
    {
        $state = State::findOrFail($id);

        if ($state->counties()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete state with existing counties.');
        }

        $state->delete();

        return redirect()->route('admin.geographic.index')->with('success', 'State deleted successfully.');
    }

    public function storeCounty(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'capital' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'registered_voters' => 'nullable|integer|min:0',
        ]);

        County::create($validated);

        return redirect()->back()->with('success', 'County created successfully.');
    }

    public function updateCounty(Request $request, $id)
    {
        $county = County::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capital' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'registered_voters' => 'nullable|integer|min:0',
        ]);

        $county->update($validated);

        return redirect()->back()->with('success', 'County updated successfully.');
    }

    public function destroyCounty($id)
    {
        $county = County::findOrFail($id);

        if ($county->constituencies()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete county with existing constituencies.');
        }

        $county->delete();

        return redirect()->back()->with('success', 'County deleted successfully.');
    }

    public function storeConstituency(Request $request)
    {
        $validated = $request->validate([
            'county_id' => 'required|exists:counties,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Constituency::create($validated);

        return redirect()->back()->with('success', 'Constituency created successfully.');
    }

    public function updateConstituency(Request $request, $id)
    {
        $constituency = Constituency::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $constituency->update($validated);

        return redirect()->back()->with('success', 'Constituency updated successfully.');
    }

    public function destroyConstituency($id)
    {
        $constituency = Constituency::findOrFail($id);

        if ($constituency->pollingStations()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete constituency with existing polling stations.');
        }

        $constituency->delete();

        return redirect()->back()->with('success', 'Constituency deleted successfully.');
    }

    public function storePayam(Request $request)
    {
        $validated = $request->validate([
            'boma_id' => 'required|exists:bomas,id',
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Payam::create($validated);

        return redirect()->back()->with('success', 'Payam created successfully.');
    }

    public function updatePayam(Request $request, $id)
    {
        $payam = Payam::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $payam->update($validated);

        return redirect()->back()->with('success', 'Payam updated successfully.');
    }

    public function destroyPayam($id)
    {
        $payam = Payam::findOrFail($id);
        $payam->delete();

        return redirect()->back()->with('success', 'Payam deleted successfully.');
    }

    public function storeBoma(Request $request)
    {
        $validated = $request->validate([
            'county_id' => 'required|exists:counties,id',
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Boma::create($validated);

        return redirect()->back()->with('success', 'Boma created successfully.');
    }

    public function updateBoma(Request $request, $id)
    {
        $boma = Boma::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $boma->update($validated);

        return redirect()->back()->with('success', 'Boma updated successfully.');
    }

    public function destroyBoma($id)
    {
        $boma = Boma::findOrFail($id);

        if ($boma->payams()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete boma with existing payams.');
        }

        $boma->delete();

        return redirect()->back()->with('success', 'Boma deleted successfully.');
    }
}
