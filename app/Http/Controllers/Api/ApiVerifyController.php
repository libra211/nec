<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiVerifyController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'voter_id' => 'nullable|string',
            'national_id' => 'nullable|string',
        ]);

        if (empty($validated['voter_id']) && empty($validated['national_id'])) {
            return response()->json([
                'error' => 'Either voter_id or national_id is required.',
            ], 422);
        }

        $query = Voter::query();

        if (!empty($validated['voter_id'])) {
            $query->where('voter_id', $validated['voter_id']);
        } elseif (!empty($validated['national_id'])) {
            $encrypted = nec_encrypt($validated['national_id']);
            $query->where('national_id_number', $encrypted);
        }

        $voter = $query->first();

        if (!$voter) {
            return response()->json([
                'verified' => false,
                'message' => 'Voter not found.',
            ]);
        }

        return response()->json([
            'verified' => true,
            'voter' => [
                'voter_id' => $voter->voter_id,
                'first_name' => $voter->first_name,
                'last_name' => $voter->last_name,
                'gender' => $voter->gender,
                'constituency' => $voter->constituency?->name,
                'status' => $voter->status,
                'registration_date' => $voter->registration_date?->toDateString(),
            ],
        ]);
    }
}
