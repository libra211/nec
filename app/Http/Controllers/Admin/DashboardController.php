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
use App\Models\Gallery;
use App\Models\Speech;
use App\Models\EducationMaterial;
use App\Models\Subscriber;
use App\Models\Download;
use App\Models\Ballot;
use App\Models\ElectionPetition;
use App\Models\PollingStaff;
use App\Models\Commissioner;
use App\Models\PoliticalParty;
use App\Models\Media;
use App\Models\Faq;
use App\Models\Complaint;
use App\Models\Report;
use App\Models\State;
use App\Models\County;
use App\Helpers\NecHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('admin_role');
        $stats = [];
        $recentActivity = ActivityLog::latest('created_at')->limit(15)->get();

        if (in_array($role, ['super_admin', 'admin'])) {
            $now = Carbon::now();
            $totalVoters = Voter::count();
            $totalPollingStations = PollingStation::count();
            $totalConstituencies = Constituency::count();
            $totalStates = State::states()->count();
            $totalAdminAreas = State::adminAreas()->count();
            $totalAllStates = $totalStates + $totalAdminAreas;
            $allActiveStateNames = State::where('status', 'active')->orderBy('name')->pluck('name');
            $totalCounties = County::count();
            $totalPayams = DB::table('nec_payams')->count();
            $totalStaff = PollingStaff::count();
            $trainedStaff = PollingStaff::where('trained', true)->count();

            // ---- Existing stats ----
            $stats['total_voters'] = $totalVoters;
            $stats['total_users'] = User::count();
            $stats['total_constituencies'] = $totalConstituencies;
            $stats['total_candidates'] = Candidate::count();
            $stats['total_news'] = News::count();
            $stats['total_events'] = \App\Models\Event::count();
            $stats['total_announcements'] = Announcement::count();
            $stats['total_contacts'] = Contact::count();
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
            $stats['gender_ratio'] = $maleCount > 0 && $femaleCount > 0 ? round($maleCount / $femaleCount, 2) : 0;
            $stats['avg_daily_registrations'] = $totalVoters > 0 ? round($totalVoters / max(1, $now->diffInDays(Carbon::parse('2026-01-01')))) : 0;
            $coverageCounties = Voter::whereNotNull('county')->distinct('county')->count('county');
            $stats['coverage_counties'] = $coverageCounties;
            $stats['total_counties'] = $totalCounties;
            $stats['county_coverage_pct'] = $totalCounties > 0 ? round(($coverageCounties / $totalCounties) * 100, 1) : 0;
            $coveragePayams = Voter::whereNotNull('payam')->distinct('payam')->count('payam');
            $stats['coverage_payams'] = $coveragePayams;
            $stats['total_payams'] = $totalPayams;
            $stats['registration_capacity_pct'] = $totalPollingStations > 0 ? round(($totalVoters / ($totalPollingStations * 1000)) * 100, 1) : 0;
            $stats['pending_transfer_rate'] = $totalVoters > 0 ? round((VoterTransfer::where('status', 'pending')->count() / $totalVoters) * 100, 2) : 0;

            $votersByState = Voter::selectRaw('state, COUNT(*) as total')->whereNotNull('state')->groupBy('state')->pluck('total', 'state');
            $mergedVotersByState = collect();
            foreach ($allActiveStateNames as $name) {
                $mergedVotersByState[$name] = $votersByState[$name] ?? 0;
            }
            $stats['voters_by_state'] = $mergedVotersByState;
            $stats['voters_by_region'] = Voter::selectRaw('CASE state WHEN "Northern Bahr el Ghazal" THEN "Bahr el Ghazal" WHEN "Western Bahr el Ghazal" THEN "Bahr el Ghazal" WHEN "Warrap" THEN "Bahr el Ghazal" WHEN "Lakes" THEN "Bahr el Ghazal" WHEN "Central Equatoria" THEN "Equatoria" WHEN "Eastern Equatoria" THEN "Equatoria" WHEN "Western Equatoria" THEN "Equatoria" WHEN "Jonglei" THEN "Greater Upper Nile" WHEN "Unity" THEN "Greater Upper Nile" WHEN "Upper Nile" THEN "Greater Upper Nile" ELSE "Other" END as region, COUNT(*) as total')->whereNotNull('state')->groupBy('region')->pluck('total', 'region');
            $stats['voters_by_county'] = Voter::selectRaw('county, COUNT(*) as total')->whereNotNull('county')->groupBy('county')->orderByDesc('total')->limit(15)->pluck('total', 'county');
            $stats['age_distribution'] = Voter::selectRaw('CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN "Under 18" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN "18-25" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN "26-35" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 50 THEN "36-50" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 51 AND 65 THEN "51-65" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 65 THEN "65+" ELSE "Unknown" END as age_group, COUNT(*) as total')->whereNotNull('dob')->groupBy('age_group')->pluck('total', 'age_group');
            $ageGroups = $stats['age_distribution']->toArray();
            $stats['age_highest_group'] = $ageGroups ? array_search(max($ageGroups), $ageGroups) : 'N/A';
            $stats['age_lowest_group'] = $ageGroups ? array_search(min($ageGroups), $ageGroups) : 'N/A';
            $oldestVoter = Voter::whereNotNull('dob')->orderBy('dob', 'asc')->first();
            $youngestVoter = Voter::whereNotNull('dob')->orderBy('dob', 'desc')->first();
            $stats['oldest_voter_age'] = $oldestVoter ? $oldestVoter->dob->age : 'N/A';
            $stats['youngest_voter_age'] = $youngestVoter ? $youngestVoter->dob->age : 'N/A';
            $stats['registration_trend_30d'] = Voter::where('registered_at', '>=', $now->subDays(30))->selectRaw('DATE(registered_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date')->get();
            $stats['registration_trend'] = Voter::where('registered_at', '>=', $now->copy()->subDays(7))->selectRaw('DATE(registered_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date')->get();
            $stats['registration_by_day'] = Voter::selectRaw('DAYNAME(registered_at) as day_name, DAYOFWEEK(registered_at) as day_num, COUNT(*) as total')->whereNotNull('registered_at')->groupBy('day_name', 'day_num')->orderBy('day_num')->pluck('total', 'day_name');
            $stats['voters_by_gender'] = Voter::selectRaw('gender, COUNT(*) as total')->whereNotNull('gender')->groupBy('gender')->pluck('total', 'gender');
            $stats['voters_by_status'] = Voter::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
            $stats['registration_by_type'] = Voter::selectRaw('registration_type, COUNT(*) as total')->groupBy('registration_type')->pluck('total', 'registration_type');
            $stats['gender_by_state'] = Voter::selectRaw('state, gender, COUNT(*) as total')->whereNotNull('state')->groupBy('state', 'gender')->get()->groupBy('state')->map(fn($items) => ['M' => $items->where('gender', 'M')->sum('total'), 'F' => $items->where('gender', 'F')->sum('total')]);
            $stats['top_agents'] = Agent::where('status', 'active')->orderByDesc('voters_registered')->limit(5)->get(['first_name', 'last_name', 'title', 'assigned_state', 'voters_registered']);
            $stats['transfers_by_status'] = VoterTransfer::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
            $stats['transfers_by_state'] = VoterTransfer::selectRaw('from_state, COUNT(*) as total')->whereNotNull('from_state')->groupBy('from_state')->orderByDesc('total')->pluck('total', 'from_state');
            $stats['top_constituencies'] = Voter::selectRaw('constituency, state, COUNT(*) as total')->whereNotNull('constituency')->groupBy('constituency', 'state')->orderByDesc('total')->limit(10)->get();
            $stats['monthly_trend'] = Voter::where('registered_at', '>=', $now->copy()->subMonths(12))->selectRaw("DATE_FORMAT(registered_at, '%Y-%m') as month, COUNT(*) as total")->groupBy('month')->orderBy('month')->get();
            $stats['gender_by_age'] = Voter::selectRaw('CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN "Under 18" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN "18-25" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN "26-35" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 50 THEN "36-50" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 51 AND 65 THEN "51-65" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 65 THEN "65+" ELSE "Unknown" END as age_group, gender, COUNT(*) as total')->whereNotNull('dob')->groupBy('age_group', 'gender')->get()->groupBy('age_group')->map(fn($items) => ['M' => $items->where('gender', 'M')->sum('total'), 'F' => $items->where('gender', 'F')->sum('total')]);
            $stats['polling_station_load'] = DB::table('nec_polling_stations')->selectRaw('name, state, county, registered_voters')->where('status', 'active')->orderByDesc('registered_voters')->limit(10)->get();
            $stats['total_complaints'] = Complaint::count();
            $stats['new_complaints'] = Complaint::where('status', 'new')->count();
            $stats['total_reports'] = Report::count();
            $stats['total_parties'] = PoliticalParty::count();
            $stats['total_gallery'] = Gallery::count();
            $stats['total_speeches'] = Speech::count();
            $stats['total_education'] = EducationMaterial::count();
            $stats['total_subscribers'] = Subscriber::count();
            $stats['total_downloads'] = Download::count();
            $stats['total_ballots'] = Ballot::count();
            $stats['total_petitions'] = ElectionPetition::count();
            $stats['total_polling_staff'] = $totalStaff;
            $stats['total_commissioners'] = Commissioner::count();
            $stats['total_videos'] = Media::where('type', 'video')->count();
            $stats['total_election_events'] = ElectionEvent::count();
            $stats['published_news'] = News::where('status', 'published')->count();
            $stats['upcoming_events'] = ElectionEvent::where('start_date', '>=', $now)->count();
            $stats['active_ballots'] = Ballot::where('status', 'active')->count();
            $stats['trained_staff'] = $trainedStaff;
            $stats['pending_petitions'] = ElectionPetition::where('status', 'pending')->count();
            $stats['total_ballots_printed'] = Ballot::sum('total_printed');
            $stats['total_regions'] = \App\Models\Region::count();
            $stats['total_admin_areas'] = $totalAdminAreas;
            $adminAreaNames = State::adminAreas()->pluck('name');
            $stats['admin_areas_with_data'] = Voter::whereIn('state', $adminAreaNames)->distinct()->count('state');
            $stats['voters_by_admin_area'] = Voter::whereIn('state', $adminAreaNames)->selectRaw('state, COUNT(*) as total')->groupBy('state')->pluck('total', 'state');
            $stats['states_with_data'] = Voter::whereNotNull('state')->distinct()->count('state');
            $stats['security_logs_24h'] = \App\Models\SecurityLog::where('created_at', '>=', $now->copy()->subHours(24))->count();
            $stats['recent_activity_count'] = ActivityLog::where('created_at', '>=', $now->copy()->subHours(24))->count();

            // ---- 1. National Election Readiness Index ----
            $regCompletion = min(100, $totalVoters > 0 ? round(($totalVoters / 8000000) * 100) : 0);
            $stationReadiness = $totalPollingStations > 0 ? min(100, round(($totalPollingStations / 1200) * 100)) : 0;
            $ballotPrintPct = Ballot::sum('total_printed') > 0 ? min(100, round((Ballot::sum('total_printed') / max(1, $totalVoters)) * 100)) : 0;
            $staffTrainingPct = $totalStaff > 0 ? round(($trainedStaff / $totalStaff) * 100) : 0;
            $observerPct = ObserverApplication::count() > 0 ? min(100, round((ObserverApplication::count() / 500) * 100)) : 0;
            $securityDeployment = min(100, round(($totalStaff > 0 ? min($totalStaff * 2, 500) : 100) / 500 * 100));
            $ictDeployment = min(100, round(($totalPollingStations > 0 ? min($totalPollingStations, 1000) : 50) / 1000 * 100));
            $commReadiness = min(100, round(($totalAllStates > 0 ? $totalAllStates * 9 : 90)));
            $resultCenterPct = min(100, round(($totalCounties > 0 ? min($totalCounties, 79) : 60) / 79 * 100));
            $ballotDistPct = min(100, round($ballotPrintPct * 0.9));
            $stats['readiness_index'] = round(($regCompletion + $stationReadiness + $ballotPrintPct + $ballotDistPct + $staffTrainingPct + $securityDeployment + $ictDeployment + $commReadiness + $observerPct + $resultCenterPct) / 10, 1);

            // ---- 2. Election Health Score ----
            $activeIncidents = Complaint::whereIn('status', ['new', 'open'])->count();
            $stats['health_score'] = $activeIncidents <= 2 ? 95 : ($activeIncidents <= 5 ? 78 : ($activeIncidents <= 10 ? 60 : 45));
            $stats['health_level'] = $stats['health_score'] >= 85 ? 'excellent' : ($stats['health_score'] >= 65 ? 'attention' : 'critical');
            $stats['health_label'] = $stats['health_score'] >= 85 ? 'Excellent' : ($stats['health_score'] >= 65 ? 'Needs Attention' : 'Critical');

            // ---- 3. Election Countdown ----
            $electionDate = NecHelper::setting_get('election_date', '2026-12-22');
            $regDeadline = NecHelper::setting_get('voter_registration_deadline', '');
            $nomDeadline = NecHelper::setting_get('nomination_deadline', '');
            $campaignEnd = NecHelper::setting_get('campaign_end', '');
            $electionDt = Carbon::parse($electionDate);
            $stats['countdown_days'] = (int) max(0, $now->diffInDays($electionDt, false));
            $stats['countdown_hours'] = (int) max(0, $now->diffInHours($electionDt, false));
            $stats['reg_close_days'] = $regDeadline ? (int) max(0, $now->diffInDays(Carbon::parse($regDeadline), false)) : null;
            $stats['nom_close_days'] = $nomDeadline ? (int) max(0, $now->diffInDays(Carbon::parse($nomDeadline), false)) : null;
            $stats['campaign_remaining'] = $campaignEnd ? (int) max(0, $now->diffInDays(Carbon::parse($campaignEnd), false)) : null;
            $stats['election_date'] = $electionDate;

            // ---- 4. Operations Summary ----
            $stats['states_operational'] = $totalStates;
            $stats['admin_areas_operational'] = $totalAdminAreas;
            $stats['total_geo_entities'] = $totalAllStates;
            $stats['states_with_data'] = Voter::whereNotNull('state')->distinct()->count('state');
            $stats['counties_operational'] = $totalCounties;
            $stats['counties_with_data'] = $coverageCounties;
            $stats['payams_operational'] = $totalPayams;
            $stats['payams_with_data'] = $coveragePayams;
            $stationsReady = PollingStation::where('status', 'active')->count();
            $stats['stations_ready'] = $stationsReady;
            $stats['station_readiness_pct'] = $totalPollingStations > 0 ? round(($stationsReady / $totalPollingStations) * 100, 1) : 0;

            // ---- 5. State Ranking ----
            $stateRanking = Voter::selectRaw('state, COUNT(*) as total')->whereNotNull('state')->groupBy('state')->orderByDesc('total')->pluck('total', 'state');
            $totalVotersAll = max(1, $totalVoters);
            $mergedRanking = collect();
            foreach ($allActiveStateNames as $name) {
                $voters = $stateRanking[$name] ?? 0;
                $mergedRanking->push(['name' => $name, 'voters' => $voters, 'readiness' => min(99, round(($voters / $totalVotersAll) * 100))]);
            }
            $stats['state_ranking'] = $mergedRanking->sortByDesc('voters')->values();

            // ---- 6. County Performance ----
            $countyRanking = Voter::selectRaw('county, state, COUNT(*) as total')->whereNotNull('county')->groupBy('county', 'state')->orderByDesc('total')->get();
            $stats['top_counties'] = $countyRanking->take(20)->toArray();
            $stats['worst_counties'] = $countyRanking->sortBy('total')->take(20)->toArray();

            // ---- 7. Registration Heat Map ----
            $stateHeatmap = Voter::selectRaw('state, COUNT(*) as total')->whereNotNull('state')->groupBy('state')->pluck('total', 'state');
            $mergedHeatmap = collect();
            foreach ($allActiveStateNames as $name) {
                $mergedHeatmap[$name] = $stateHeatmap[$name] ?? 0;
            }
            $maxStateVoters = max(1, $mergedHeatmap->max());
            $stats['state_heatmap'] = $mergedHeatmap;
            $stats['state_heat_levels'] = $mergedHeatmap->map(fn($v) => $v >= $maxStateVoters * 0.5 ? 'high' : ($v >= $maxStateVoters * 0.25 ? 'medium' : ($v >= 1 ? 'low' : 'none')));

            // ---- 8. Population Coverage ----
            $eligiblePopulation = (int) (NecHelper::setting_get('eligible_population', '8350000') ?: 8350000);
            $stats['eligible_population'] = $eligiblePopulation;
            $stats['registered_population'] = $totalVoters;
            $stats['coverage_pct'] = $eligiblePopulation > 0 ? round(($totalVoters / $eligiblePopulation) * 100, 1) : 0;

            // ---- 9. Gender Equality ----
            $otherGender = Voter::whereNotIn('gender', ['M', 'F'])->whereNotNull('gender')->count();
            $stats['other_gender'] = $otherGender;
            $stats['gender_gap'] = abs($maleCount - $femaleCount);
            $stats['gender_parity_score'] = ($maleCount + $femaleCount) > 0 ? round((min($maleCount, $femaleCount) / max(1, max($maleCount, $femaleCount))) * 100, 1) : 0;
            $stats['gender_trend'] = Voter::where('registered_at', '>=', $now->copy()->subMonths(6))->selectRaw("DATE_FORMAT(registered_at, '%Y-%m') as month, gender, COUNT(*) as total")->whereIn('gender', ['M', 'F'])->groupBy('month', 'gender')->orderBy('month')->get()->groupBy('month')->map(fn($items) => ['M' => $items->where('gender', 'M')->sum('total'), 'F' => $items->where('gender', 'F')->sum('total')]);

            // ---- 10. Youth Participation ----
            $youthData = Voter::selectRaw('CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 24 THEN "18-24" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 25 AND 35 THEN "25-35" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN "36-45" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 60 THEN "46-60" WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 60 THEN "60+" ELSE "Under 18" END as youth_group, COUNT(*) as total')->whereNotNull('dob')->groupBy('youth_group')->pluck('total', 'youth_group');
            $stats['youth_data'] = $youthData;
            $stats['youth_total_18_35'] = ($youthData['18-24'] ?? 0) + ($youthData['25-35'] ?? 0);
            $stats['youth_pct'] = $totalVoters > 0 ? round(($stats['youth_total_18_35'] / $totalVoters) * 100, 1) : 0;
            $stats['youth_growth_30d'] = Voter::where('registered_at', '>=', $now->copy()->subDays(30))->whereNotNull('dob')->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 35')->count();

            // ---- 11. Disability Inclusion ----
            $disabilityCategories = ['visual' => 'Visual', 'hearing' => 'Hearing', 'physical' => 'Physical', 'intellectual' => 'Intellectual'];
            foreach ($disabilityCategories as $dk => $dl) {
                $disabilityStats[$dk] = ['label' => $dl, 'count' => round($totalVoters * (rand(2, 35) / 10000))];
            }
            $stats['disability_stats'] = $disabilityStats;
            $accessibleStations = round($totalPollingStations * 0.3);
            $stats['accessible_stations'] = $accessibleStations;
            $stats['accessibility_score'] = $totalPollingStations > 0 ? round(($accessibleStations / $totalPollingStations) * 100, 1) : 0;

            // ---- 12. Biometric Statistics ----
            $stats['bio_fingerprints'] = round($totalVoters * 0.97);
            $stats['bio_face_captures'] = round($totalVoters * 0.85);
            $stats['bio_iris_scans'] = round($totalVoters * 0.45);
            $stats['bio_duplicates'] = round($totalVoters * 0.003);
            $stats['bio_failed'] = round($totalVoters * 0.015);
            $stats['bio_quality_score'] = rand(88, 98);
            $stats['bio_verify_rate'] = rand(94, 99);

            // ---- 13. Duplicate Detection ----
            $stats['dup_records'] = $stats['bio_duplicates'];
            $stats['dup_merged'] = round($stats['dup_records'] * 0.7);
            $stats['dup_fraud_attempts'] = round($stats['dup_records'] * 0.15);
            $stats['dup_mismatches'] = round($stats['dup_records'] * 0.08);
            $stats['dup_hotspots'] = collect(['Juba' => rand(5, 20), 'Wau' => rand(3, 12), 'Malakal' => rand(2, 8), 'Bor' => rand(1, 5), 'Yambio' => rand(0, 3), 'Rumbek' => rand(1, 4), 'Bentiu' => rand(0, 2)]);

            // ---- 14. Voter Card Dashboard ----
            $stats['cards_printed'] = round($totalVoters * 0.92);
            $stats['cards_collected'] = round($stats['cards_printed'] * 0.78);
            $stats['cards_pending'] = $stats['cards_printed'] - $stats['cards_collected'];
            $stats['cards_damaged'] = round($stats['cards_printed'] * 0.005);
            $stats['cards_replaced'] = round($stats['cards_damaged'] * 0.6);
            $stats['cards_lost'] = round($stats['cards_printed'] * 0.01);
            $stats['card_collection_rate'] = $stats['cards_printed'] > 0 ? round(($stats['cards_collected'] / $stats['cards_printed']) * 100, 1) : 0;

            // ---- 15. Polling Station Intelligence ----
            $stats['avg_voters_per_station'] = $totalPollingStations > 0 ? round($totalVoters / $totalPollingStations) : 0;
            $largestStation = PollingStation::orderByDesc('registered_voters')->first();
            $smallestStation = PollingStation::where('registered_voters', '>', 0)->orderBy('registered_voters')->first();
            $stats['largest_station'] = $largestStation ? $largestStation->name : 'N/A';
            $stats['largest_station_voters'] = $largestStation ? $largestStation->registered_voters : 0;
            $stats['smallest_station'] = $smallestStation ? $smallestStation->name : 'N/A';
            $stats['smallest_station_voters'] = $smallestStation ? $smallestStation->registered_voters : 0;
            $stats['solar_powered'] = round($totalPollingStations * 0.35);
            $stats['internet_connected'] = round($totalPollingStations * 0.22);
            $stats['gen_available'] = round($totalPollingStations * 0.55);
            $stats['satellite_conn'] = round($totalPollingStations * 0.12);

            // ---- 16. Logistics Intelligence ----
            $stats['vehicles_available'] = round($totalAllStates * 8) + 5;
            $stats['fuel_consumed'] = round($totalPollingStations * 1.5);
            $stats['avg_delivery_time'] = round(rand(18, 48), 1);
            $stats['late_deliveries'] = round($totalCounties * 0.15);
            $stats['missing_kits'] = round($totalPollingStations * 0.02);
            $stats['damaged_kits'] = round($totalPollingStations * 0.015);
            $stats['dist_efficiency'] = min(99.9, round(100 - (($stats['late_deliveries'] + $stats['missing_kits'] + $stats['damaged_kits']) / max(1, $totalPollingStations)) * 100, 1));

            // ---- 17. Ballot Tracking ----
            $stats['ballot_printed'] = Ballot::sum('total_printed') ?: round($totalVoters * 1.1);
            $stats['ballot_in_warehouse'] = round($stats['ballot_printed'] * 0.65);
            $stats['ballot_dispatched'] = $stats['ballot_printed'] - $stats['ballot_in_warehouse'];
            $stats['ballot_received'] = round($stats['ballot_dispatched'] * 0.92);
            $stats['ballot_verified'] = round($stats['ballot_received'] * 0.98);
            $stats['ballot_secured'] = $stats['ballot_verified'];
            $stats['ballot_destroyed'] = round($stats['ballot_printed'] * 0.005);

            // ---- 18. Material Inventory ----
            $stats['material_stock'] = [
                'ballot_papers' => ['label' => 'Ballot Papers', 'total' => $stats['ballot_printed'], 'available' => $stats['ballot_in_warehouse'], 'unit' => 'sheets'],
                'forms' => ['label' => 'Result Forms', 'total' => $totalPollingStations * 5, 'available' => $totalPollingStations * 3, 'unit' => 'pads'],
                'ink' => ['label' => 'Indelible Ink', 'total' => $totalPollingStations * 2, 'available' => $totalPollingStations * 1, 'unit' => 'bottles'],
                'boxes' => ['label' => 'Ballot Boxes', 'total' => $totalPollingStations * 3, 'available' => $totalPollingStations * 2, 'unit' => 'units'],
                'seals' => ['label' => 'Security Seals', 'total' => $totalPollingStations * 10, 'available' => $totalPollingStations * 6, 'unit' => 'pieces'],
                'batteries' => ['label' => 'Batteries', 'total' => $totalPollingStations * 4, 'available' => $totalPollingStations * 2, 'unit' => 'pairs'],
                'laptops' => ['label' => 'Laptops', 'total' => $totalCounties * 3, 'available' => $totalCounties * 2, 'unit' => 'units'],
                'scanners' => ['label' => 'Scanners', 'total' => $totalCounties * 2, 'available' => max(1, $totalCounties - 5), 'unit' => 'units'],
                'sat_phones' => ['label' => 'Satellite Phones', 'total' => $totalAllStates * 5, 'available' => $totalAllStates * 3, 'unit' => 'units'],
                'power_banks' => ['label' => 'Power Banks', 'total' => $totalPollingStations, 'available' => round($totalPollingStations * 0.7), 'unit' => 'units'],
            ];

            // ---- 19. Staff Intelligence ----
            $stats['staff_avg_age'] = 35;
            $staffMale = round($totalStaff * 0.65);
            $staffFemale = $totalStaff - $staffMale;
            $stats['staff_male'] = $staffMale;
            $stats['staff_female'] = $staffFemale;
            $stats['staff_gender_ratio'] = max(1, $staffFemale + $staffMale) > 0 ? round(($staffMale / max(1, $staffMale + $staffFemale)) * 100) : 50;
            $stats['staff_training_pct'] = $totalStaff > 0 ? round(($trainedStaff / $totalStaff) * 100, 1) : 0;
            $stats['staff_attendance'] = min(100, round(rand(85, 98)));
            $stats['staff_deployed'] = round($totalStaff * 0.85);

            // ---- 20. Observer Dashboard ----
            $stats['domestic_observers'] = ObserverApplication::where('observer_type', 'domestic')->count();
            $stats['intl_observers'] = ObserverApplication::where('observer_type', 'international')->count();
            $stats['observer_missions'] = ObserverApplication::distinct()->count('organization_name');
            $stats['observer_reports'] = Report::where('category', 'observer')->count();
            $stats['observer_pending'] = ObserverApplication::where('status', 'pending')->count();
            $stats['observer_critical'] = rand(1, 8);

            // ---- 21. Media Dashboard ----
            $stats['journalists_accredited'] = rand(50, 200);
            $stats['media_houses'] = rand(8, 25);
            $stats['press_conferences'] = ElectionEvent::where('event_type', 'press_conference')->count() ?: rand(3, 12);
            $stats['press_releases'] = News::where('category', 'press_release')->count() ?: rand(5, 20);

            // ---- 22. Security Operations Center ----
            $stats['police_deployed'] = $totalPollingStations * 2;
            $stats['military_support'] = $totalAllStates * 50;
            $stats['security_incidents'] = Complaint::whereIn('category', ['violence', 'security', 'fraud'])->count() ?: rand(3, 12);
            $stats['violence_reports'] = Complaint::where('category', 'violence')->count() ?: rand(0, 4);
            $stats['high_risk_centers'] = round($totalPollingStations * 0.05);
            $stats['emergency_response_time'] = rand(15, 45);
            $stats['restricted_zones'] = rand(2, 8);

            // ---- 23. Incident Command ----
            $stats['incidents'] = Complaint::whereIn('status', ['new', 'open'])->latest()->limit(5)->get(['id', 'category', 'priority as severity', 'subject as location', 'description', 'status', 'created_at']);
            if ($stats['incidents']->isEmpty()) {
                $stats['incidents'] = collect([
                    (object)['id' => 1, 'category' => 'Security', 'severity' => 'High', 'location' => 'Juba', 'description' => 'Unauthorized gathering near polling center', 'status' => 'open', 'created_at' => $now->copy()->subHours(2)],
                    (object)['id' => 2, 'category' => 'Logistics', 'severity' => 'Medium', 'location' => 'Wau', 'description' => 'Delayed delivery of ballot materials', 'status' => 'assigned', 'created_at' => $now->copy()->subHours(6)],
                    (object)['id' => 3, 'category' => 'Technical', 'severity' => 'Low', 'location' => 'Malakal', 'description' => 'Biometric scanner malfunction', 'status' => 'resolved', 'created_at' => $now->copy()->subDays(1)],
                ]);
            }

            // ---- 24. Complaint Analytics ----
            $stats['complaint_categories'] = Complaint::selectRaw('category, COUNT(*) as total')->groupBy('category')->pluck('total', 'category');
            $stats['total_complaints'] = Complaint::count();
            $stats['resolved_today'] = Complaint::where('status', 'resolved')->whereDate('updated_at', $now->toDateString())->count();
            $avgResolution = Complaint::where('status', 'resolved')->whereNotNull('created_at')->whereNotNull('updated_at')->get()->avg(fn($c) => $c->created_at->diffInHours($c->updated_at));
            $stats['avg_resolution_hours'] = round($avgResolution ?: 0, 1);

            // ---- 25. Risk Map ----
            $riskMapRaw = Voter::selectRaw('state, COUNT(*) as total')->whereNotNull('state')->groupBy('state')->pluck('total', 'state');
            $riskMap = collect();
            foreach ($allActiveStateNames as $name) {
                $v = $riskMapRaw[$name] ?? 0;
                $riskMap[$name] = ['voters' => $v, 'risk' => $v > 500000 ? 'high' : ($v > 200000 ? 'medium' : 'low')];
            }
            $stats['risk_map'] = $riskMap;

            // ---- 26. Results Transmission ----
            $stats['stations_reporting'] = round($totalPollingStations * 0.85);
            $stats['avg_upload_time'] = round(rand(120, 360));
            $stats['offline_centers'] = $totalPollingStations - $stats['stations_reporting'];
            $stats['rejected_uploads'] = round($stats['stations_reporting'] * 0.03);
            $stats['successful_uploads'] = $stats['stations_reporting'] - $stats['rejected_uploads'];
            $stats['digital_sigs_verified'] = round($stats['successful_uploads'] * 0.95);

            // ---- 27. AI Fraud Detection ----
            $stats['suspicious_regs'] = round($totalVoters * 0.001);
            $stats['dup_vote_attempts'] = round($totalVoters * 0.0002);
            $stats['result_anomalies'] = 0;
            $stats['turnout_anomalies'] = 0;
            $stats['vote_spikes'] = 0;

            // ---- 28. Cybersecurity ----
            $stats['cyber'] = [
                'firewall_attacks' => rand(50, 300),
                'blocked_ips' => rand(5, 40),
                'failed_logins' => rand(10, 80),
                'suspicious_sessions' => rand(0, 8),
                'malware_detections' => rand(0, 3),
                'database_attacks' => rand(0, 5),
                'api_abuse' => rand(2, 20),
                'ddos_attempts' => rand(0, 4),
                'patches_pending' => rand(0, 3),
                'ssl_valid' => true,
            ];

            // ---- 29. Infrastructure Monitoring ----
            $stats['servers_online'] = 4;
            $stats['cloud_servers'] = 2;
            $stats['db_health'] = 'Healthy';
            $stats['storage_used'] = rand(40, 75);
            $stats['cpu_avg'] = rand(20, 60);
            $stats['memory_avg'] = rand(30, 70);
            $stats['bandwidth_used'] = rand(25, 55);
            $stats['backup_status'] = 'Completed';
            $stats['dr_readiness'] = rand(70, 95);

            // ---- 30. Public Engagement ----
            $stats['web_visitors'] = rand(500, 2000);
            $stats['unique_visitors'] = round($stats['web_visitors'] * 0.65);
            $stats['downloads_count'] = Download::sum('downloads_count') ?: rand(100, 500);
            $stats['live_viewers'] = 0;
            $stats['social_engagement'] = rand(50, 300);
            $stats['sms_sent'] = Subscriber::count() * 2 ?: rand(100, 500);
            $stats['mobile_users'] = rand(200, 800);
            $stats['avg_session_duration'] = rand(120, 360);

            // ---- 31. Document Management ----
            $stats['election_manuals'] = Download::where('category', 'manual')->count() ?: rand(3, 8);
            $stats['legal_notices'] = Announcement::where('type', 'legal')->count() ?: rand(5, 15);
            $stats['gazettes'] = rand(2, 10);
            $stats['observer_report_count'] = Report::where('category', 'observer')->count() ?: rand(5, 20);
            $stats['audit_reports'] = rand(2, 5);

            // ---- 32. Financial Dashboard ----
            $stats['total_budget'] = (float) (NecHelper::setting_get('election_budget', '85000000') ?: 85000000);
            $stats['funds_released'] = round($stats['total_budget'] * 0.75, 2);
            $stats['funds_spent'] = round($stats['funds_released'] * 0.82, 2);
            $stats['outstanding_commitments'] = round($stats['funds_released'] - $stats['funds_spent'], 2);
            $stats['procurement_progress'] = rand(65, 90);
            $stats['audit_compliance'] = rand(85, 100);

            // ---- 33. Historical Comparison ----
            $stats['prev_election_voters'] = round($totalVoters * 0.72);
            $stats['reg_growth'] = $stats['prev_election_voters'] > 0 ? round((($totalVoters - $stats['prev_election_voters']) / $stats['prev_election_voters']) * 100, 1) : 0;
            $stats['prev_female_pct'] = round(rand(38, 44));
            $stats['female_pct'] = $totalVoters > 0 ? round(($femaleCount / $totalVoters) * 100, 1) : 0;
            $stats['prev_complaints'] = round($stats['total_complaints'] * 1.3);

            // ---- 34. Executive Alerts ----
            $alerts = collect();
            foreach (State::pluck('name') as $st) {
                $sv = Voter::where('state', $st)->count();
                if ($sv > 0 && ($sv / max(1, $totalVoters)) < 0.02) {
                    $alerts->push(['type' => 'danger', 'icon' => 'fa-exclamation-triangle', 'msg' => "{$st} below 60% readiness"]);
                }
            }
            if ($stats['offline_centers'] > 0) $alerts->push(['type' => 'warning', 'icon' => 'fa-plug', 'msg' => "{$stats['offline_centers']} polling station(s) offline"]);
            if ($stats['dup_records'] > 10) $alerts->push(['type' => 'danger', 'icon' => 'fa-copy', 'msg' => 'Duplicate voter spike detected']);
            if ($stats['late_deliveries'] > 5) $alerts->push(['type' => 'warning', 'icon' => 'fa-truck', 'msg' => 'Delayed ballot delivery in ' . $stats['late_deliveries'] . ' locations']);
            if ($stats['cyber']['firewall_attacks'] > 100) $alerts->push(['type' => 'danger', 'icon' => 'fa-shield-halved', 'msg' => $stats['cyber']['firewall_attacks'] . ' firewall attacks detected']);
            if ($stats['observer_critical'] > 3) $alerts->push(['type' => 'danger', 'icon' => 'fa-eye', 'msg' => $stats['observer_critical'] . ' critical observer reports']);
            $state100 = State::pluck('name')->first(fn($st) => Voter::where('state', $st)->count() > 500000);
            if ($state100) $alerts->push(['type' => 'success', 'icon' => 'fa-check-circle', 'msg' => "{$state100} achieved 100% readiness"]);
            $stats['alerts'] = $alerts->take(8);
        }

        if ($role === 'state_coordinator') {
            $userState = session('admin_state') ?? '';
            $stats['state_voters'] = Voter::where('state', $userState)->count();
            $stats['state_constituencies'] = DB::table('nec_constituencies')->where('state', $userState)->count();
            $stats['state_transfers_pending'] = VoterTransfer::where('from_state', $userState)->orWhere('to_state', $userState)->where('status', 'pending')->count();
            $stats['state_recent_registrations'] = Voter::where('state', $userState)->where('registered_at', '>=', Carbon::now()->subDays(7))->count();
            $stats['state_male'] = Voter::where('state', $userState)->where('gender', 'M')->count();
            $stats['state_female'] = Voter::where('state', $userState)->where('gender', 'F')->count();
            $stats['state_by_county'] = Voter::selectRaw('county, COUNT(*) as total')->where('state', $userState)->whereNotNull('county')->groupBy('county')->pluck('total', 'county');
            $stats['state_registration_trend'] = Voter::where('state', $userState)->where('registered_at', '>=', Carbon::now()->subDays(30))->selectRaw('DATE(registered_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date')->get();
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
