<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Boma;
use App\Models\County;
use App\Models\Payam;
use App\Models\PollingStation;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeographicController extends Controller
{
    public function states(Request $request): JsonResponse
    {
        $query = State::where('status', 'active')->orderBy('name');

        if ($request->has('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $states = $query->get(['id', 'name', 'code', 'capital', 'region_id']);

        return response()->json($states);
    }

    public function counties(Request $request): JsonResponse
    {
        if (!$request->has('state_id')) {
            return response()->json(['error' => 'state_id is required'], 422);
        }

        $counties = County::where('state_id', $request->state_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($counties);
    }

    public function constituencies(Request $request): JsonResponse
    {
        $query = DB::table('nec_constituencies')->where('status', 'active');

        if ($request->has('state')) {
            $state = $request->input('state');
            $query->where(function ($q) use ($state) {
                $q->where('state', $state)
                    ->orWhere('state', DB::raw("(SELECT name FROM nec_states WHERE code = '$state' OR name = '$state' LIMIT 1)"));
            });
        }

        if ($request->has('county')) {
            $county = $request->input('county');
            $query->where(function ($q) use ($county) {
                $q->where('county', $county)
                    ->orWhere('county', str_ireplace(' county', '', $county));
            });
        }

        $results = $query->pluck('name', 'id');

        return response()->json($results);
    }

    public function payams(Request $request): JsonResponse
    {
        if (!$request->has('county_id')) {
            return response()->json(['error' => 'county_id is required'], 422);
        }

        $results = Payam::where('county_id', $request->county_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($results);
    }

    public function bomas(Request $request): JsonResponse
    {
        if (!$request->has('payam_id')) {
            return response()->json(['error' => 'payam_id is required'], 422);
        }

        $results = Boma::where('payam_id', $request->payam_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($results);
    }

    public function pollingStations(Request $request): JsonResponse
    {
        $query = DB::table('nec_polling_stations')->where('status', 'active');

        if ($request->has('state_id')) {
            $stateId = $request->input('state_id');
            $state = State::where('id', $stateId)->first();
            if ($state) {
                $query->where('state', $state->name);
            }
        } elseif ($request->has('state')) {
            $state = $request->input('state');
            $query->where(function ($q) use ($state) {
                $q->where('state', $state)
                    ->orWhere('state', DB::raw("(SELECT name FROM nec_states WHERE code = '$state' OR name = '$state' LIMIT 1)"));
            });
        }

        if ($request->has('county')) {
            $county = $request->input('county');
            $query->where(function ($q) use ($county) {
                $q->where('county', $county)
                    ->orWhere('county', str_ireplace(' county', '', $county));
            });
        }

        $results = $query->get();

        return response()->json(['stations' => $results]);
    }

    public function stateDetail($id): JsonResponse
    {
        $state = State::where('id', $id)->where('status', 'active')->first();

        if (!$state) {
            return response()->json(['error' => 'State not found'], 404);
        }

        $countyCount = County::where('state_id', $id)->where('status', 'active')->count();
        $constituencyCount = DB::table('nec_constituencies')
            ->where('state', $state->name)
            ->where('status', 'active')
            ->count();
        $pollingStationCount = DB::table('nec_polling_stations')
            ->where('state', $state->name)
            ->where('status', 'active')
            ->count();
        $registeredVoters = DB::table('nec_polling_stations')
            ->where('state', $state->name)
            ->where('status', 'active')
            ->sum('registered_voters');
        $payamCount = DB::table('nec_payams')
            ->whereIn('county_id', fn($q) => $q->select('id')->from('nec_counties')->where('state_id', $id))
            ->where('status', 'active')
            ->count();
        $bomaCount = DB::table('nec_bomas')
            ->whereIn('payam_id', fn($q) => $q->select('id')->from('nec_payams')->whereIn('county_id', fn($q2) => $q2->select('id')->from('nec_counties')->where('state_id', $id)))
            ->where('status', 'active')
            ->count();

        $counties = County::where('state_id', $id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($county) use ($state) {
                $county->constituency_count = DB::table('nec_constituencies')
                    ->where('county_id', $county->id)
                    ->where('status', 'active')
                    ->count();
                $county->polling_station_count = DB::table('nec_polling_stations')
                    ->where('county', $county->name)
                    ->where('status', 'active')
                    ->count();
                $county->registered_voters = DB::table('nec_polling_stations')
                    ->where('county', $county->name)
                    ->where('status', 'active')
                    ->sum('registered_voters');
                return $county;
            });

        $constituencies = DB::table('nec_constituencies')
            ->where('state', $state->name)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $pollingStations = DB::table('nec_polling_stations')
            ->where('state', $state->name)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'county', 'constituency', 'payam', 'registered_voters', 'latitude', 'longitude']);

        return response()->json([
            'state' => $state,
            'stats' => [
                'counties' => $countyCount,
                'constituencies' => $constituencyCount,
                'polling_stations' => $pollingStationCount,
                'registered_voters' => $registeredVoters,
                'payams' => $payamCount,
                'bomas' => $bomaCount,
            ],
            'counties' => $counties,
            'constituencies' => $constituencies,
            'polling_stations' => $pollingStations,
        ]);
    }

    public function countyDetail($id): JsonResponse
    {
        $county = County::where('id', $id)->where('status', 'active')->first();

        if (!$county) {
            return response()->json(['error' => 'County not found'], 404);
        }

        $constituencies = DB::table('nec_constituencies')
            ->where('county_id', $county->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $payams = Payam::where('county_id', $county->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($payam) {
                $payam->boma_count = Boma::where('payam_id', $payam->id)->where('status', 'active')->count();
                return $payam;
            });

        $pollingStations = DB::table('nec_polling_stations')
            ->where('county', $county->name)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'county' => $county,
            'constituencies' => $constituencies,
            'payams' => $payams,
            'polling_stations' => $pollingStations,
        ]);
    }

    public function dashboard(): JsonResponse
    {
        $regionStats = DB::table('nec_regions')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($region) {
                $stateIds = DB::table('nec_states')->where('region_id', $region->id)->where('status', 'active')->pluck('id');
                $stateNames = DB::table('nec_states')->where('region_id', $region->id)->where('status', 'active')->pluck('name');
                $region->state_count = $stateIds->count();
                $region->county_count = DB::table('nec_counties')->whereIn('state_id', $stateIds)->where('status', 'active')->count();
                $region->constituency_count = DB::table('nec_constituencies')->whereIn('state', $stateNames)->where('status', 'active')->count();
                $region->polling_station_count = DB::table('nec_polling_stations')->whereIn('state', $stateNames)->where('status', 'active')->count();
                $region->registered_voters = DB::table('nec_polling_stations')->whereIn('state', $stateNames)->where('status', 'active')->sum('registered_voters');
                return $region;
            });

        $stateStats = DB::table('nec_states')
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($state) {
                $state->county_count = DB::table('nec_counties')->where('state_id', $state->id)->where('status', 'active')->count();
                $state->constituency_count = DB::table('nec_constituencies')->where('state', $state->name)->where('status', 'active')->count();
                $state->polling_station_count = DB::table('nec_polling_stations')->where('state', $state->name)->where('status', 'active')->count();
                $state->registered_voters = DB::table('nec_polling_stations')->where('state', $state->name)->where('status', 'active')->sum('registered_voters');
                return $state;
            });

        $totals = [
            'regions' => DB::table('nec_regions')->where('status', 'active')->count(),
            'states' => DB::table('nec_states')->where('status', 'active')->count(),
            'counties' => DB::table('nec_counties')->where('status', 'active')->count(),
            'constituencies' => DB::table('nec_constituencies')->where('status', 'active')->count(),
            'payams' => DB::table('nec_payams')->where('status', 'active')->count(),
            'bomas' => DB::table('nec_bomas')->where('status', 'active')->count(),
            'polling_stations' => DB::table('nec_polling_stations')->where('status', 'active')->count(),
            'registered_voters' => DB::table('nec_polling_stations')->where('status', 'active')->sum('registered_voters'),
        ];

        return response()->json([
            'totals' => $totals,
            'regions' => $regionStats,
            'states' => $stateStats,
        ]);
    }
}
