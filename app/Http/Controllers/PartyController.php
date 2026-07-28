<?php

namespace App\Http\Controllers;

use App\Models\PoliticalParty;

class PartyController extends Controller
{
    public function index()
    {
        $parties = PoliticalParty::orderBy('name')->get();

        return view('parties.index', compact('parties'));
    }
}
