<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();
        $status = $request->input('status');

        if ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'trash') $query->where('status', 'trash');
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('album', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($album = $request->input('album')) {
            $query->where('album', $album);
        }

        $galleries = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'all' => Gallery::where('status', '!=', 'trash')->count(),
            'published' => Gallery::where('status', 'published')->count(),
            'draft' => Gallery::where('status', 'draft')->count(),
            'trash' => Gallery::where('status', 'trash')->count(),
        ];

        $albums = Gallery::whereNotNull('album')->distinct()->pluck('album')->sort()->values();

        return view('admin.gallery.index', compact('galleries', 'albums', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string|max:500',
            'album' => 'nullable|string|max:100',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $gallery = Gallery::create($validated);

        $this->logActivity('gallery_created', "Created gallery: {$gallery->title}", $gallery);

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
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string|max:500',
            'album' => 'nullable|string|max:100',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $gallery->update($validated);

        $this->logActivity('gallery_updated', "Updated gallery: {$gallery->title}", $gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = 'trash';
        $gallery->save();
        $gallery->delete();

        $this->logActivity('gallery_deleted', "Deleted gallery: {$gallery->title}", $gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => Gallery::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => Gallery::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => Gallery::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => Gallery::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => Gallery::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        $this->logActivity('gallery_bulk_action', "Bulk {$action} on {$count} gallery items");

        return back()->with('success', "{$count} image(s) updated.");
    }

    public function toggleStatus($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = $gallery->status === 'published' ? 'draft' : 'published';
        $gallery->save();

        $this->logActivity('gallery_status_changed', "Changed gallery {$gallery->title} status to {$gallery->status}", $gallery);

        return back()->with('success', 'Image status toggled.');
    }

    public function restore($id)
    {
        $gallery = Gallery::onlyTrashed()->findOrFail($id);
        $gallery->status = 'draft';
        $gallery->save();
        $gallery->restore();

        $this->logActivity('gallery_restored', "Restored gallery: {$gallery->title}", $gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Image restored.');
    }

    public function forceDelete($id)
    {
        $gallery = Gallery::onlyTrashed()->findOrFail($id);
        $gallery->forceDelete();

        $this->logActivity('gallery_force_deleted', "Permanently deleted gallery: {$gallery->title}", $gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Image permanently deleted.');
    }
}
