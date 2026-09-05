<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoterTransfer;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminVoterTransferController extends Controller
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
            return $query->where(function ($q) use ($state) {
                $q->where('from_state', $state)->orWhere('to_state', $state);
            });
        }

        if ($constituency = $this->roleScopedToConstituency()) {
            return $query->where(function ($q) use ($constituency) {
                $q->where('from_constituency', $constituency)->orWhere('to_constituency', $constituency);
            });
        }

        return $query;
    }

    private function scopedBase()
    {
        return $this->applyRoleScope(VoterTransfer::query());
    }

    private function authorizeScopeOrAbort(VoterTransfer $transfer): void
    {
        if (!$this->scopedBase()->whereKey($transfer->getKey())->exists()) {
            abort(403, 'This transfer request is outside your assigned area.');
        }
    }

    public function index(Request $request)
    {
        $query = $this->scopedBase();

        if ($request->filled('status')) {
            $status = InputSanitizer::clean($request->input('status'));
            if (in_array($status, ['pending', 'approved', 'rejected', 'cancelled'])) {
                $query->where('status', $status);
            }
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('voter_identifier', 'LIKE', "%{$search}%")
                    ->orWhere('national_id', 'LIKE', "%{$search}%")
                    ->orWhere('from_constituency', 'LIKE', "%{$search}%")
                    ->orWhere('to_constituency', 'LIKE', "%{$search}%");
            });
        }

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];

        $transfers = $query->latest('created_at')->paginate(20)->appends($request->query());

        return view('admin.transfers.index', compact('transfers', 'stats'));
    }

    public function show(VoterTransfer $transfer)
    {
        $this->authorizeScopeOrAbort($transfer);

        return view('admin.transfers.show', compact('transfer'));
    }

    public function approve(VoterTransfer $transfer)
    {
        $this->authorizeScopeOrAbort($transfer);

        $transfer->update([
            'status' => 'approved',
            'reviewed_by' => session('admin_user_name'),
            'reviewed_at' => now(),
            'admin_notes' => 'Approved by ' . session('admin_user_name'),
        ]);

        $this->logActivity('transfer_approved', "Approved transfer for {$transfer->full_name}", $transfer);

        return back()->with('success', 'Transfer approved successfully.');
    }

    public function reject(VoterTransfer $transfer)
    {
        $this->authorizeScopeOrAbort($transfer);

        $transfer->update([
            'status' => 'rejected',
            'reviewed_by' => session('admin_user_name'),
            'reviewed_at' => now(),
            'admin_notes' => 'Rejected by ' . session('admin_user_name'),
        ]);

        $this->logActivity('transfer_rejected', "Rejected transfer for {$transfer->full_name}", $transfer);

        return back()->with('success', 'Transfer rejected.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $transfers = $this->scopedBase()
            ->whereIn('id', $request->input('ids'))
            ->where('status', 'pending')
            ->get();

        foreach ($transfers as $transfer) {
            $transfer->update([
                'status' => $request->input('action') === 'approve' ? 'approved' : 'rejected',
                'reviewed_by' => session('admin_user_name'),
                'reviewed_at' => now(),
            ]);
        }

        $this->logActivity('transfer_bulk_action', "Bulk {$request->input('action')} on " . count($transfers) . " transfers", $transfers->first());

        return back()->with('success', count($transfers) . ' transfer(s) processed.');
    }

    public function export(Request $request)
    {
        $query = $this->scopedBase();

        if ($request->filled('status')) {
            $status = InputSanitizer::clean($request->input('status'));
            if (in_array($status, ['pending', 'approved', 'rejected', 'cancelled'])) {
                $query->where('status', $status);
            }
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('voter_identifier', 'LIKE', "%{$search}%")
                    ->orWhere('national_id', 'LIKE', "%{$search}%")
                    ->orWhere('from_constituency', 'LIKE', "%{$search}%")
                    ->orWhere('to_constituency', 'LIKE', "%{$search}%");
            });
        }

        $transfers = $query->orderBy('created_at', 'desc')->get();

        $csv = "Voter ID,Full Name,National ID,From State,From Constituency,To State,To Constituency,Reason,Status,Submitted\n";
        foreach ($transfers as $t) {
            $csv .= implode(',', [
                '"' . str_replace('"', '""', $t->voter_identifier) . '"',
                '"' . str_replace('"', '""', $t->full_name) . '"',
                '"' . str_replace('"', '""', $t->national_id ?? '') . '"',
                '"' . str_replace('"', '""', $t->from_state ?? '') . '"',
                '"' . str_replace('"', '""', $t->from_constituency ?? '') . '"',
                '"' . str_replace('"', '""', $t->to_state ?? '') . '"',
                '"' . str_replace('"', '""', $t->to_constituency ?? '') . '"',
                '"' . str_replace('"', '""', $t->reason ?? '') . '"',
                '"' . ($t->status ?? '') . '"',
                $t->created_at?->format('Y-m-d') ?? '',
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="voter_transfers_' . date('Y-m-d') . '.csv"',
        ]);
    }
}