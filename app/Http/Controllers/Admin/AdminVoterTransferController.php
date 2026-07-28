<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoterTransfer;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminVoterTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = VoterTransfer::query();

        if ($request->filled('status')) {
            $status = InputSanitizer::clean($request->input('status'));
            $query->where('status', $status);
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('voter_identifier', 'LIKE', "%{$search}%")
                    ->orWhere('national_id', 'LIKE', "%{$search}%");
            });
        }

        $transfers = $query->latest('created_at')->paginate(20)->appends($request->query());

        return view('admin.transfers.index', compact('transfers'));
    }

    public function show(VoterTransfer $transfer)
    {
        return view('admin.transfers.show', compact('transfer'));
    }

    public function approve(VoterTransfer $transfer)
    {
        $transfer->update([
            'status' => 'approved',
            'reviewed_by' => session('admin_user_name'),
            'processed_date' => now(),
            'admin_notes' => 'Approved by ' . session('admin_user_name'),
        ]);

        return back()->with('success', 'Transfer approved successfully.');
    }

    public function reject(VoterTransfer $transfer)
    {
        $transfer->update([
            'status' => 'rejected',
            'reviewed_by' => session('admin_user_name'),
            'processed_date' => now(),
            'admin_notes' => 'Rejected by ' . session('admin_user_name'),
        ]);

        return back()->with('success', 'Transfer rejected.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $transfers = VoterTransfer::whereIn('id', $request->input('ids'))
            ->where('status', 'pending')
            ->get();

        foreach ($transfers as $transfer) {
            $transfer->update([
                'status' => $request->input('action') === 'approve' ? 'approved' : 'rejected',
                'reviewed_by' => session('admin_user_name'),
                'processed_date' => now(),
            ]);
        }

        return back()->with('success', count($transfers) . ' transfer(s) processed.');
    }
}
