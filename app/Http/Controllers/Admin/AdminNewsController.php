<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        // Status filter (WordPress-style)
        $status = $request->input('status');
        if ($status === 'trash') {
            // Bypass SoftDeletes global scope to find trashed items
            $query = News::onlyTrashed();
        } elseif ($status === 'draft') {
            $query->where('status', 'draft');
        } elseif ($status === 'published') {
            $query->where('status', 'published');
        } else {
            $query->where('status', '!=', 'trash');
        }

        // Category filter
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }

        // Date range
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $news = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $categories = News::where('status', '!=', 'trash')
            ->selectRaw('DISTINCT category')
            ->pluck('category')
            ->filter()
            ->values();

        $counts = [
            'all' => News::where('status', '!=', 'trash')->count(),
            'published' => News::where('status', 'published')->count(),
            'draft' => News::where('status', 'draft')->count(),
            'trash' => News::onlyTrashed()->where('status', 'trash')->count(),
        ];

        return view('admin.news.index', compact('news', 'categories', 'counts', 'status'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'image' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['author'] = session('admin_user_name', 'Admin');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news = News::create($validated);

        $this->logActivity('news_created', "Created news: {$news->title}", $news);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article created.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $tags = $news->tags ? implode(', ', $news->tags->pluck('name')->toArray()) : '';

        return view('admin.news.edit', compact('news', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'image' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        $this->logActivity('news_updated', "Updated news: {$news->title}", $news);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->status = 'trash';
        $news->save();
        $news->delete();

        $this->logActivity('news_deleted', "Deleted news: {$news->title}", $news);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'News article moved to trash.']);
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'News article moved to trash.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('bulk_action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No items selected.');
        }

        if (empty($action)) {
            return back()->with('error', 'No action selected.');
        }

        $count = match ($action) {
            'publish' => News::whereIn('id', $ids)->update(['status' => 'published', 'published_at' => now()]),
            'draft' => News::whereIn('id', $ids)->update(['status' => 'draft']),
            'trash' => News::whereIn('id', $ids)->update(['status' => 'trash']),
            'restore' => News::onlyTrashed()->whereIn('id', $ids)->restore(),
            'delete' => News::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
            default => throw new \InvalidArgumentException("Unknown action: {$action}"),
        };

        $this->logActivity('news_bulk_action', "Bulk {$action} on {$count} news items");

        return back()->with('success', "{$count} article(s) updated.");
    }

    public function restore($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);
        $news->status = 'draft';
        $news->save();
        $news->restore();

        $this->logActivity('news_restored', "Restored news: {$news->title}", $news);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article restored.');
    }

    public function forceDelete($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);
        $news->forceDelete();

        $this->logActivity('news_force_deleted', "Permanently deleted news: {$news->title}", $news);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'News article permanently deleted.']);
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'News article permanently deleted.');
    }

    public function toggleStatus($id)
    {
        $news = News::findOrFail($id);
        $news->status = $news->status === 'published' ? 'draft' : 'published';
        if ($news->status === 'published') {
            $news->published_at = $news->published_at ?? now();
        }
        $news->save();

        $this->logActivity('news_status_changed', "Changed news {$news->title} status to {$news->status}", $news);

        return back()->with('success', 'News status toggled.');
    }
}
