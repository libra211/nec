<?php

namespace App\Http\Controllers;

use App\Models\PoliticalParty;

class PartyController extends Controller
{
    public function index()
    {
        abort_unless(feature_enabled('public_feature_parties'), 404);
        $parties = PoliticalParty::orderBy('name')->get();

        return view('parties.index', compact('parties'));
    }
}
