<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Observer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminObserverController extends Controller
{
    private function roleScopedToState(): ?string
    {
        $role = (string) session('admin_role');
        $state = trim((string) session('admin_state'));

        if (in_array($role, ['state_coordinator', 'registration_officer', 'data_entry'])) {
            return $state !== '' ? $state : null;
        }

        if ($role === 'constituency_officer') {
            return $state !== '' ? $state : null;
        }

        return null;
    }

    private function applyScope($query)
    {
        if ($state = $this->roleScopedToState()) {
            return $query->where('assigned_state', $state);
        }

        return $query;
    }

    private function scopedBase()
    {
        return $this->applyScope(Observer::query());
    }

    private function findScopedObserver($id)
    {
        return $this->scopedBase()->where('id', $id)->firstOrFail();
    }

    private function states()
    {
        return DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();
    }

    public function index(Request $request)
    {
        $query = $this->scopedBase();

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

        $base = $this->scopedBase();
        $stats = [
            'total' => (clone $base)->count(),
            'accredited' => (clone $base)->where('status', 'accredited')->count(),
            'verified' => (clone $base)->where('status', 'verified')->count(),
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'rejected' => (clone $base)->where('status', 'rejected')->count(),
        ];

        $scopedState = $this->roleScopedToState();
        $states = $this->states();

        return view('admin.observers.index', compact('observers', 'stats', 'scopedState', 'states'));
    }

    public function show($id)
    {
        $observer = $this->findScopedObserver($id);
        $states = $this->states();

        return view('admin.observers.show', compact('observer', 'states'));
    }

    public function updateStatus(Request $request, $id)
    {
        $observer = $this->findScopedObserver($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,verified,accredited,rejected,trash',
        ]);

        $observer->update(['status' => $validated['status']]);

        $this->logActivity('observer_status_changed', "Changed observer {$observer->email} status to {$validated['status']}", $observer);

        return back()->with('success', 'Observer status updated.');
    }

    public function updateState(Request $request, $id)
    {
        $observer = $this->findScopedObserver($id);

        $validated = $request->validate([
            'assigned_state' => 'required|string|max:100',
        ]);

        $state = $validated['assigned_state'];
        $stateExists = DB::table('nec_states')->where('name', $state)->exists();
        if (!$stateExists) {
            return back()->with('error', "The state \"{$state}\" is not a recognized NEC state.");
        }

        $observer->update(['assigned_state' => $state]);

        $this->logActivity('observer_state_changed', "Assigned observer {$observer->email} to {$state}", $observer);

        return back()->with('success', "Observer assigned to {$state}.");
    }
}
