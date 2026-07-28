<?php

namespace App\Http\Controllers;

use App\Models\Constituency;
use App\Models\County;
use App\Models\State;

class ConstituencyController extends Controller
{
    public function index()
    {
        $constituencies = Constituency::with(['county', 'county.state'])->orderBy('name')->get();
        $counties = County::orderBy('name')->get();
        $states = State::orderBy('name')->get();

        return view('constituencies.index', compact('constituencies', 'counties', 'states'));
    }
}
