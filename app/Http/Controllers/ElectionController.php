<?php

namespace App\Http\Controllers;

use App\Models\ElectionEvent;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index()
    {
        $events = ElectionEvent::orderByDesc('start_date')->paginate(\App\Helpers\NecHelper::pageLimit('paginate_election_events', 12));

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
            ->paginate(\App\Helpers\NecHelper::pageLimit('paginate_results', 20));

        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        return view('elections.results', compact('results', 'states'));
    }

    public function types()
    {
        $events = ElectionEvent::where('status', 'active')->orderBy('start_date')->get();

        $presidential = $events->first(fn ($e) => str_contains($e->title, 'Presidential'));
        $parliamentary = $events->first(fn ($e) => str_contains($e->title, 'National Legislative'));
        $stateAssembly = $events->filter(fn ($e) => str_contains($e->title, 'State Assembly'));

        $fmt = fn ($e) => $e ? Carbon::parse($e->start_date)->format('d M Y') : null;

        return view('elections.types', compact('presidential', 'parliamentary', 'stateAssembly', 'fmt'));
    }

    public function electoralSystem()
    {
        return view('elections.electoral-system');
    }
}
