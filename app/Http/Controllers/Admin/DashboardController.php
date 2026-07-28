<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use App\Models\Voter;
use App\Models\VoterTransfer;
use App\Models\ActivityLog;
use App\Models\News;
use App\Models\ElectionEvent;
use App\Models\Constituency;
use App\Models\Candidate;
use App\Models\ObserverApplication;
use App\Models\Contact;
use App\Models\Announcement;
use App\Models\PollingStation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('admin_role');
        $stats = [];
        $charts = [];
        $recentActivity = ActivityLog::latest('created_at')->limit(15)->get();

        if (in_array($role, ['super_admin', 'admin'])) {
            $now = Carbon::now();
            $totalVoters = Voter::count();
            $totalPollingStations = PollingStation::count();
            $totalConstituencies = Constituency::count();

            $stats['total_voters'] = $totalVoters;
            $stats['total_users'] = User::count();
            $stats['total_constituencies'] = $totalConstituencies;
            $stats['total_candidates'] = Candidate::count();
            $stats['total_news'] = News::count();
            $stats['total_events'] = ElectionEvent::count();
            $stats['pending_transfers'] = VoterTransfer::where('status', 'pending')->count();
            $stats['pending_contacts'] = Contact::where('status', 'new')->count();
            $stats['total_observers'] = ObserverApplication::count();
            $stats['active_announcements'] = Announcement::where('status', 'published')->count();
            $stats['total_agents'] = Agent::where('status', 'active')->count();
            $stats['total_polling_stations'] = $totalPollingStations;

            $stats['active_voters'] = Voter::where('status', 'active')->count();
            $stats['suspended_voters'] = Voter::where('status', 'suspended')->count();
            $stats['inactive_voters'] = Voter::where('status', 'inactive')->count();

            $stats['new_today'] = Voter::where('registered_at', '>=', $now->copy()->startOfDay())->count();
            $stats['new_this_week'] = Voter::where('registered_at', '>=', $now->copy()->startOfWeek())->count();
            $stats['new_this_month'] = Voter::where('registered_at', '>=', $now->copy()->startOfMonth())->count();

            $stats['self_registered'] = Voter::where('registration_type', 'self')->count();
            $stats['agent_registered'] = Voter::where('registration_type', 'agent')->count();

            $maleCount = Voter::where('gender', 'M')->count();
            $femaleCount = Voter::where('gender', 'F')->count();
            $stats['male_count'] = $maleCount;
            $stats['female_count'] = $femaleCount;
            $stats['gender_ratio'] = $maleCount > 0 && $femaleCount > 0
                ? round($maleCount / $femaleCount, 2)
                : 0;

            $stats['avg_daily_registrations'] = $totalVoters > 0
                ? round($totalVoters / max(1, $now->diffInDays(Carbon::parse('2026-01-01'))))
                : 0;

            $coverageCounties = Voter::whereNotNull('county')->distinct('county')->count('county');
            $totalCounties = DB::table('nec_counties')->count();
            $stats['coverage_counties'] = $coverageCounties;
            $stats['total_counties'] = $totalCounties;
            $stats['county_coverage_pct'] = $totalCounties > 0 ? round(($coverageCounties / $totalCounties) * 100, 1) : 0;

            $coveragePayams = Voter::whereNotNull('payam')->distinct('payam')->count('payam');
            $totalPayams = DB::table('nec_payams')->count();
            $stats['coverage_payams'] = $coveragePayams;
            $stats['total_payams'] = $totalPayams;

            $stats['registration_capacity_pct'] = $totalPollingStations > 0
                ? round(($totalVoters / ($totalPollingStations * 1000)) * 100, 1)
                : 0;

            $stats['pending_transfer_rate'] = $totalVoters > 0
                ? round((VoterTransfer::where('status', 'pending')->count() / $totalVoters) * 100, 2)
                : 0;

            $stats['voters_by_state'] = Voter::selectRaw('state, COUNT(*) as total')
                ->whereNotNull('state')
                ->groupBy('state')
                ->pluck('total', 'state');

            $stats['voters_by_region'] = Voter::selectRaw('
                    CASE state
                        WHEN "Northern Bahr el Ghazal" THEN "Bahr el Ghazal"
                        WHEN "Western Bahr el Ghazal" THEN "Bahr el Ghazal"
                        WHEN "Warrap" THEN "Bahr el Ghazal"
                        WHEN "Lakes" THEN "Bahr el Ghazal"
                        WHEN "Central Equatoria" THEN "Equatoria"
                        WHEN "Eastern Equatoria" THEN "Equatoria"
                        WHEN "Western Equatoria" THEN "Equatoria"
                        WHEN "Jonglei" THEN "Greater Upper Nile"
                        WHEN "Unity" THEN "Greater Upper Nile"
                        WHEN "Upper Nile" THEN "Greater Upper Nile"
                        ELSE "Other"
                    END as region,
                    COUNT(*) as total
                ')
                ->whereNotNull('state')
                ->groupBy('region')
                ->pluck('total', 'region');

            $stats['voters_by_county'] = Voter::selectRaw('county, COUNT(*) as total')
                ->whereNotNull('county')
                ->groupBy('county')
                ->orderByDesc('total')
                ->limit(15)
                ->pluck('total', 'county');

            $stats['age_distribution'] = Voter::selectRaw('
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN "Under 18"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 51 AND 65 THEN "51-65"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 65 THEN "65+"
                        ELSE "Unknown"
                    END as age_group,
                    COUNT(*) as total
                ')
                ->whereNotNull('dob')
                ->groupBy('age_group')
                ->pluck('total', 'age_group');

            $ageGroups = $stats['age_distribution']->toArray();
            $stats['age_highest_group'] = $ageGroups ? array_search(max($ageGroups), $ageGroups) : 'N/A';
            $stats['age_lowest_group'] = $ageGroups ? array_search(min($ageGroups), $ageGroups) : 'N/A';

            $oldestVoter = Voter::whereNotNull('dob')->orderBy('dob', 'asc')->first();
            $youngestVoter = Voter::whereNotNull('dob')->orderBy('dob', 'desc')->first();
            $stats['oldest_voter_age'] = $oldestVoter ? $oldestVoter->dob->age : 'N/A';
            $stats['youngest_voter_age'] = $youngestVoter ? $youngestVoter->dob->age : 'N/A';

            $stats['registration_trend_30d'] = Voter::where('registered_at', '>=', $now->subDays(30))
                ->selectRaw('DATE(registered_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $stats['registration_trend'] = Voter::where('registered_at', '>=', $now->copy()->subDays(7))
                ->selectRaw('DATE(registered_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $stats['registration_by_day'] = Voter::selectRaw('
                    DAYNAME(registered_at) as day_name,
                    DAYOFWEEK(registered_at) as day_num,
                    COUNT(*) as total
                ')
                ->whereNotNull('registered_at')
                ->groupBy('day_name', 'day_num')
                ->orderBy('day_num')
                ->pluck('total', 'day_name');

            $stats['voters_by_gender'] = Voter::selectRaw('gender, COUNT(*) as total')
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $stats['voters_by_status'] = Voter::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $stats['registration_by_type'] = Voter::selectRaw('registration_type, COUNT(*) as total')
                ->groupBy('registration_type')
                ->pluck('total', 'registration_type');

            $stats['gender_by_state'] = Voter::selectRaw('state, gender, COUNT(*) as total')
                ->whereNotNull('state')
                ->groupBy('state', 'gender')
                ->get()
                ->groupBy('state')
                ->map(fn($items) => [
                    'M' => $items->where('gender', 'M')->sum('total'),
                    'F' => $items->where('gender', 'F')->sum('total'),
                ]);

            $stats['top_agents'] = Agent::where('status', 'active')
                ->orderByDesc('voters_registered')
                ->limit(5)
                ->get(['first_name', 'last_name', 'title', 'assigned_state', 'voters_registered']);

            $stats['transfers_by_status'] = VoterTransfer::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $stats['transfers_by_state'] = VoterTransfer::selectRaw('from_state, COUNT(*) as total')
                ->whereNotNull('from_state')
                ->groupBy('from_state')
                ->orderByDesc('total')
                ->pluck('total', 'from_state');

            $stats['top_constituencies'] = Voter::selectRaw('constituency, state, COUNT(*) as total')
                ->whereNotNull('constituency')
                ->groupBy('constituency', 'state')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

            $stats['monthly_trend'] = Voter::where('registered_at', '>=', $now->copy()->subMonths(12))
                ->selectRaw("DATE_FORMAT(registered_at, '%Y-%m') as month, COUNT(*) as total")
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $stats['gender_by_age'] = Voter::selectRaw('
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN "Under 18"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 51 AND 65 THEN "51-65"
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 65 THEN "65+"
                        ELSE "Unknown"
                    END as age_group,
                    gender,
                    COUNT(*) as total
                ')
                ->whereNotNull('dob')
                ->groupBy('age_group', 'gender')
                ->get()
                ->groupBy('age_group')
                ->map(fn($items) => [
                    'M' => $items->where('gender', 'M')->sum('total'),
                    'F' => $items->where('gender', 'F')->sum('total'),
                ]);

            $stats['polling_station_load'] = DB::table('nec_polling_stations')
                ->selectRaw('name, state, county, registered_voters')
                ->where('status', 'active')
                ->orderByDesc('registered_voters')
                ->limit(10)
                ->get();

            $stats['total_complaints'] = \App\Models\Complaint::count();
            $stats['new_complaints'] = \App\Models\Complaint::where('status', 'new')->count();
            $stats['total_reports'] = \App\Models\Report::count();
        }

        if ($role === 'state_coordinator') {
            $userState = session('admin_state') ?? '';
            $stats['state_voters'] = Voter::where('state', $userState)->count();
            $stats['state_constituencies'] = DB::table('nec_constituencies')->where('state', $userState)->count();
            $stats['state_transfers_pending'] = VoterTransfer::where('from_state', $userState)
                ->orWhere('to_state', $userState)
                ->where('status', 'pending')
                ->count();
            $stats['state_recent_registrations'] = Voter::where('state', $userState)
                ->where('registered_at', '>=', Carbon::now()->subDays(7))
                ->count();
            $stats['state_male'] = Voter::where('state', $userState)->where('gender', 'M')->count();
            $stats['state_female'] = Voter::where('state', $userState)->where('gender', 'F')->count();
            $stats['state_by_county'] = Voter::selectRaw('county, COUNT(*) as total')
                ->where('state', $userState)
                ->whereNotNull('county')
                ->groupBy('county')
                ->pluck('total', 'county');
            $stats['state_registration_trend'] = Voter::where('state', $userState)
                ->where('registered_at', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(registered_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        if ($role === 'constituency_officer') {
            $userConstituency = session('admin_constituency') ?? '';
            $stats['constituency_voters'] = Voter::where('constituency', $userConstituency)->count();
            $stats['constituency_stations'] = DB::table('nec_polling_stations')->where('constituency', $userConstituency)->count();
            $stats['constituency_male'] = Voter::where('constituency', $userConstituency)->where('gender', 'M')->count();
            $stats['constituency_female'] = Voter::where('constituency', $userConstituency)->where('gender', 'F')->count();
        }

        return view('admin.dashboard', compact('stats', 'recentActivity', 'role'));
    }
}
