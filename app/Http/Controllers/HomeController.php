<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Announcement;
use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Commissioner;
use App\Models\Constituency;
use App\Models\Download;
use App\Models\ElectionEvent;
use App\Models\EducationMaterial;
use App\Models\Gallery;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\Observer;
use App\Models\ObserverApplication;
use App\Models\PoliticalParty;
use App\Models\PollingStaff;
use App\Models\PollingStation;
use App\Models\Result;
use App\Models\Speech;
use App\Models\Subscriber;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function publicStatValue(string $stat, $autoValue)
    {
        $show = \App\Helpers\NecHelper::setting_get("public_show_{$stat}", '1');
        if ($show !== '1') {
            return null;
        }
        $source = \App\Helpers\NecHelper::setting_get("public_stat_{$stat}_source", 'auto');
        if ($source === 'manual') {
            $manual = \App\Helpers\NecHelper::setting_get("public_stat_{$stat}_value", '');
            return $manual !== '' ? $manual : $autoValue;
        }
        return $autoValue;
    }

    public function index()
    {
        $autoStat = fn(string $stat) => match ($stat) {
            'total_voters' => Voter::count(),
            'constituencies' => Constituency::count(),
            'polling_stations' => PollingStation::count(),
            'parties' => PoliticalParty::count(),
            'candidates' => Candidate::count(),
            'observers' => Observer::count(),
            'ballot_types' => Ballot::count(),
            'agents' => Agent::where('status', 'active')->count(),
            'commissioners' => Commissioner::count(),
            'polling_staff' => PollingStaff::count(),
            'trained_staff' => PollingStaff::where('trained', true)->count(),
            'counties' => DB::table('nec_counties')->count(),
            'payams' => DB::table('nec_payams')->count(),
            'states_with_data' => Voter::whereNotNull('state')->distinct()->count('state'),
            'news' => News::where('status', 'published')->count(),
            'events' => ElectionEvent::where('start_date', '>=', now())->count(),
            'gallery' => GalleryAlbum::count(),
            'downloads' => Download::count(),
            'speeches' => Speech::count(),
            'subscribers' => Subscriber::count(),
            'observer_apps' => ObserverApplication::count(),
            default => 0,
        };

        $stats = [
            'voters' => $this->publicStatValue('total_voters', $autoStat('total_voters')),
            'constituencies' => $this->publicStatValue('constituencies', $autoStat('constituencies')),
            'polling_stations' => $this->publicStatValue('polling_stations', $autoStat('polling_stations')),
            'parties' => $this->publicStatValue('parties', $autoStat('parties')),
            'candidates' => $this->publicStatValue('candidates', $autoStat('candidates')),
            'observers' => $this->publicStatValue('observers', $autoStat('observers')),
        ];

        $latestNews = News::where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $latestAnnouncements = Announcement::where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $latestResults = Result::orderByDesc('created_at')
            ->limit(5)
            ->get();

        $upcomingEvents = ElectionEvent::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        $topDownloads = Download::where('status', 'published')
            ->orderByDesc('downloads_count')
            ->limit(6)
            ->get();

        $educationResources = EducationMaterial::where('status', 'published')
            ->whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->orderBy('title')
            ->get();

        $topDownloads = $educationResources->merge($topDownloads)->take(8);

        $electionDate = $this->publicStatValue('election_date', \App\Helpers\NecHelper::setting_get('election_date', '2026-12-22'));
        $electionType = $this->publicStatValue('election_type', \App\Helpers\NecHelper::setting_get('election_type', 'General Elections'));

        $galleryAlbums = GalleryAlbum::withCount('images')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $commissioners = Commissioner::where('status', 'active')
            ->orderBy('order_num')
            ->get();

        $politicalParties = PoliticalParty::where('status', 'active')
            ->orderBy('name')
            ->limit(8)
            ->get();

        return view('home', compact(
            'stats',
            'latestNews',
            'latestAnnouncements',
            'latestResults',
            'upcomingEvents',
            'topDownloads',
            'electionDate',
            'electionType',
            'galleryAlbums',
            'commissioners',
            'politicalParties'
        ));
    }
}
