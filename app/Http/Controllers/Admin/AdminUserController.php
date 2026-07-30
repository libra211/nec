<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    private $roles = [
        'super_admin',
        'admin',
        'state_coordinator',
        'constituency_officer',
        'registration_officer',
        'polling_officer',
        'data_entry',
        'content_editor',
        'viewer',
    ];

    public function index(Request $request)
    {
        $query = User::query()->whereNull('deleted_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('employee_id', 'LIKE', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($department = $request->input('department')) {
            $query->where('department', $department);
        }

        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        $users = $query->orderBy('name')->paginate(20);
        $roles = $this->roles;
        $states = \App\Models\State::orderBy('name')->get();
        $departments = User::whereNull('deleted_at')->whereNotNull('department')->distinct()->pluck('department')->filter()->sort()->values();

        return view('admin.users.index', compact('users', 'roles', 'states', 'departments'));
    }

    public function create()
    {
        $roles = $this->roles;
        $states = \App\Models\State::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:nec_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', $this->roles),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'employee_id' => 'nullable|string|max:50|unique:nec_users,employee_id',
            'photo' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_at'] = now();

        $user = User::create($validated);

        $this->logActivity('user_created', "Created user: {$user->name} ({$user->email})", $user);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->whereNull('deleted_at')->findOrFail($id);
        $roles = $this->roles;
        $states = \App\Models\State::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'states'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:nec_users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', $this->roles),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'employee_id' => 'nullable|string|max:50|unique:nec_users,employee_id,' . $id,
            'photo' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $oldValues = $user->only(['name', 'email', 'role', 'status', 'department', 'state', 'position']);

        $user->update($validated);

        $newValues = $user->only(['name', 'email', 'role', 'status', 'department', 'state', 'position']);
        $this->logActivity('user_updated', "Updated user: {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        if ($user->id === session('admin_user_id')) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->update(['deleted_at' => now()]);

        $this->logActivity('user_deleted', "Soft deleted user: {$userName}", $user);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $userName = $user->name;

        $user->update(['deleted_at' => null]);

        $this->logActivity('user_restored', "Restored user: {$userName}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->id === session('admin_user_id')) {
            return back()->with('error', 'You cannot permanently delete your own account.');
        }

        $userName = $user->name;
        $user->forceDelete();

        $this->logActivity('user_force_deleted', "Permanently deleted user: {$userName}");

        return redirect()->route('admin.users.trashed')->with('success', 'User permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
        ]);

        $action = $validated['action'];
        $userIds = $validated['user_ids'];
        $count = 0;

        foreach ($userIds as $userId) {
            $user = User::where('id', $userId)->whereNull('deleted_at')->first();
            if (!$user || $user->id === session('admin_user_id')) {
                continue;
            }

            match ($action) {
                'activate' => $user->update(['status' => 'active']),
                'deactivate' => $user->update(['status' => 'inactive']),
                'delete' => $user->update(['deleted_at' => now()]),
            };

            $count++;
        }

        $this->logActivity('user_bulk_action', "Bulk {$action} on {$count} users");

        return back()->with('success', "{$count} users processed successfully.");
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        $this->logActivity('user_password_reset', "Reset password for user: {$user->name}", $user);

        return back()->with('success', 'Password reset successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user->update(['status' => $validated['status']]);

        $this->logActivity('user_status_changed', "Changed status of {$user->name} to {$validated['status']}", $user);

        return back()->with('success', 'User status updated.');
    }
}
