<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $unauthorized = static fn () => redirect()->route('login')->with('error', 'Please log in to access the admin panel.');

        if (!session('admin_logged_in', false)) {
            $token = $request->cookie('nec_remember');
            $type = $request->cookie('nec_remember_type');
            if ($token && $type === 'admin') {
                $user = User::where('remember_token', $token)->where('status', 'active')->first();
                if (! $user) {
                    return $unauthorized();
                }
                session([
                    'admin_logged_in' => true,
                    'admin_email' => $user->email,
                    'admin_user_id' => $user->id,
                    'admin_user_name' => $user->name,
                    'admin_role' => $user->role ?? 'admin',
                    'admin_state' => $user->state ?? '',
                    'admin_constituency' => $user->constituency ?? '',
                ]);
            } else {
                return $unauthorized();
            }
        }

        $role = session('admin_role', 'viewer');

        $userPermissions = Permission::forRole($role);

        view()->share('adminRole', $role);
        view()->share('adminPermissions', $userPermissions);
        view()->share('can', fn(string $slug) => $role === 'super_admin' || in_array($slug, $userPermissions));

        if ($role === 'super_admin') {
            return $next($request);
        }

        $routeName = $request->route()->getName();
        if (!$routeName || !str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        $parts = explode('.', $routeName);
        $module = $parts[1] ?? '';
        $action = count($parts) > 2 ? $parts[count($parts) - 1] : 'index';

        // Dashboard visibility is a superadmin-only configuration surface.
        if ($module === 'dashboard-visibility') {
            return redirect()->route('admin.dashboard')
                ->with('error', "You don't have permission to access: {$module} {$action}");
        }

        // The observers module holds only view/review permissions: every
        // management action (approve, batch, generate, revoke, ...) maps to review.
        if ($module === 'observers') {
            $slug = in_array($action, ['index', 'show', 'applications', 'batches', 'badge', 'certificate', 'print', 'export'])
                ? 'observers.view'
                : 'observers.review';
        } else {
            if ($action === 'index') $slug = "{$module}.view";
            elseif ($action === 'create' || $action === 'store') $slug = "{$module}.create";
            elseif (in_array($action, ['edit', 'update'])) $slug = "{$module}.update";
            elseif (in_array($action, ['destroy', 'force-delete'])) $slug = "{$module}.delete";
            elseif ($action === 'restore') $slug = "{$module}.restore";
            elseif ($action === 'export') $slug = "{$module}.export";
            elseif (in_array($action, ['show'])) $slug = "{$module}.view";
            else $slug = "{$module}.{$action}";
        }

        if (!in_array($slug, $userPermissions)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', "You don't have permission to access: {$module} {$action}");
        }

        return $next($request);
    }
}
