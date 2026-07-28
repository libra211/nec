<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }
        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('employee_id', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $staff = $query->latest('created_at')->paginate(20)->appends($request->query());
        $roleCounts = User::selectRaw('role, COUNT(*) as total')->groupBy('role')->pluck('total', 'role');

        return view('admin.staff.index', compact('staff', 'roleCounts'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:nec_users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin,state_coordinator,constituency_officer,registration_officer,polling_officer,data_entry,content_editor,viewer',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'phone' => $request->input('phone'),
            'department' => $request->input('department'),
            'state' => $request->input('state'),
            'employee_id' => 'EMP' . strtoupper(uniqid()),
            'status' => 'active',
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show(User $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:nec_users,email,{$staff->id}",
            'role' => 'required|in:super_admin,admin,state_coordinator,constituency_officer,registration_officer,polling_officer,data_entry,content_editor,viewer',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'phone' => $request->input('phone'),
            'department' => $request->input('department'),
            'state' => $request->input('state'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted.');
    }

    public function updateStatus(Request $request, User $staff)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended']);

        $staff->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function assign(Request $request, User $staff)
    {
        $request->validate([
            'state' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
        ]);

        $staff->update([
            'state' => $request->input('state'),
            'department' => $request->input('department'),
        ]);

        return back()->with('success', 'Assignment updated successfully.');
    }

    public function activity(User $staff)
    {
        $activities = \App\Models\ActivityLog::where('user_email', $staff->email)
            ->latest('created_at')
            ->paginate(20);

        return view('admin.staff.activity', compact('staff', 'activities'));
    }
}
