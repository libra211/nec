<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PollingStation;
use App\Models\Voter;
use App\Models\VoterTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminVoterController extends Controller
{
    private function writeState(): ?string
    {
        $role = (string) session('admin_role');

        if (in_array($role, ['state_coordinator', 'constituency_officer', 'registration_officer', 'data_entry'])) {
            $state = trim((string) session('admin_state'));

            return $state !== '' ? $state : null;
        }

        return null;
    }

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
            return $query->where('state', $state);
        }

        if ($constituency = $this->roleScopedToConstituency()) {
            return $query->where('constituency', $constituency);
        }

        return $query;
    }

    private function roleScopedBase()
    {
        return $this->applyRoleScope(Voter::query()->whereNull('deleted_at'));
    }

    private function scopedStates()
    {
        if ($state = $this->writeState()) {
            return collect([(object) ['name' => $state]]);
        }

        return DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();
    }

    private function stationOptions()
    {
        return PollingStation::where('status', 'active')
            ->orderBy('state')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'state', 'constituency']);
    }

    private function findScopedVoter($id)
    {
        return $this->roleScopedBase()->where('id', $id)->firstOrFail();
    }

    private function scopedPendingTransfers()
    {
        $base = VoterTransfer::query()->where('status', 'pending');

        if ($state = $this->roleScopedToState()) {
            return (clone $base)->where(function ($q) use ($state) {
                $q->where('from_state', $state)->orWhere('to_state', $state);
            })->count();
        }

        if ($constituency = $this->roleScopedToConstituency()) {
            return (clone $base)->where(function ($q) use ($constituency) {
                $q->where('from_constituency', $constituency)->orWhere('to_constituency', $constituency);
            })->count();
        }

        return (clone $base)->count();
    }

    public function index(Request $request)
    {
        $query = $this->roleScopedBase();

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

        if (!$this->roleScopedToState()) {
            if ($state = $request->input('state')) {
                $query->where('state', $state);
            }
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
        $states = $this->scopedStates();
        $scopeBase = $this->roleScopedBase();
        $counties = (clone $scopeBase)->whereNotNull('county')->distinct()->pluck('county')->filter()->sort()->values();
        $constituencies = (clone $scopeBase)->whereNotNull('constituency')->distinct()->pluck('constituency')->filter()->sort()->values();

        $stats = [
            'total_voters' => (clone $scopeBase)->count(),
            'active_voters' => (clone $scopeBase)->where('status', 'active')->whereNull('deceased_date')->count(),
            'suspended_voters' => (clone $scopeBase)->where('status', 'suspended')->count(),
            'deceased_voters' => (clone $scopeBase)->where(fn($q) => $q->where('status', 'deceased')->orWhereNotNull('deceased_date'))->count(),
            'male_voters' => (clone $scopeBase)->where('gender', 'M')->count(),
            'female_voters' => (clone $scopeBase)->where('gender', 'F')->count(),
            'pending_transfers' => $this->scopedPendingTransfers(),
        ];

        $scopedState = $this->roleScopedToState();
        $scopedConstituency = $this->roleScopedToConstituency();

        return view('admin.voters.index', compact('voters', 'states', 'counties', 'constituencies', 'stats', 'scopedState', 'scopedConstituency'));
    }

    public function create()
    {
        $states = $this->scopedStates();
        $pollingStations = $this->stationOptions();

        return view('admin.voters.create', compact('states', 'pollingStations'));
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
            'polling_station_id' => 'nullable|exists:nec_polling_stations,id',
            'polling_station' => 'nullable|string|max:100',
            'registration_center' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['state'] = $this->writeState() ?? $validated['state'];

        if (!empty($validated['polling_station_id'])) {
            $station = PollingStation::find($validated['polling_station_id']);
            $validated['polling_station'] = $station?->name;
        } elseif (!empty($validated['polling_station'])) {
            $station = PollingStation::where('name', $validated['polling_station'])->first();
            $validated['polling_station_id'] = $station?->id;
        }

        $validated['registered_at'] = now();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        $voter = Voter::create($validated);

        $this->logActivity('voter_created', "Registered voter: {$voter->full_name} ({$voter->voter_id})", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter registered successfully.');
    }

    public function show($id)
    {
        $voter = $this->findScopedVoter($id);
        $voter->load([
            'voterTransfers' => fn ($q) => $q->orderByDesc('created_at'),
            'account',
            'country',
            'diasporaMission',
            'pollingStation',
        ]);

        $this->logActivity('voter_viewed', "Viewed voter profile: {$voter->full_name}", $voter);

        return view('admin.voters.show', compact('voter'));
    }

    public function edit($id)
    {
        $voter = $this->findScopedVoter($id);
        $states = $this->scopedStates();
        $pollingStations = $this->stationOptions();

        return view('admin.voters.edit', compact('voter', 'states', 'pollingStations'));
    }

    public function update(Request $request, $id)
    {
        $voter = $this->findScopedVoter($id);

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
            'polling_station_id' => 'nullable|exists:nec_polling_stations,id',
            'polling_station' => 'nullable|string|max:100',
            'registration_center' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['state'] = $this->writeState() ?? $validated['state'];

        if (!empty($validated['polling_station_id'])) {
            $station = PollingStation::find($validated['polling_station_id']);
            $validated['polling_station'] = $station?->name;
        } elseif (!empty($validated['polling_station'])) {
            $station = PollingStation::where('name', $validated['polling_station'])->first();
            $validated['polling_station_id'] = $station?->id;
        }

        $validated['updated_at'] = now();

        $voter->update($validated);

        $this->logActivity('voter_updated', "Updated voter: {$voter->full_name}", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $voter = $this->findScopedVoter($id);

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,deceased',
        ]);

        $oldStatus = $voter->status;
        $voter->update(['status' => $validated['status'], 'updated_at' => now()]);

        $this->logActivity('voter_status_changed', "Changed voter {$voter->full_name} status from {$oldStatus} to {$validated['status']}", $voter);

        return back()->with('success', 'Voter status updated.');
    }

    public function markDeceased(Request $request, $id)
    {
        $voter = $this->findScopedVoter($id);

        $validated = $request->validate([
            'deceased_date' => 'required|date|before_or_equal:today',
            'death_certificate_ref' => 'nullable|string|max:100',
        ]);

        $voter->markAsDeceased([
            'deceased_date' => $validated['deceased_date'],
            'deceased_by' => session('admin_user_name', session('admin_email', '')),
            'death_certificate_ref' => $validated['death_certificate_ref'] ?? null,
        ]);

        $this->logActivity('voter_deceased', "Recorded death of voter {$voter->full_name} ({$voter->voter_id}) dated {$validated['deceased_date']}", $voter);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Death recorded. Voter removed from the electoral roll.']);
        }

        return back()->with('success', 'Death recorded. Voter removed from the electoral roll.');
    }

    public function revive($id)
    {
        $voter = $this->findScopedVoter($id);

        $voter->revive();

        $this->logActivity('voter_revived', "Cleared death record for voter {$voter->full_name} ({$voter->voter_id})", $voter);

        return back()->with('success', 'Voter returned to active and death record cleared.');
    }

    public function destroy($id)
    {
        $voter = $this->findScopedVoter($id);
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
        $query = $this->applyRoleScope(Voter::onlyTrashed());

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
        $voter = $this->applyRoleScope(Voter::onlyTrashed())->where('id', $id)->firstOrFail();
        $voter->update(['deleted_at' => null, 'updated_at' => now()]);

        $this->logActivity('voter_restored', "Restored voter: {$voter->full_name}", $voter);

        return redirect()->route('admin.voters.index')->with('success', 'Voter restored successfully.');
    }

    public function export(Request $request)
    {
        $query = $this->roleScopedBase();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if (!$this->roleScopedToState()) {
            if ($state = $request->input('state')) {
                $query->where('state', $state);
            }
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
                'Polling Station', 'Registration Center', 'Status', 'Registration Type',
                'Registered By Code', 'Registered By Location', 'Registered At',
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
                    $voter->registration_type === 'agent'
                        ? ($voter->registered_by_name === 'Bulk Import' ? 'Bulk Import' : 'Agent Assisted')
                        : 'Self Registration',
                    $voter->registered_by_code ?? ($voter->registration_type === 'agent' ? 'NEC Registration Team' : 'NEC Online Portal'),
                    $voter->registered_by_location,
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

    public function importTemplate()
    {
        $filename = 'voter_import_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'voter_id', 'full_name', 'gender', 'dob', 'national_id', 'phone', 'email',
                'payam', 'boma', 'polling_station', 'registration_center',
            ]);
            fputcsv($handle, [
                'N/A (auto)', 'Deng Akech', 'M', '1990-01-15', 'NID-00000001', '+211912000001', 'voter@example.com',
                'Juba Town', 'Kator', 'Juba Primary School', 'Juba Center',
            ]);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
            'import_state' => 'required|string|max:100',
            'import_county' => 'nullable|string|max:100',
            'import_constituency' => 'nullable|string|max:255',
        ]);

        $state = $this->writeState() ?? $validated['import_state'];
        $county = $validated['import_county'] ?? null;
        $constituency = $validated['import_constituency'] ?? null;

        $stateExists = DB::table('nec_states')->where('name', $state)->exists();
        if (!$stateExists) {
            return back()->with('error', "The state \"{$state}\" is not a recognized NEC state.");
        }

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'Could not read the uploaded CSV file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'The CSV file is empty.');
        }

        $header = array_map(fn ($h) => strtolower(trim($h)), $header);
        $col = [
            'voter_id' => array_search('voter_id', $header),
            'full_name' => array_search('full_name', $header) ?: array_search('fullname', $header),
            'gender' => array_search('gender', $header),
            'dob' => array_search('dob', $header) ?: array_search('date_of_birth', $header),
            'national_id' => array_search('national_id', $header),
            'phone' => array_search('phone', $header),
            'email' => array_search('email', $header),
            'payam' => array_search('payam', $header),
            'boma' => array_search('boma', $header),
            'polling_station' => array_search('polling_station', $header),
            'registration_center' => array_search('registration_center', $header),
        ];

        if (($col['full_name'] === false) || ($col['gender'] === false) || ($col['dob'] === false) || ($col['phone'] === false)) {
            fclose($handle);
            return back()->with('error', 'The CSV must include at least these columns: full_name, gender, dob, phone.');
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (empty(array_filter($data))) {
                continue;
            }
            $rows[] = $data;
        }
        fclose($handle);

        if (empty($rows)) {
            return back()->with('error', 'The CSV file contains no data rows.');
        }

        $get = function ($data, $key) use ($col) {
            $idx = $col[$key];
            return ($idx !== false && $idx !== null && isset($data[$idx])) ? trim((string) $data[$idx]) : null;
        };

        $imported = 0;
        $duplicates = 0;
        $invalid = 0;
        $errors = [];
        $now = now();

        $stationIdByName = PollingStation::whereNotNull('code')->where('status', 'active')
            ->pluck('id', 'name')
            ->toArray();

        DB::transaction(function () use ($rows, $get, $state, $county, $constituency, $now, $stationIdByName, &$imported, &$duplicates, &$invalid, &$errors) {
            foreach ($rows as $line => $data) {
                $rowNo = $line + 2;

                $fullName = $get($data, 'full_name');
                $gender = strtoupper((string) $get($data, 'gender'));
                $dob = $get($data, 'dob');
                $phone = $get($data, 'phone');

                $nationalId = $get($data, 'national_id');
                $email = $get($data, 'email');
                $payam = $get($data, 'payam');
                $boma = $get($data, 'boma');
                $pollingStation = $get($data, 'polling_station') ?? 'Central Polling Station';
                $registrationCenter = $get($data, 'registration_center');

                if (!$fullName || !$gender || !$dob || !$phone) {
                    $invalid++;
                    $errors[] = "Row {$rowNo}: missing required field (full_name, gender, dob or phone).";
                    continue;
                }

                if (!in_array($gender, ['M', 'F'], true)) {
                    $invalid++;
                    $errors[] = "Row {$rowNo}: gender must be M or F (got \"{$gender}\").";
                    continue;
                }

                if (!strtotime($dob) || strtotime($dob) > time() || substr($dob, 0, 4) < 1900) {
                    $invalid++;
                    $errors[] = "Row {$rowNo}: invalid date of birth \"{$dob}\".";
                    continue;
                }

                if (preg_match('/[a-zA-Z]/', $phone) || strlen(preg_replace('/\D/', '', $phone)) < 7) {
                    $invalid++;
                    $errors[] = "Row {$rowNo}: invalid phone number \"{$phone}\".";
                    continue;
                }

                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $invalid++;
                    $errors[] = "Row {$rowNo}: invalid email \"{$email}\".";
                    continue;
                }

                // Scope enforcement: every imported row is stamped into the chosen area.
                $voterData = [
                    'full_name' => $fullName,
                    'gender' => $gender,
                    'dob' => date('Y-m-d', strtotime($dob)),
                    'phone' => $phone,
                    'email' => $email ?: null,
                    'national_id' => $nationalId ?: null,
                    'payam' => $payam ?: null,
                    'boma' => $boma ?: null,
                    'polling_station' => $pollingStation,
                    'polling_station_id' => $stationIdByName[$pollingStation] ?? null,
                    'registration_center' => $registrationCenter ?: null,
                    'state' => $state,
                    'county' => $county ?: null,
                    'constituency' => $constituency ?: null,
                    'country_name' => 'South Sudan',
                    'nationality' => 'South Sudanese',
                    'registration_type' => 'agent',
                    'status' => 'active',
                    'registered_at' => $now,
                ];

                $elig = \App\Helpers\NecHelper::eligibility_date(\Carbon\Carbon::parse($voterData['dob']));
                $voterData['eligibility_date'] = $elig;
                $voterData['eligible_to_vote'] = \Carbon\Carbon::parse($voterData['dob'])->age >= \App\Helpers\NecHelper::voting_age();
                $voterData['pre_registered'] = \Carbon\Carbon::parse($voterData['dob'])->age < \App\Helpers\NecHelper::voting_age();
                $voterData['voter_id'] = \App\Helpers\NecHelper::generate_voter_id($gender, date('Y'));

                // Wire the registration source back to the interoperable fields.
                $voterData['registered_by_name'] = 'Bulk Import';
                $voterData['registered_by_title'] = 'NEC Batch Import';
                $voterData['registered_by_location'] = $state . ($county ? ', ' . $county : '');

                try {
                    Voter::create($voterData);
                } catch (\Illuminate\Database\QueryException $e) {
                    $code = $e->errorInfo[1] ?? null;
                    if ($code === 1062) {
                        $duplicates++;
                        $errors[] = "Row {$rowNo}: skipped — voter with matching national ID, phone or voter ID already exists.";
                    } else {
                        $invalid++;
                        $errors[] = "Row {$rowNo}: database error — " . $e->getMessage();
                    }
                    continue;
                }

                $imported++;
            }
        });

        $summary = compact('imported', 'duplicates', 'invalid');

        if ($imported === 0) {
            return back()->with('error', 'No voters were imported. ' . $summary['duplicates'] . ' duplicate(s) and ' . $summary['invalid'] . ' invalid row(s).')
                ->with('import_errors', $errors);
        }

        $this->logActivity('voters_imported', "Imported {$imported} voters to {$state}" . ($county ? " / {$county}" : '') . ($constituency ? " / {$constituency}" : ''));

        return back()->with('success', "Imported {$imported} voter(s) successfully under {$state}" . ($county ? " / {$county}" : '') . ($constituency ? " / {$constituency}" : '') . ". {$summary['duplicates']} duplicate(s) and {$summary['invalid']} invalid row(s) were skipped.")
            ->with('import_summary', $summary)
            ->with('import_errors', $errors);
    }
}
