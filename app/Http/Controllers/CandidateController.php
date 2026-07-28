<?php

namespace App\Http\Controllers;

use App\Models\Candidate;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['politicalParty'])
            ->orderBy('name')
            ->get();

        return view('candidates.index', compact('candidates'));
    }
}
