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

        if ($search = $request->input('search')) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $announcements = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
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
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }
}
