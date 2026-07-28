<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\County;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeographicController extends Controller
{
    public function states(Request $request): JsonResponse
    {
        $regionId = $request->input('region_id');
        $query = State::where('status', 'active');

        if ($regionId) {
            $query->where('region_id', $regionId);
        }

        return response()->json($query->orderBy('name')->get(['id', 'name', 'code', 'capital']));
    }

    public function counties(Request $request): JsonResponse
    {
        $stateId = $request->input('state_id');
        if (!$stateId) {
            return response()->json([]);
        }

        return response()->json(
            County::where('state_id', $stateId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    public function constituencies(Request $request): JsonResponse
    {
        $state = $request->input('state');
        $county = $request->input('county');

        $query = DB::table('nec_constituencies')
            ->where('status', 'active');

        if ($state) {
            $stateCode = DB::table('nec_states')->where('name', $state)->value('code');
            $query->where(function ($q) use ($state, $stateCode) {
                $q->where('state', $state);
                if ($stateCode) {
                    $q->orWhere('state', $stateCode);
                }
            });
        }
        if ($county) {
            $countyBase = preg_replace('/\s*County$/i', '', $county);
            $query->where(function ($q) use ($county, $countyBase) {
                $q->where('county', $county)
                  ->orWhere('county', $countyBase)
                  ->orWhere('county', 'LIKE', $countyBase . '%');
            });
        }

        return response()->json(
            $query->orderBy('name')->pluck('name', 'id')->toArray()
        );
    }

    public function payams(Request $request): JsonResponse
    {
        $countyId = $request->input('county_id');
        if (!$countyId) {
            return response()->json([]);
        }

        return response()->json(
            DB::table('nec_payams')
                ->where('county_id', $countyId)
                ->where('status', 'active')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray()
        );
    }

    public function bomas(Request $request): JsonResponse
    {
        $payamId = $request->input('payam_id');
        if (!$payamId) {
            return response()->json([]);
        }

        return response()->json(
            DB::table('nec_bomas')
                ->where('payam_id', $payamId)
                ->where('status', 'active')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray()
        );
    }

    public function pollingStations(Request $request): JsonResponse
    {
        $state = $request->input('state');
        $county = $request->input('county');

        $query = DB::table('nec_polling_stations')
            ->where('status', 'active');

        if ($state) {
            $stateRow = DB::table('nec_states')->where('name', $state)->orWhere('code', $state)->first();
            $stateNames = [$state];
            if ($stateRow) {
                $stateNames[] = $stateRow->code;
                $stateNames[] = $stateRow->name;
            }
            $query->where(function ($q) use ($stateNames) {
                $q->whereIn('state', $stateNames);
            });
        }
        if ($county) {
            $query->where(function ($q) use ($county) {
                $q->where('county', $county)
                    ->orWhere('county', str_replace(' County', '', $county))
                    ->orWhere('county', $county . ' County');
            });
        }

        return response()->json(
            $query->orderBy('name')->pluck('name', 'id')->toArray()
        );
    }
}
