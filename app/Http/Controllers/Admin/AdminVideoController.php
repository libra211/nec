<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class AdminVideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::where('type', 'video');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $videos = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['type'] = 'video';

        Media::create($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Video created.');
    }

    public function edit($id)
    {
        $video = Media::where('type', 'video')->findOrFail($id);

        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $video = Media::where('type', 'video')->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['type'] = 'video';

        $video->update($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated.');
    }

    public function destroy($id)
    {
        $video = Media::where('type', 'video')->findOrFail($id);
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted.');
    }
}
