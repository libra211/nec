<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                  ->orWhere('answer', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $faqs = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(15);

        $categories = Faq::whereNotNull('category')->distinct()->pluck('category')->sort()->values();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:published,draft,trash',
        ]);

        $faq = Faq::create($validated);

        $this->logActivity('faq_created', "Created FAQ: {$faq->question}", $faq);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);

        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:published,draft,trash',
        ]);

        $faq->update($validated);

        $this->logActivity('faq_updated', "Updated FAQ: {$faq->question}", $faq);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        $this->logActivity('faq_deleted', "Deleted FAQ: {$faq->question}", $faq);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'FAQ deleted.']);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $position => $id) {
            Faq::where('id', $id)->update(['sort_order' => $position]);
        }

        $this->logActivity('faq_reordered', "Reordered FAQs");

        return response()->json(['success' => true]);
    }
}
