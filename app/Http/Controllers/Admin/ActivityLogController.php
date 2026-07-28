<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('user_email', 'LIKE', "%{$search}%")
                    ->orWhere('action', 'LIKE', "%{$search}%")
                    ->orWhere('details', 'LIKE', "%{$search}%")
                    ->orWhere('entity_type', 'LIKE', "%{$search}%")
                    ->orWhere('ip_address', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->latest('created_at')->paginate(20)->appends($request->query());
        $uniqueActions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'uniqueActions'));
    }

    public function show(ActivityLog $log)
    {
        return view('admin.activity-logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->latest('created_at')->get();

        $csv = "Date,User Email,Action,Entity Type,Entity ID,Details,IP Address\n";
        foreach ($logs as $log) {
            $csv .= implode(',', [
                $log->created_at?->format('Y-m-d H:i:s') ?? '',
                '"' . str_replace('"', '""', $log->user_email ?? '') . '"',
                '"' . str_replace('"', '""', $log->action ?? '') . '"',
                '"' . str_replace('"', '""', $log->entity_type ?? '') . '"',
                $log->entity_id ?? '',
                '"' . str_replace('"', '""', $log->details ?? '') . '"',
                '"' . str_replace('"', '""', $log->ip_address ?? '') . '"',
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="activity_logs_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function destroy(ActivityLog $log)
    {
        $log->delete();
        return back()->with('success', 'Log entry deleted.');
    }

    public function clear(Request $request)
    {
        ActivityLog::truncate();
        return back()->with('success', 'All activity logs cleared.');
    }
}
