<?php

namespace App\Http\Controllers;

use App\Models\Constituency;
use App\Models\County;
use App\Models\PollingStation;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class ConstituencyController extends Controller
{
    public function index()
    {
        $states = State::where('status', 'active')->orderBy('name')->get();

        $constituencies = Constituency::withCount(['pollingStations', 'candidates'])
            ->where('status', 'active')
            ->orderBy('state')
            ->orderBy('name')
            ->get();

        $voterTotals = PollingStation::select('constituency', DB::raw('COALESCE(SUM(registered_voters), 0) as voters'))
            ->whereNotNull('constituency')
            ->groupBy('constituency')
            ->get()
            ->keyBy('constituency');

        foreach ($constituencies as $c) {
            $c->registered_voters = (int) ($voterTotals[$c->name]->voters ?? 0);
        }

        $byState = $constituencies->groupBy('state')->map(function ($group, $stateName) {
            return [
                'name' => $stateName,
                'constituencies' => $group->count(),
                'polling_stations' => $group->sum(fn($c) => $c->polling_stations_count),
                'candidates' => $group->sum(fn($c) => $c->candidates_count),
                'voters' => $group->sum(fn($c) => $c->registered_voters),
            ];
        })->sortByDesc('constituencies')->values();

        $totalVoters = $byState->sum('voters');
        $totalPollingStations = $byState->sum('polling_stations');
        $totalCandidates = $byState->sum('candidates');
        $totalConstituencies = $constituencies->count();
        $statesWithData = $byState->count();
        $counties = County::orderBy('name')->get();

        return view('constituencies.index', compact(
            'constituencies',
            'states',
            'byState',
            'statesWithData',
            'totalConstituencies',
            'totalPollingStations',
            'totalCandidates',
            'totalVoters',
            'counties'
        ));
    }
}
