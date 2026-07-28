<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResultsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Result::with(['electionEvent', 'constituency', 'candidateResults']);

        if ($electionId = $request->input('election_id')) {
            $query->where('election_event_id', $electionId);
        }

        if ($constituencyId = $request->input('constituency_id')) {
            $query->where('constituency_id', $constituencyId);
        }

        $results = $query->orderByDesc('declared_at')->get();

        return response()->json([
            'results' => $results->map(fn ($r) => [
                'id' => $r->id,
                'election_event' => $r->electionEvent?->name,
                'constituency' => $r->constituency?->name,
                'declared_at' => $r->declared_at?->toIso8601String(),
                'candidate_results' => $r->candidateResults->map(fn ($cr) => [
                    'candidate_name' => $cr->candidate_name,
                    'party_name' => $cr->party_name,
                    'votes' => $cr->votes,
                    'status' => $cr->status,
                ]),
            ]),
        ]);
    }
}
