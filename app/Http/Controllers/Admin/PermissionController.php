<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');
        $roles = $this->getRoles();
        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role['slug']] = Permission::forRole($role['slug']);
        }

        return view('admin.permissions.index', compact('permissions', 'roles', 'rolePermissions'));
    }

    public function update(Request $request, string $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:nec_permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        DB::table('nec_role_permissions')->where('role', $role)->delete();

        foreach ($permissionIds as $permId) {
            DB::table('nec_role_permissions')->insert([
                'role' => $role,
                'permission_id' => $permId,
                'created_at' => now(),
            ]);
        }

        Permission::flushCache($role);
        $this->logActivity('permissions_updated', "Updated permissions for role: {$role}");

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permissions updated for role: {$role}");
    }

    public function sync()
    {
        Artisan::call('permissions:sync');
        $output = Artisan::output();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions synced from routes. ' . trim($output));
    }

    private function getRoles(): array
    {
        return [
            ['slug' => 'super_admin', 'name' => 'Super Admin', 'color' => 'danger'],
            ['slug' => 'admin', 'name' => 'Admin', 'color' => 'primary'],
            ['slug' => 'state_coordinator', 'name' => 'State Coordinator', 'color' => 'success'],
            ['slug' => 'constituency_officer', 'name' => 'Constituency Officer', 'color' => 'info'],
            ['slug' => 'registration_officer', 'name' => 'Registration Officer', 'color' => 'info'],
            ['slug' => 'polling_officer', 'name' => 'Polling Officer', 'color' => 'secondary'],
            ['slug' => 'data_entry', 'name' => 'Data Entry', 'color' => 'warning'],
            ['slug' => 'content_editor', 'name' => 'Content Editor', 'color' => 'purple'],
            ['slug' => 'viewer', 'name' => 'Viewer', 'color' => 'light'],
        ];
    }
}
