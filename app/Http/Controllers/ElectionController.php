<?php

namespace App\Http\Controllers;

use App\Models\ElectionEvent;
use App\Models\Result;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index()
    {
        $events = ElectionEvent::orderByDesc('start_date')->paginate(12);

        return view('elections.index', compact('events'));
    }

    public function calendar()
    {
        $events = ElectionEvent::orderBy('start_date')->get();

        return view('elections.calendar', compact('events'));
    }

    public function results()
    {
        abort_unless(feature_enabled('public_feature_results'), 404);
        $results = Result::with(['electionEvent', 'constituency'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        return view('elections.results', compact('results', 'states'));
    }

    public function types()
    {
        return view('elections.types');
    }

    public function electoralSystem()
    {
        return view('elections.electoral-system');
    }
}
