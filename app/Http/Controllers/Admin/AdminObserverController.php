<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Observer;
use Illuminate\Http\Request;

class AdminObserverController extends Controller
{
    public function index(Request $request)
    {
        $query = Observer::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('other_names', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $observers = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.observers.index', compact('observers'));
    }

    public function show($id)
    {
        $observer = Observer::findOrFail($id);

        return view('admin.observers.show', compact('observer'));
    }

    public function updateStatus(Request $request, $id)
    {
        $observer = Observer::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,verified,accredited,rejected,trash',
        ]);

        $observer->update(['status' => $validated['status']]);

        $this->logActivity('observer_status_changed', "Changed observer {$observer->email} status to {$validated['status']}", $observer);

        return back()->with('success', 'Observer status updated.');
    }
}
