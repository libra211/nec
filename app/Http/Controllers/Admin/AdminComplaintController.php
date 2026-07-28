<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::query();

        if ($request->filled('status')) {
            $query->where('status', InputSanitizer::clean($request->input('status')));
        }
        if ($request->filled('category')) {
            $query->where('category', InputSanitizer::clean($request->input('category')));
        }
        if ($request->filled('priority')) {
            $query->where('priority', InputSanitizer::clean($request->input('priority')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $complaints = $query->latest('created_at')->paginate(20)->appends($request->query());
        $counts = [
            'total' => Complaint::count(),
            'new' => Complaint::where('status', 'new')->count(),
            'open' => Complaint::where('status', 'open')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'escalated' => Complaint::where('status', 'escalated')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'counts'));
    }

    public function show(Complaint $complaint)
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,open,in_progress,resolved,closed,escalated',
            'resolution' => 'nullable|string|max:5000',
        ]);

        $complaint->status = $validated['status'];
        if ($validated['status'] === 'resolved' || $validated['status'] === 'closed') {
            $complaint->resolved_at = now();
            $complaint->resolved_by = session('admin_user_name');
        }
        if (!empty($validated['resolution'])) {
            $complaint->resolution = $validated['resolution'];
        }
        $complaint->save();

        return back()->with('success', 'Complaint status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])) . '.');
    }
}
