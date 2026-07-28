<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::published()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->paginate(12);

        $pastEvents = Event::published()
            ->where('start_date', '<', now())
            ->orderByDesc('start_date')
            ->paginate(12);

        return view('events.index', compact('events', 'pastEvents'));
    }

    public function show($slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        $event->incrementViews();

        $upcoming = Event::published()
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(4)
            ->get();

        return view('events.show', compact('event', 'upcoming'));
    }
}
