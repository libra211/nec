<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Constituency;
use App\Models\County;
use App\Models\Region;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiLocationsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $level = $request->input('level', 'regions');
        $parentId = $request->input('parent_id');

        return match ($level) {
            'states' => response()->json([
                'states' => State::orderBy('name')
                    ->when($parentId, fn ($q) => $q->where('region_id', $parentId))
                    ->get(['id', 'name', 'region_id']),
            ]),
            'counties' => response()->json([
                'counties' => County::orderBy('name')
                    ->when($parentId, fn ($q) => $q->where('state_id', $parentId))
                    ->get(['id', 'name', 'state_id']),
            ]),
            'constituencies' => response()->json([
                'constituencies' => Constituency::orderBy('name')
                    ->when($parentId, fn ($q) => $q->where('county_id', $parentId))
                    ->get(['id', 'name', 'county_id']),
            ]),
            default => response()->json([
                'regions' => Region::orderBy('name')->get(['id', 'name']),
            ]),
        };
    }
}
