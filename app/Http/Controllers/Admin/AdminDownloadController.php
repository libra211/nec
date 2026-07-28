<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminDownloadController extends Controller
{
    public function index(Request $request)
    {
        $query = Download::query();

        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $downloads = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('admin.downloads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file_path' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'file_size' => 'nullable|integer|min:0',
            'file_type' => 'nullable|string|max:50',
            'download_count' => 'nullable|integer|min:0',
        ]);

        Download::create(InputSanitizer::clean($validated));

        return redirect()->route('admin.downloads.index')->with('success', 'Download resource added successfully.');
    }

    public function edit(Download $download)
    {
        return view('admin.downloads.edit', compact('download'));
    }

    public function update(Request $request, Download $download)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file_path' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'file_size' => 'nullable|integer|min:0',
            'file_type' => 'nullable|string|max:50',
        ]);

        $download->update(InputSanitizer::clean($validated));

        return redirect()->route('admin.downloads.index')->with('success', 'Download resource updated.');
    }

    public function destroy(Download $download)
    {
        $download->delete();
        return back()->with('success', 'Download resource deleted.');
    }
}
