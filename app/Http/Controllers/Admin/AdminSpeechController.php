<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speech;
use Illuminate\Http\Request;

class AdminSpeechController extends Controller
{
    public function index(Request $request)
    {
        $query = Speech::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('speaker', 'LIKE', "%{$search}%")
                  ->orWhere('event_name', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $speeches = $query->orderByDesc('speech_date')->paginate(15);

        return view('admin.speeches.index', compact('speeches'));
    }

    public function create()
    {
        return view('admin.speeches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'event_name' => 'nullable|string|max:255',
            'speech_date' => 'nullable|date',
            'document_url' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

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
            'speaker' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'event_name' => 'nullable|string|max:255',
            'speech_date' => 'nullable|date',
            'document_url' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft,trash',
        ]);

        $speech->update($validated);

        return redirect()->route('admin.speeches.index')->with('success', 'Speech updated.');
    }

    public function destroy($id)
    {
        $speech = Speech::findOrFail($id);
        $speech->delete();

        return redirect()->route('admin.speeches.index')->with('success', 'Speech deleted.');
    }
}
