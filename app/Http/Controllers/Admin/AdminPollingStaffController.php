<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PollingStaff;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPollingStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = PollingStaff::query();

        if ($request->filled('status')) {
            $query->where('status', InputSanitizer::clean($request->input('status')));
        }
        if ($request->filled('state')) {
            $query->where('state', InputSanitizer::clean($request->input('state')));
        }
        if ($request->filled('role')) {
            $query->where('role', InputSanitizer::clean($request->input('role')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $staff = $query->latest()->paginate(20)->appends($request->query());
        $states = DB::table('nec_states')->orderBy('name')->get();

        return view('admin.polling-staff.index', compact('staff', 'states'));
    }

    public function create()
    {
        $states = DB::table('nec_states')->orderBy('name')->get();
        return view('admin.polling-staff.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'polling_station_id' => 'nullable|integer',
            'assignment_date' => 'nullable|date',
            'trained' => 'boolean',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $staff = PollingStaff::create(InputSanitizer::clean($validated));

        $this->logActivity('polling_staff_created', "Created polling staff: {$staff->full_name}", $staff);

        return redirect()->route('admin.polling-staff.index')->with('success', 'Polling staff member added successfully.');
    }

    public function edit(PollingStaff $pollingStaff)
    {
        $states = DB::table('nec_states')->orderBy('name')->get();
        return view('admin.polling-staff.edit', ['staff' => $pollingStaff, 'states' => $states]);
    }

    public function update(Request $request, PollingStaff $pollingStaff)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'polling_station_id' => 'nullable|integer',
            'assignment_date' => 'nullable|date',
            'trained' => 'boolean',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $pollingStaff->update(InputSanitizer::clean($validated));

        $this->logActivity('polling_staff_updated', "Updated polling staff: {$pollingStaff->full_name}", $pollingStaff);

        return redirect()->route('admin.polling-staff.index')->with('success', 'Polling staff updated successfully.');
    }

    public function destroy(PollingStaff $pollingStaff)
    {
        $pollingStaff->delete();
        $this->logActivity('polling_staff_deleted', "Deleted polling staff: {$pollingStaff->full_name}", $pollingStaff);
        return back()->with('success', 'Polling staff member removed.');
    }

    public function updateStatus(PollingStaff $pollingStaff)
    {
        $pollingStaff->status = $pollingStaff->status === 'active' ? 'inactive' : 'active';
        $pollingStaff->save();
        $this->logActivity('polling_staff_status_changed', "Changed polling staff {$pollingStaff->full_name} status to {$pollingStaff->status}", $pollingStaff);
        return back()->with('success', 'Status updated to ' . ucfirst($pollingStaff->status) . '.');
    }
}
