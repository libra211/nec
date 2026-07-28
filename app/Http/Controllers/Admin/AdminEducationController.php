<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationMaterial;
use Illuminate\Http\Request;

class AdminEducationController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationMaterial::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('content_type', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $materials = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.education.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.education.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'content_type' => 'nullable|in:document,video,infographic,poster,presentation,other',
            'language' => 'nullable|string|max:50',
            'target_audience' => 'nullable|string|max:100',
            'status' => 'required|in:published,draft,trash',
        ]);

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
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'content_type' => 'nullable|in:document,video,infographic,poster,presentation,other',
            'language' => 'nullable|string|max:50',
            'target_audience' => 'nullable|string|max:100',
            'status' => 'required|in:published,draft,trash',
        ]);

        $material->update($validated);

        return redirect()->route('admin.education.index')->with('success', 'Education material updated.');
    }

    public function destroy($id)
    {
        $material = EducationMaterial::findOrFail($id);
        $material->delete();

        return redirect()->route('admin.education.index')->with('success', 'Education material deleted.');
    }
}
