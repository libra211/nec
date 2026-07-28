<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEducationController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationMaterial::query();
        $status = $request->input('status');

        if ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'trash') $query->onlyTrashed(); // no trash status column, use soft-delete built-in
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('content_type', 'LIKE', "%{$search}%");
            });
        }

        $materials = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'all' => EducationMaterial::where('status', '!=', 'trash')->count(),
            'published' => EducationMaterial::where('status', 'published')->count(),
            'draft' => EducationMaterial::where('status', 'draft')->count(),
            'trash' => EducationMaterial::onlyTrashed()->count(),
        ];

        return view('admin.education.index', compact('materials', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.education.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'content_type' => 'nullable|in:document,video,infographic,poster,presentation,other',
            'language' => 'nullable|string|max:50',
            'target_audience' => 'nullable|string|max:100',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        EducationMaterial::create($validated);

        return redirect()->route('admin.education.index')->with('success', 'Education material created.');
    }

    public function edit($id)
    {
        $material = EducationMaterial::findOrFail($id);
        return view('admin.education.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = EducationMaterial::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'content_type' => 'nullable|in:document,video,infographic,poster,presentation,other',
            'language' => 'nullable|string|max:50',
            'target_audience' => 'nullable|string|max:100',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $material->update($validated);

        return redirect()->route('admin.education.index')->with('success', 'Education material updated.');
    }

    public function destroy($id)
    {
        $material = EducationMaterial::findOrFail($id);
        $material->status = 'trash';
        $material->save();
        $material->delete();

        return redirect()->route('admin.education.index')->with('success', 'Education material moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => EducationMaterial::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => EducationMaterial::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => EducationMaterial::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => EducationMaterial::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => EducationMaterial::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        return back()->with('success', "{$count} material(s) updated.");
    }

    public function toggleStatus($id)
    {
        $material = EducationMaterial::findOrFail($id);
        $material->status = $material->status === 'published' ? 'draft' : 'published';
        $material->save();

        return back()->with('success', 'Material status toggled.');
    }

    public function restore($id)
    {
        $material = EducationMaterial::onlyTrashed()->findOrFail($id);
        $material->status = 'draft';
        $material->save();
        $material->restore();

        return redirect()->route('admin.education.index')->with('success', 'Material restored.');
    }

    public function forceDelete($id)
    {
        EducationMaterial::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.education.index')->with('success', 'Material permanently deleted.');
    }
}
