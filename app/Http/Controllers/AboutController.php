<?php

namespace App\Http\Controllers;

use App\Models\Commissioner;
use App\Models\Download;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        return view('about.index');
    }

    public function mandate()
    {
        return view('about.mandate');
    }

    public function leadership()
    {
        $commissioners = Commissioner::where('status', 'active')
            ->orderBy('order_num')
            ->get();

        $chairperson = $commissioners->firstWhere('position', 'Chairperson') ?? $commissioners->first();

        $others = $commissioners->filter(fn($c) => $c->id !== ($chairperson->id ?? null));

        return view('about.leadership', compact('commissioners', 'chairperson', 'others'));
    }

    public function commissioners()
    {
        $commissioners = Commissioner::orderBy('order_num')
            ->orderBy('name')
            ->get();

        return view('about.commissioners', compact('commissioners'));
    }

    public function stateCommittees()
    {
        $regions = DB::table('nec_regions')->where('status', 'active')->orderBy('sort_order')->get();
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        $stateStats = [];
        foreach ($states as $state) {
            $state->county_count = DB::table('nec_counties')->where('state_id', $state->id)->where('status', 'active')->count();
            $state->constituency_count = DB::table('nec_constituencies')->where('state', $state->name)->where('status', 'active')->count();
            $state->polling_station_count = DB::table('nec_polling_stations')->where('state', $state->name)->where('status', 'active')->count();
            $state->registered_voters = DB::table('nec_polling_stations')->where('state', $state->name)->where('status', 'active')->sum('registered_voters');
            $state->payam_count = DB::table('nec_payams')
                ->whereIn('county_id', fn($q) => $q->select('id')->from('nec_counties')->where('state_id', $state->id))
                ->where('status', 'active')->count();
            $state->region_name = $regions->firstWhere('id', $state->region_id)->name ?? '';
        }

        $totals = [
            'states' => $states->count(),
            'counties' => DB::table('nec_counties')->where('status', 'active')->count(),
            'constituencies' => DB::table('nec_constituencies')->where('status', 'active')->count(),
            'polling_stations' => DB::table('nec_polling_stations')->where('status', 'active')->count(),
            'registered_voters' => DB::table('nec_polling_stations')->where('status', 'active')->sum('registered_voters'),
            'payams' => DB::table('nec_payams')->where('status', 'active')->count(),
            'bomas' => DB::table('nec_bomas')->where('status', 'active')->count(),
        ];

        $pollingStations = DB::table('nec_polling_stations')->where('status', 'active')
            ->select('id', 'name', 'code', 'state', 'county', 'constituency', 'payam', 'registered_voters', 'latitude', 'longitude')
            ->get();

        return view('about.state-committees', compact('regions', 'states', 'totals', 'pollingStations'));
    }

    public function departments()
    {
        return view('about.departments');
    }

    public function history()
    {
        return view('about.history');
    }

    public function legalFramework()
    {
        $documents = Download::whereIn('id', [22, 45, 8, 26, 28])
            ->orderByRaw('FIELD(id, 22, 45, 8, 26, 28)')
            ->get();

        return view('about.legal-framework', compact('documents'));
    }

    public function boundaryCommission()
    {
        $breakdownRows = [];
        try {
            $breakdownRows = DB::select("
                SELECT s.name AS state_name,
                       COUNT(c.id) AS constituencies,
                       COUNT(c.id) * 80000 AS estimated_population,
                       80000 AS avg_voters
                FROM nec_states s
                LEFT JOIN nec_constituencies c ON c.state = s.name
                WHERE s.status = 'active'
                GROUP BY s.name
                ORDER BY s.name
            ");
        } catch (\Exception $e) {
            // Fallback empty - view has hardcoded fallback
        }

        $totalConstituencies = 0;
        try {
            $totalConstituencies = DB::table('nec_constituencies')->where('status', 'active')->count();
        } catch (\Exception $e) {}

        $totalStates = 0;
        try {
            $totalStates = DB::table('nec_states')->where('status', 'active')->count();
        } catch (\Exception $e) {}

        return view('about.boundary-commission', compact('breakdownRows', 'totalConstituencies', 'totalStates'));
    }
}
