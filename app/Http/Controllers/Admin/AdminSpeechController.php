<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speech;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSpeechController extends Controller
{
    public function index(Request $request)
    {
        $query = Speech::query();
        $status = $request->input('status');

        if ($status === 'draft') $query->where('status', 'draft');
        elseif ($status === 'trash') $query->where('status', 'trash');
        elseif ($status === 'published') $query->where('status', 'published');
        else $query->where('status', '!=', 'trash');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('speaker', 'LIKE', "%{$search}%")
                  ->orWhere('event_name', 'LIKE', "%{$search}%");
            });
        }

        $speeches = $query->orderByDesc('speech_date')->paginate(20)->withQueryString();

        $counts = [
            'all' => Speech::where('status', '!=', 'trash')->count(),
            'published' => Speech::where('status', 'published')->count(),
            'draft' => Speech::where('status', 'draft')->count(),
            'trash' => Speech::where('status', 'trash')->count(),
        ];

        return view('admin.speeches.index', compact('speeches', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.speeches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'event_name' => 'nullable|string|max:255',
            'speech_date' => 'nullable|date',
            'document_url' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        Speech::create($validated);

        return redirect()->route('admin.speeches.index')->with('success', 'Speech created.');
    }

    public function edit($id)
    {
        $speech = Speech::findOrFail($id);
        return view('admin.speeches.edit', compact('speech'));
    }

    public function update(Request $request, $id)
    {
        $speech = Speech::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'event_name' => 'nullable|string|max:255',
            'speech_date' => 'nullable|date',
            'document_url' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $speech->update($validated);

        return redirect()->route('admin.speeches.index')->with('success', 'Speech updated.');
    }

    public function destroy($id)
    {
        $speech = Speech::findOrFail($id);
        $speech->status = 'trash';
        $speech->save();
        $speech->delete();

        return redirect()->route('admin.speeches.index')->with('success', 'Speech moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) return back()->with('error', 'No items selected.');

        $count = match ($action) {
            'publish' => Speech::whereIn('id', $ids)->update(['status' => 'published']),
            'draft' => Speech::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => Speech::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => Speech::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => Speech::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        return back()->with('success', "{$count} speech(es) updated.");
    }

    public function toggleStatus($id)
    {
        $speech = Speech::findOrFail($id);
        $speech->status = $speech->status === 'published' ? 'draft' : 'published';
        $speech->save();

        return back()->with('success', 'Speech status toggled.');
    }

    public function restore($id)
    {
        $speech = Speech::onlyTrashed()->findOrFail($id);
        $speech->status = 'draft';
        $speech->save();
        $speech->restore();

        return redirect()->route('admin.speeches.index')->with('success', 'Speech restored.');
    }

    public function forceDelete($id)
    {
        Speech::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.speeches.index')->with('success', 'Speech permanently deleted.');
    }
}
