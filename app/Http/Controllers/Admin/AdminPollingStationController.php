<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PollingStation;
use App\Models\Region;
use App\Models\State;
use App\Models\County;
use App\Models\Constituency;
use App\Models\Voter;
use Illuminate\Http\Request;

class AdminPollingStationController extends Controller
{
    private function roleScopedToState(): ?string
    {
        $role = (string) session('admin_role');
        $state = trim((string) session('admin_state'));

        if (in_array($role, ['state_coordinator', 'registration_officer', 'data_entry'])) {
            return $state !== '' ? $state : null;
        }

        if ($role === 'constituency_officer' && trim((string) session('admin_constituency')) === '') {
            return $state !== '' ? $state : null;
        }

        return null;
    }

    private function roleScopedToConstituency(): ?string
    {
        if ((string) session('admin_role') === 'constituency_officer') {
            $constituency = trim((string) session('admin_constituency'));

            return $constituency !== '' ? $constituency : null;
        }

        return null;
    }

    private function applyRoleScope($query)
    {
        if ($state = $this->roleScopedToState()) {
            $query->where('state', $state);
        }

        if ($constituency = $this->roleScopedToConstituency()) {
            $query->where('constituency', $constituency);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->applyRoleScope(PollingStation::query());

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%")
                  ->orWhere('county', 'LIKE', "%{$search}%")
                  ->orWhere('state', 'LIKE', "%{$search}%");
            });
        }

        if (!$this->roleScopedToState()) {
            if ($state = $request->input('state')) {
                $query->where('state', $state);
            }
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pollingStations = $query->orderByDesc('created_at')->paginate(15);

        $scopeBase = $this->applyRoleScope(PollingStation::query());
        $scopedStationIds = (clone $scopeBase)->pluck('id');

        $stats = [
            'total' => (clone $scopeBase)->count(),
            'active' => (clone $scopeBase)->where('status', 'active')->count(),
            'inactive' => (clone $scopeBase)->where('status', 'inactive')->count(),
            'total_voters' => (clone $scopeBase)->sum('registered_voters'),
            'staff' => \App\Models\PollingStaff::whereIn('polling_station_id', $scopedStationIds)->count(),
            'states_covered' => (clone $scopeBase)->whereNotNull('state')->distinct('state')->count('state'),
        ];

        $scopedConstituency = $this->roleScopedToConstituency();

        if ($scopedConstituency) {
            $stateStats = $this->applyRoleScope(PollingStation::where('status', 'active'))
                ->selectRaw('constituency AS group_label, COUNT(*) as stations, SUM(registered_voters) as voters')
                ->groupBy('constituency')
                ->orderByDesc('voters')
                ->get();
            $stateStatsByConstituency = true;
        } else {
            $stateStats = $this->applyRoleScope(PollingStation::where('status', 'active'))
                ->selectRaw('state AS group_label, COUNT(*) as stations, SUM(registered_voters) as voters')
                ->whereNotNull('state')
                ->groupBy('state')
                ->orderByDesc('voters')
                ->limit(6)
                ->get();
            $stateStatsByConstituency = false;
        }

        $regions = Region::where('status', 'active')->orderBy('sort_order')->pluck('name', 'id');
        $states = State::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $scopedState = $this->roleScopedToState();

        return view('admin.polling-stations.index', compact('pollingStations', 'stats', 'stateStats', 'stateStatsByConstituency', 'regions', 'states', 'scopedState', 'scopedConstituency'));
    }

    public function show($id)
    {
        $pollingStation = $this->applyRoleScope(PollingStation::query())
            ->withCount('pollingStaff')
            ->with(['pollingStaff' => fn ($q) => $q->orderByRaw("FIELD(role, 'presiding_officer', 'deputy_presiding', 'poll_clerk', 'security', 'observer', 'trainer')")])
            ->where('id', $id)
            ->firstOrFail();

        $linkedVoters = Voter::where('polling_station', $pollingStation->name)->count();

        return view('admin.polling-stations.show', compact('pollingStation', 'linkedVoters'));
    }

    public function export(Request $request)
    {
        $query = $this->applyRoleScope(PollingStation::query());

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%")
                  ->orWhere('county', 'LIKE', "%{$search}%")
                  ->orWhere('state', 'LIKE', "%{$search}%");
            });
        }

        if (!$this->roleScopedToState()) {
            if ($state = $request->input('state')) {
                $query->where('state', $state);
            }
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $stations = $query->orderBy('state')->orderBy('name')->get();

        $csv = "Name,Code,State,County,Constituency,Payam,Registered Voters,Latitude,Longitude,Status\n";
        foreach ($stations as $item) {
            $csv .= implode(',', [
                '"' . str_replace('"', '""', $item->name) . '"',
                '"' . str_replace('"', '""', $item->code ?? '') . '"',
                '"' . str_replace('"', '""', $item->state ?? '') . '"',
                '"' . str_replace('"', '""', $item->county ?? '') . '"',
                '"' . str_replace('"', '""', $item->constituency ?? '') . '"',
                '"' . str_replace('"', '""', $item->payam ?? '') . '"',
                $item->registered_voters ?? 0,
                $item->latitude ?? '',
                $item->longitude ?? '',
                '"' . ($item->status ?? '') . '"',
            ]) . "\n";
        }

        $this->logActivity('polling_stations_exported', 'Exported polling stations to CSV (' . $stations->count() . ' rows)', null);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="polling_stations_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function create()
    {
        $regions = Region::where('status', 'active')->orderBy('sort_order')->get();
        $states = State::where('status', 'active')->orderBy('name')->get();
        $counties = County::where('status', 'active')->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.polling-stations.create', compact('regions', 'states', 'counties', 'constituencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:nec_polling_stations,code',
            'region' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'payam' => 'nullable|string|max:100',
            'registered_voters' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,trash',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueCode();
        }

        $pollingStation = PollingStation::create($validated);

        $this->logActivity('polling_station_created', "Created polling station: {$pollingStation->name} [{$pollingStation->code}]", $pollingStation);

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station created successfully.');
    }

    public function edit($id)
    {
        $pollingStation = PollingStation::findOrFail($id);
        $regions = Region::where('status', 'active')->orderBy('sort_order')->get();
        $states = State::where('status', 'active')->orderBy('name')->get();
        $counties = County::where('status', 'active')->orderBy('name')->get();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.polling-stations.edit', compact('pollingStation', 'regions', 'states', 'counties', 'constituencies'));
    }

    public function update(Request $request, $id)
    {
        $pollingStation = PollingStation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:nec_polling_stations,code,' . $id,
            'region' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'payam' => 'nullable|string|max:100',
            'registered_voters' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,trash',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueCode();
        }

        $pollingStation->update($validated);

        $this->logActivity('polling_station_updated', "Updated polling station: {$pollingStation->name} [{$pollingStation->code}]", $pollingStation);

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station updated successfully.');
    }

    public function destroy($id)
    {
        $pollingStation = PollingStation::findOrFail($id);
        $pollingStation->delete();

        $this->logActivity('polling_station_deleted', "Deleted polling station: {$pollingStation->name}", $pollingStation);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Polling station deleted.']);
        }

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station deleted.');
    }

    public function getCounties(Request $request)
    {
        $stateName = $request->input('state');
        $counties = County::where('status', 'active')
            ->when($stateName, fn($q) => $q->whereHas('state', fn($sq) => $sq->where('name', $stateName)))
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($counties);
    }

    public function getConstituencies(Request $request)
    {
        $countyName = $request->input('county');
        $constituencies = Constituency::where('status', 'active')
            ->when($countyName, fn($q) => $q->whereHas('county', fn($cq) => $cq->where('name', $countyName)))
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($constituencies);
    }

    public function generateCode()
    {
        return response()->json(['code' => $this->generateUniqueCode()]);
    }

    private function generateUniqueCode(): string
    {
        $prefix = 'PS';
        do {
            $code = $prefix . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (PollingStation::where('code', $code)->exists());

        return $code;
    }
}
