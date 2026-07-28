<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiVoterLookupController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'voter_id' => 'nullable|string',
            'phone' => 'nullable|string',
            'constituency_id' => 'nullable|integer',
        ]);

        $query = Voter::with('constituency');

        if (!empty($validated['voter_id'])) {
            $query->where('voter_id', $validated['voter_id']);
        } elseif (!empty($validated['phone'])) {
            $encrypted = nec_encrypt($validated['phone']);
            $query->where('phone_number', $encrypted);
        } elseif (!empty($validated['constituency_id'])) {
            $query->where('constituency_id', $validated['constituency_id']);
        } else {
            return response()->json([
                'error' => 'At least one lookup parameter is required.',
            ], 422);
        }

        $voters = $query->get();

        if ($voters->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'No voters found.',
            ]);
        }

        return response()->json([
            'found' => true,
            'count' => $voters->count(),
            'voters' => $voters->map(fn ($v) => [
                'voter_id' => $v->voter_id,
                'first_name' => $v->first_name,
                'last_name' => $v->last_name,
                'gender' => $v->gender,
                'constituency' => $v->constituency?->name,
                'status' => $v->status,
            ]),
        ]);
    }
}
