<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\DashboardItems;
use App\Models\DashboardVisibility;
use Illuminate\Http\Request;

class DashboardVisibilityController extends Controller
{
    public function index()
    {
        $catalog = DashboardItems::catalog();

        $enabledByRole = [];
        foreach ($catalog as $role => $items) {
            $enabledByRole[$role] = DashboardItems::enabledKeys($role);
        }

        return view('admin.dashboard-visibility.index', compact('catalog', 'enabledByRole'));
    }

    public function update(Request $request)
    {
        $catalog = DashboardItems::catalog();

        $payload = $request->validate([
            'roles' => 'nullable|array',
        ]);

        $roles = $payload['roles'] ?? [];

        foreach ($catalog as $role => $items) {
            foreach (array_keys($items) as $key) {
                $checked = isset($roles[$role][$key]) ? (int) ($roles[$role][$key] === '1' || $roles[$role][$key] === 1 || $roles[$role][$key] === 'on') : 0;

                DashboardVisibility::updateOrCreate(
                    ['role' => $role, 'key' => $key],
                    ['visible' => $checked]
                );
            }
        }

        return redirect()->route('admin.dashboard-visibility.index')
            ->with('success', 'Dashboard visibility settings saved.');
    }
}