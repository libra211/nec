<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Models\VoterTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVoterController extends Controller
{
    public function index(Request $request)
    {
        $query = Voter::query()->whereNull('deleted_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('voter_id', 'LIKE', "%{$search}%")
                  ->orWhere('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('national_id', 'LIKE', "%{$search}%")
                  ->orWhere('reg_number', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        if ($county = $request->input('county')) {
            $query->where('county', $county);
        }

        if ($constituency = $request->input('constituency')) {
            $query->where('constituency', $constituency);
        }

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortColumns = ['voter_id', 'full_name', 'gender', 'state', 'county', 'constituency', 'status', 'created_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $voters = $query->orderBy($sortColumn, $sortDirection)->paginate(20);
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->pluck('name');
        $counties = Voter::whereNull('deleted_at')->whereNotNull('county')->distinct()->pluck('county')->filter()->sort()->values();
        $constituencies = Voter::whereNull('deleted_at')->whereNotNull('constituency')->distinct()->pluck('constituency')->filter()->sort()->values();

        $stats = [
            'total_voters' => Voter::whereNull('deleted_at')->count(),
            'active_voters' => Voter::whereNull('deleted_at')->where('status', 'active')->count(),
            'suspended_voters' => Voter::whereNull('deleted_at')->where('status', 'suspended')->count(),
            'male_voters' => Voter::whereNull('deleted_at')->where('gender', 'M')->count(),
            'female_voters' => Voter::whereNull('deleted_at')->where('gender', 'F')->count(),
            'pending_transfers' => \App\Models\VoterTransfer::where('status', 'pending')->count(),
        ];

        return view('admin.voters.index', compact('voters', 'states', 'counties', 'constituencies', 'stats'));
    }

    public function create()
    {
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        return view('admin.voters.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'voter_id' => 'required|string|max:50|unique:nec_voters,voter_id',
            'national_id' => 'required|string|max:50|unique:nec_voters,national_id',
            'reg_number' => 'nullable|string|max:50|unique:nec_voters,reg_number',
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'state' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'constituency' => 'required|string|max:100',
            'payam' => 'nullable|string|max:100',
            'polling_station' => 'nullable|string|max:100',
            'registration_center' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['registered_at'] = now();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        $voter = Voter::create($validated);

        $this->logActivity('voter_created', "Registered voter: {$voter->full_name} ({$voter->voter_id})", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter registered successfully.');
    }

    public function show($id)
    {
        $voter = Voter::where('id', $id)->whereNull('deleted_at')->findOrFail($id);
        $transfers = VoterTransfer::where('voter_id', $voter->id)->orderByDesc('created_at')->get();

        $this->logActivity('voter_viewed', "Viewed voter profile: {$voter->full_name}", $voter);

        return view('admin.voters.show', compact('voter', 'transfers'));
    }

    public function edit($id)
    {
        $voter = Voter::where('id', $id)->whereNull('deleted_at')->findOrFail($id);
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();

        return view('admin.voters.edit', compact('voter', 'states'));
    }

    public function update(Request $request, $id)
    {
        $voter = Voter::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'voter_id' => 'required|string|max:50|unique:nec_voters,voter_id,' . $id,
            'national_id' => 'required|string|max:50|unique:nec_voters,national_id,' . $id,
            'reg_number' => 'nullable|string|max:50|unique:nec_voters,reg_number,' . $id,
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'state' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'constituency' => 'required|string|max:100',
            'payam' => 'nullable|string|max:100',
            'polling_station' => 'nullable|string|max:100',
            'registration_center' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['updated_at'] = now();

        $voter->update($validated);

        $this->logActivity('voter_updated', "Updated voter: {$voter->full_name}", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $voter = Voter::where('id', $id)->whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $oldStatus = $voter->status;
        $voter->update(['status' => $validated['status'], 'updated_at' => now()]);

        $this->logActivity('voter_status_changed', "Changed voter {$voter->full_name} status from {$oldStatus} to {$validated['status']}", $voter);

        return back()->with('success', 'Voter status updated.');
    }

    public function destroy($id)
    {
        $voter = Voter::where('id', $id)->whereNull('deleted_at')->findOrFail($id);
        $now = now();
        $voter->update(['deleted_at' => $now, 'updated_at' => $now]);

        $this->logActivity('voter_deleted', "Soft deleted voter: {$voter->full_name} ({$voter->voter_id})", $voter);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Voter removed successfully.']);
        }

        return redirect()->route('admin.voters.index')->with('success', 'Voter removed successfully.');
    }

    public function trashed(Request $request)
    {
        $query = Voter::onlyTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('voter_id', 'LIKE', "%{$search}%")
                  ->orWhere('full_name', 'LIKE', "%{$search}%");
            });
        }

        $voters = $query->orderByDesc('deleted_at')->paginate(20);

        return view('admin.voters.trashed', compact('voters'));
    }

    public function restore($id)
    {
        $voter = Voter::onlyTrashed()->findOrFail($id);
        $voter->update(['deleted_at' => null, 'updated_at' => now()]);

        $this->logActivity('voter_restored', "Restored voter: {$voter->full_name}", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter restored successfully.');
    }

    public function export(Request $request)
    {
        $query = Voter::whereNull('deleted_at');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }
        if ($county = $request->input('county')) {
            $query->where('county', $county);
        }
        if ($constituency = $request->input('constituency')) {
            $query->where('constituency', $constituency);
        }

        $voters = $query->orderBy('full_name')->get();

        $filename = 'voters_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($voters) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Voter ID', 'National ID', 'Reg Number', 'Full Name', 'DOB', 'Gender',
                'Phone', 'Email', 'State', 'County', 'Constituency', 'Payam',
                'Polling Station', 'Registration Center', 'Status', 'Registered At',
            ]);

            foreach ($voters as $voter) {
                fputcsv($handle, [
                    $voter->voter_id,
                    $voter->national_id,
                    $voter->reg_number,
                    $voter->full_name,
                    $voter->dob,
                    $voter->gender,
                    $voter->phone,
                    $voter->email,
                    $voter->state,
                    $voter->county,
                    $voter->constituency,
                    $voter->payam,
                    $voter->polling_station,
                    $voter->registration_center,
                    $voter->status,
                    $voter->registered_at,
                ]);
            }

            fclose($handle);
        };

        $this->logActivity('voters_exported', "Exported " . $voters->count() . " voters to CSV", $voters->first());

        return response()->stream($callback, 200, $headers);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,suspend,delete',
            'voter_ids' => 'required|array|min:1',
        ]);

        $action = $validated['action'];
        $voterIds = $validated['voter_ids'];
        $count = 0;
        $now = now();
        $firstVoter = null;

        foreach ($voterIds as $voterId) {
            $voter = Voter::where('id', $voterId)->whereNull('deleted_at')->first();
            if (!$voter) {
                continue;
            }

            match ($action) {
                'activate' => $voter->update(['status' => 'active', 'updated_at' => $now]),
                'suspend' => $voter->update(['status' => 'suspended', 'updated_at' => $now]),
                'delete' => $voter->update(['deleted_at' => $now, 'updated_at' => $now]),
            };

            $firstVoter ??= $voter;
            $count++;
        }

        $this->logActivity('voter_bulk_action', "Bulk {$action} on {$count} voters", $firstVoter);

        return back()->with('success', "{$count} voters processed successfully.");
    }
}
