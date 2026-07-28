<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PollingStation;
use App\Models\Constituency;
use Illuminate\Http\Request;

class AdminPollingStationController extends Controller
{
    public function index(Request $request)
    {
        $query = PollingStation::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('constituency', 'LIKE', "%{$search}%")
                  ->orWhere('state', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pollingStations = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.polling-stations.index', compact('pollingStations'));
    }

    public function create()
    {
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.polling-stations.create', compact('constituencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'payam' => 'nullable|string|max:100',
            'registered_voters' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,trash',
        ]);

        PollingStation::create($validated);

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station created.');
    }

    public function edit($id)
    {
        $pollingStation = PollingStation::findOrFail($id);
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();

        return view('admin.polling-stations.edit', compact('pollingStation', 'constituencies'));
    }

    public function update(Request $request, $id)
    {
        $pollingStation = PollingStation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'constituency' => 'nullable|string|max:100',
            'payam' => 'nullable|string|max:100',
            'registered_voters' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,trash',
        ]);

        $pollingStation->update($validated);

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station updated.');
    }

    public function destroy($id)
    {
        $pollingStation = PollingStation::findOrFail($id);
        $pollingStation->delete();

        return redirect()->route('admin.polling-stations.index')->with('success', 'Polling station deleted.');
    }
}
