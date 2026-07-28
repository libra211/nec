<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class AdminGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('album', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($album = $request->input('album')) {
            $query->where('album', $album);
        }

        $galleries = $query->orderByDesc('created_at')->paginate(15);

        $albums = Gallery::whereNotNull('album')->distinct()->pluck('album')->sort()->values();

        return view('admin.gallery.index', compact('galleries', 'albums'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string|max:500',
            'album' => 'nullable|string|max:100',
            'status' => 'required|in:published,draft,trash',
        ]);

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image created.');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);

        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string|max:500',
            'album' => 'nullable|string|max:100',
            'status' => 'required|in:published,draft,trash',
        ]);

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image deleted.');
    }
}
