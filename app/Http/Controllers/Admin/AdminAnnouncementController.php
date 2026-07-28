<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();
        $status = $request->input('status');

        if ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'trash') $query->where('status', 'trash');
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $announcements = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'all' => Announcement::where('status', '!=', 'trash')->count(),
            'published' => Announcement::where('status', 'published')->count(),
            'draft' => Announcement::where('status', 'draft')->count(),
            'trash' => Announcement::where('status', 'trash')->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'type' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created.');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'type' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 'trash';
        $announcement->save();
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => Announcement::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => Announcement::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => Announcement::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => Announcement::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => Announcement::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        return back()->with('success', "{$count} announcement(s) updated.");
    }

    public function toggleStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = $announcement->status === 'published' ? 'draft' : 'published';
        $announcement->save();

        return back()->with('success', 'Announcement status toggled.');
    }

    public function restore($id)
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->status = 'draft';
        $announcement->save();
        $announcement->restore();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement restored.');
    }

    public function forceDelete($id)
    {
        Announcement::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement permanently deleted.');
    }
}
