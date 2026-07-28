<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commissioner;
use Illuminate\Http\Request;

class AdminCommissionerController extends Controller
{
    public function index(Request $request)
    {
        $query = Commissioner::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $commissioners = $query->orderBy('order_num')->orderBy('name')->paginate(15);

        return view('admin.commissioners.index', compact('commissioners'));
    }

    public function create()
    {
        return view('admin.commissioners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'order_num' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        Commissioner::create($validated);

        return redirect()->route('admin.commissioners.index')->with('success', 'Commissioner added.');
    }

    public function edit($id)
    {
        $commissioner = Commissioner::findOrFail($id);

        return view('admin.commissioners.edit', compact('commissioner'));
    }

    public function update(Request $request, $id)
    {
        $commissioner = Commissioner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'order_num' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $commissioner->update($validated);

        return redirect()->route('admin.commissioners.index')->with('success', 'Commissioner updated.');
    }

    public function destroy($id)
    {
        $commissioner = Commissioner::findOrFail($id);
        $commissioner->delete();

        return redirect()->route('admin.commissioners.index')->with('success', 'Commissioner deleted.');
    }
}
