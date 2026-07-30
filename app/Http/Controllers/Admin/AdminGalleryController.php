<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryAlbum::query();
        $status = $request->input('status');

        if ($status === 'trash') $query = GalleryAlbum::onlyTrashed();
        elseif ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $albums = $query->withCount('images')->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'all' => GalleryAlbum::where('status', '!=', 'trash')->count(),
            'published' => GalleryAlbum::where('status', 'published')->count(),
            'draft' => GalleryAlbum::where('status', 'draft')->count(),
            'trash' => GalleryAlbum::onlyTrashed()->count(),
        ];

        return view('admin.gallery.index', compact('albums', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_albums,slug',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $albumData = array_filter($validated, fn($key) => !in_array($key, ['images', 'existing_images', 'sort_order']), ARRAY_FILTER_USE_KEY);
        $albumData['slug'] = $albumData['slug'] ?: Str::slug($albumData['title']);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('gallery/featured', 'public');
            $albumData['featured_image'] = 'storage/' . $path;
        }

        $album = GalleryAlbum::create($albumData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('gallery', 'public');
                $album->images()->create([
                    'title' => $file->getClientOriginalName(),
                    'image_path' => 'storage/' . $path,
                    'alt_text' => $file->getClientOriginalName(),
                    'sort_order' => $i,
                    'status' => $albumData['status'],
                ]);
            }
        }

        // If no featured image set, use first uploaded image
        if (!$album->featured_image && $album->images()->exists()) {
            $album->featured_image = $album->images()->value('image_path');
            $album->save();
        }

        $this->logActivity('gallery_created', "Created gallery album: {$album->title}", $album);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery album created.');
    }

    public function edit($id)
    {
        $album = GalleryAlbum::withTrashed()->findOrFail($id);
        $album->load('images');
        return view('admin.gallery.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        $album = GalleryAlbum::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_albums,slug,' . $id,
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'existing_images' => 'nullable|array',
            'sort_order' => 'nullable|array',
        ]);

        $albumData = array_filter($validated, fn($key) => !in_array($key, ['images', 'existing_images', 'sort_order']), ARRAY_FILTER_USE_KEY);
        $albumData['slug'] = $albumData['slug'] ?: Str::slug($albumData['title']);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('gallery/featured', 'public');
            $albumData['featured_image'] = 'storage/' . $path;
        } elseif ($request->input('remove_featured') === '1') {
            $albumData['featured_image'] = null;
        }

        $album->update($albumData);

        // Handle existing images (reorder + delete removed ones)
        $existingIds = $request->input('existing_images', []);
        $sortOrder = $request->input('sort_order', []);

        // Delete images not in the existing list
        if ($existingIds) {
            $album->images()->whereNotIn('id', $existingIds)->delete();
        }

        // Update sort order for kept images
        foreach ($sortOrder as $imgId => $order) {
            Gallery::where('id', $imgId)->where('gallery_album_id', $album->id)->update(['sort_order' => $order]);
        }

        // Upload new images
        if ($request->hasFile('images')) {
            $maxSort = $album->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('gallery', 'public');
                $album->images()->create([
                    'title' => $file->getClientOriginalName(),
                    'image_path' => 'storage/' . $path,
                    'alt_text' => $file->getClientOriginalName(),
                    'sort_order' => $maxSort + 1 + $i,
                    'status' => $album->status,
                ]);
            }
        }

        // Ensure featured image is set
        if (!$album->featured_image && $album->images()->exists()) {
            $album->featured_image = $album->images()->value('image_path');
            $album->save();
        }

        $this->logActivity('gallery_updated', "Updated gallery album: {$album->title}", $album);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery album updated.');
    }

    public function destroy($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $album->status = 'trash';
        $album->save();
        $album->delete();

        $this->logActivity('gallery_deleted', "Deleted gallery album: {$album->title}", $album);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Album moved to trash.']);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery album moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => GalleryAlbum::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => GalleryAlbum::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => GalleryAlbum::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => GalleryAlbum::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => GalleryAlbum::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        $this->logActivity('gallery_bulk_action', "Bulk {$action} on {$count} gallery albums");

        return back()->with('success', "{$count} album(s) updated.");
    }

    public function toggleStatus($id)
    {
        $album = GalleryAlbum::withTrashed()->findOrFail($id);
        $album->status = $album->status === 'published' ? 'draft' : 'published';
        $album->save();

        $this->logActivity('gallery_status_changed', "Changed gallery album {$album->title} status to {$album->status}", $album);

        return back()->with('success', 'Album status toggled.');
    }

    public function restore($id)
    {
        $album = GalleryAlbum::onlyTrashed()->findOrFail($id);
        $album->status = 'draft';
        $album->save();
        $album->restore();

        $this->logActivity('gallery_restored', "Restored gallery album: {$album->title}", $album);

        return redirect()->route('admin.gallery.index')->with('success', 'Album restored.');
    }

    public function forceDelete($id)
    {
        $album = GalleryAlbum::onlyTrashed()->findOrFail($id);
        $album->images()->forceDelete();
        $album->forceDelete();

        $this->logActivity('gallery_force_deleted', "Permanently deleted gallery album: {$album->title}", $album);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Album permanently deleted.']);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Album permanently deleted.');
    }
}
