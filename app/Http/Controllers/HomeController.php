<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\Constituency;
use App\Models\Download;
use App\Models\ElectionEvent;
use App\Models\News;
use App\Models\Observer;
use App\Models\PoliticalParty;
use App\Models\PollingStation;
use App\Models\Result;
use App\Models\Voter;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'voters' => Voter::count(),
            'constituencies' => Constituency::count(),
            'polling_stations' => PollingStation::count(),
            'parties' => PoliticalParty::count(),
            'candidates' => Candidate::count(),
            'observers' => Observer::count(),
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
            ->limit(5)
            ->get();

        $topDownloads = Download::where('status', 'published')
            ->orderByDesc('downloads_count')
            ->limit(6)
            ->get();

        return view('home', compact(
            'stats',
            'latestNews',
            'latestAnnouncements',
            'latestResults',
            'upcomingEvents',
            'topDownloads'
        ));
    }
}
