<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NecHelper;
use App\Http\Controllers\Controller;
use App\Models\ObserverApplication;
use App\Models\ObserverBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AdminObserverApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = ObserverApplication::with('batch');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('other_names', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('accreditation_number', 'LIKE', "%{$search}%")
                  ->orWhere('national_id', 'LIKE', "%{$search}%")
                  ->orWhere('passport_number', 'LIKE', "%{$search}%");
            });
        }

        if ($type = $request->input('form_type')) {
            $query->where('form_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($batch = $request->input('batch')) {
            if ($batch === 'none') {
                $query->whereNull('batch_id');
            } else {
                $query->where('batch_id', $batch);
            }
        }

        // Sentinels: a revoked accreditation is treated as such even if status is still approved.
        if ($request->input('revoked') === '1') {
            $query->whereNotNull('revoked_at');
        } elseif ($request->input('revoked') === '0') {
            $query->whereNull('revoked_at');
        }

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowed = ['id', 'first_name', 'last_name', 'form_type', 'status', 'created_at', 'batch_id'];
        if (! in_array($sortColumn, $allowed, true)) {
            $sortColumn = 'created_at';
        }
        if (! in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        $applications = $query->orderBy($sortColumn, $sortDirection)->paginate(20)->withQueryString();

        $stats = [
            'total' => ObserverApplication::count(),
            'domestic' => ObserverApplication::where('form_type', 'domestic')->count(),
            'international' => ObserverApplication::where('form_type', 'international')->count(),
            'pending' => ObserverApplication::where('status', 'pending')->count(),
            'approved' => ObserverApplication::where('status', 'approved')->whereNull('revoked_at')->count(),
            'accredited' => ObserverApplication::whereNotNull('accreditation_number')->whereNull('revoked_at')->count(),
            'rejected' => ObserverApplication::where('status', 'rejected')->count(),
            'revoked' => ObserverApplication::whereNotNull('revoked_at')->count(),
        ];

        $batches = ObserverBatch::orderByDesc('created_at')->get(['id', 'batch_number', 'label', 'status']);

        return view('admin.observers.applications.index', compact('applications', 'stats', 'batches'));
    }

    public function show($id)
    {
        $application = ObserverApplication::with(['batch', 'approver'])->findOrFail($id);

        $verifyUrl = route('observers.accreditation.verify', $application->verification_token ?: 'invalid');

        return view('admin.observers.applications.show', compact('application', 'verifyUrl'));
    }

    public function updateStatus(Request $request, $id)
    {
        $application = ObserverApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,approved,rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $application->admin_notes,
            'approved_at' => $validated['status'] === 'approved' ? now() : $application->approved_at,
            'approved_by' => $validated['status'] === 'approved' ? (session('admin_user_id') ?: null) : $application->approved_by,
        ]);

        $this->logActivity('observer_application_status_changed',
            "Observer application #OA-{$application->id} ({$application->email}) status set to {$validated['status']}.",
            $application);

        return back()->with('success', "Application status updated to {$validated['status']}.");
    }

    public function revoke(Request $request, $id)
    {
        $application = ObserverApplication::findOrFail($id);

        $validated = $request->validate([
            'revoked_reason' => 'required|string|max:2000',
        ]);

        $application->update([
            'revoked_at' => now(),
            'revoked_by' => session('admin_user_id') ?: null,
            'revoked_reason' => $validated['revoked_reason'],
        ]);

        $this->logActivity('observer_accreditation_revoked',
            "Accreditation {$application->accreditation_number} for #OA-{$application->id} revoked.",
            $application);

        return back()->with('success', 'Accreditation revoked. The verification link no longer confirms validity.');
    }

    public function generate(Request $request, $id)
    {
        $application = ObserverApplication::with('batch')->findOrFail($id);

        if (! $application->is_accredited) {
            $application->update([
                'status' => 'approved',
                'approved_at' => $application->approved_at ?? now(),
                'approved_by' => $application->approved_by ?? (session('admin_user_id') ?: null),
            ]);
        }

        $application->update([
            'accreditation_number' => $application->accreditation_number ?? NecHelper::generate_accreditation_number(),
            'verification_token' => $application->verification_token ?? Str::random(40),
            'status' => 'approved',
        ]);

        $this->logActivity('observer_accreditation_generated',
            "Accreditation certificate {$application->accreditation_number} generated for #OA-{$application->id}.",
            $application);

        return redirect()->route('admin.observers.applications.badge', $application->id)
            ->with('success', "Accreditation {$application->accreditation_number} generated.");
    }

    public function batchIndex()
    {
        $batches = ObserverBatch::withCount('applications')->with('generator')->orderByDesc('created_at')->get();

        return view('admin.observers.batches.index', compact('batches'));
    }

    public function batchStore(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'application_ids' => 'nullable|array',
            'application_ids.*' => 'integer|exists:nec_observer_applications,id',
        ]);

        $count = $batches = ObserverBatch::count();

        $batch = ObserverBatch::create([
            'batch_number' => 'OBB-' . date('Y') . '-' . str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT),
            'label' => $validated['label'],
            'notes' => $validated['notes'] ?? null,
            'generated_by' => session('admin_user_id') ?: null,
            'generated_at' => now(),
        ]);

        if (! empty($validated['application_ids'])) {
            ObserverApplication::whereIn('id', $validated['application_ids'])
                ->whereNull('batch_id')
                ->update(['batch_id' => $batch->id]);
        }

        $this->logActivity('observer_batch_created', "Observer batch {$batch->batch_number} created.", $batch);

        return redirect()->route('admin.observers.batches.show', $batch->id)
            ->with('success', "Batch {$batch->batch_number} created.");
    }

    public function batchShow($id)
    {
        $batch = ObserverBatch::with(['applications' => fn ($q) => $q->orderBy('last_name')->orderBy('first_name')])->findOrFail($id);

        $pending = ObserverApplication::whereNull('batch_id')->where('status', 'approved')->whereNull('revoked_at')->get();

        return view('admin.observers.batches.show', compact('batch', 'pending'));
    }

    public function batchAssign(Request $request, $id)
    {
        $batch = ObserverBatch::findOrFail($id);

        $validated = $request->validate([
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'integer|exists:nec_observer_applications,id',
        ]);

        $count = ObserverApplication::whereIn('id', $validated['application_ids'])
            ->whereNull('batch_id')
            ->where('status', 'approved')
            ->whereNull('revoked_at')
            ->update(['batch_id' => $batch->id]);

        $this->logActivity('observer_batch_assigned', "Assigned {$count} application(s) to batch {$batch->batch_number}.", $batch);

        return back()->with('success', "Assigned {$count} approved application(s) to batch {$batch->batch_number}.");
    }

    public function batchGenerate(Request $request, $id)
    {
        $batch = ObserverBatch::with('applications')->findOrFail($id);

        $count = 0;
        foreach ($batch->applications as $application) {
            if ($application->revoked_at) {
                continue;
            }
            $application->update([
                'accreditation_number' => $application->accreditation_number ?? NecHelper::generate_accreditation_number(),
                'verification_token' => $application->verification_token ?? Str::random(40),
                'status' => 'approved',
                'approved_at' => $application->approved_at ?? now(),
                'approved_by' => $application->approved_by ?? (session('admin_user_id') ?: null),
            ]);
            $count++;
        }

        if ($count > 0) {
            $batch->update(['status' => 'generated', 'generated_at' => now()]);
        }

        $this->logActivity('observer_batch_generated', "Generated {$count} accreditation(s) in batch {$batch->batch_number}.", $batch);

        return back()->with('success', "Generated accreditation for {$count} observer(s) in batch {$batch->batch_number}.");
    }

    public function badge($id)
    {
        $application = ObserverApplication::findOrFail($id);

        if (! $application->accreditation_number) {
            return redirect()->route('admin.observers.applications.show', $application->id)
                ->with('error', 'Generate the accreditation first.');
        }

        return view('admin.observers.badge', compact('application'));
    }

    public function badgePrint(Request $request)
    {
        $ids = $request->input('ids', []);
        if (! is_array($ids)) {
            $ids = [$ids];
        }

        $applications = ObserverApplication::whereNotNull('accreditation_number')
            ->whereNull('revoked_at')
            ->whereIn('id', $ids)
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'Select at least one accredited observer to print.');
        }

        return view('admin.observers.badge-print', compact('applications'));
    }
}