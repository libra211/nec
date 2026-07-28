<?php

namespace App\Http\Controllers;

use App\Models\Commissioner;
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

        return view('about.leadership', compact('commissioners'));
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
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();
        return view('about.state-committees', compact('states'));
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
        return view('about.legal-framework');
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
                LEFT JOIN nec_constituencies c ON c.state = s.code
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
