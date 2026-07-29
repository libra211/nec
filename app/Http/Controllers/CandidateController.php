<?php

namespace App\Http\Controllers;

use App\Models\Candidate;

class CandidateController extends Controller
{
    public function index()
    {
        abort_unless(feature_enabled('public_feature_candidates'), 404);
        $candidates = Candidate::with(['politicalParty'])
            ->orderBy('name')
            ->get();

        return view('candidates.index', compact('candidates'));
    }
}
