<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();
        $status = $request->input('status');

        if ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'trash') $query->where('status', 'trash');
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->orderByDesc('start_date')->paginate(20)->withQueryString();

        $counts = [
            'all' => Event::where('status', '!=', 'trash')->count(),
            'published' => Event::where('status', 'published')->count(),
            'draft' => Event::where('status', 'draft')->count(),
            'trash' => Event::where('status', 'trash')->count(),
        ];

        return view('admin.events.index', compact('events', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
            'event_type' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
            'event_type' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'trash';
        $event->save();
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => Event::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => Event::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => Event::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => Event::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => Event::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        return back()->with('success', "{$count} event(s) updated.");
    }

    public function toggleStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = $event->status === 'published' ? 'draft' : 'published';
        $event->save();

        return back()->with('success', 'Event status toggled.');
    }

    public function restore($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->status = 'draft';
        $event->save();
        $event->restore();

        return redirect()->route('admin.events.index')->with('success', 'Event restored.');
    }

    public function forceDelete($id)
    {
        Event::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.events.index')->with('success', 'Event permanently deleted.');
    }
}
