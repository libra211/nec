<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminSecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SecurityLog::query();

        if ($request->filled('event_type')) {
            $query->where('event_type', InputSanitizer::clean($request->input('event_type')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('user_email', 'LIKE', "%{$search}%")
                  ->orWhere('details', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('request_uri', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }

        $logs = $query->latest()->paginate(50)->appends($request->query());
        $eventTypes = SecurityLog::distinct()->pluck('event_type')->filter()->sort()->values();

        return view('admin.security-logs.index', compact('logs', 'eventTypes'));
    }

    public function show(SecurityLog $securityLog)
    {
        return view('admin.security-logs.show', ['log' => $securityLog]);
    }

    public function destroy(SecurityLog $securityLog)
    {
        $securityLog->delete();
        return back()->with('success', 'Log entry deleted.');
    }

    public function clear(Request $request)
    {
        $before = $request->input('before_date');
        if ($before) {
            SecurityLog::where('created_at', '<', $before)->delete();
        } else {
            SecurityLog::truncate();
        }
        return back()->with('success', 'Security logs cleared.');
    }
}
