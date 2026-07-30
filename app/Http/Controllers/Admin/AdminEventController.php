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

        if ($status === 'trash') $query = Event::onlyTrashed();
        elseif ($status === 'draft') $query->where('status', 'draft');
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
            'trash' => Event::onlyTrashed()->where('status', 'trash')->count(),
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
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = asset('storage/' . $path);
        }

        $event = Event::create($validated);

        $this->logActivity('event_created', "Created event: {$event->title}", $event);

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
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events', 'public');
            $validated['featured_image'] = asset('storage/' . $path);
        } elseif ($request->input('remove_image') === '1') {
            $validated['featured_image'] = null;
        }

        $event->update($validated);

        $this->logActivity('event_updated', "Updated event: {$event->title}", $event);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'trash';
        $event->save();
        $event->delete();

        $this->logActivity('event_deleted', "Deleted event: {$event->title}", $event);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Event moved to trash.']);
        }

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

        $this->logActivity('event_bulk_action', "Bulk {$action} on {$count} events");

        return back()->with('success', "{$count} event(s) updated.");
    }

    public function toggleStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = $event->status === 'published' ? 'draft' : 'published';
        $event->save();

        $this->logActivity('event_status_changed', "Changed event {$event->title} status to {$event->status}", $event);

        return back()->with('success', 'Event status toggled.');
    }

    public function restore($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->status = 'draft';
        $event->save();
        $event->restore();

        $this->logActivity('event_restored', "Restored event: {$event->title}", $event);

        return redirect()->route('admin.events.index')->with('success', 'Event restored.');
    }

    public function forceDelete($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->forceDelete();

        $this->logActivity('event_force_deleted', "Permanently deleted event: {$event->title}", $event);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Event permanently deleted.']);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event permanently deleted.');
    }
}
