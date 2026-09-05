<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Voter;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function annual()
    {
        $reports = Report::where('status', 'published')
            ->orderByDesc('report_date')
            ->get();

        return view('reports.annual', compact('reports'));
    }

    private function groupColumn(Request $request): string
    {
        $constituency = trim((string) $request->input('constituency'));
        $county = trim((string) $request->input('county'));
        $state = trim((string) $request->input('state'));

        $column = $constituency !== '' ? 'polling_station'
            : ($county !== '' ? 'constituency'
            : ($state !== '' ? 'county' : 'state'));

        if (!in_array($column, ['state', 'county', 'constituency', 'polling_station'], true)) {
            $column = 'state';
        }

        return $column;
    }

    private function statisticsQuery(Request $request)
    {
        $query = Voter::query()->whereNull('deleted_at');

        if ($state = trim((string) $request->input('state'))) {
            $query->where('state', $state);
        }
        if ($county = trim((string) $request->input('county'))) {
            $query->where('county', $county);
        }
        if ($constituency = trim((string) $request->input('constituency'))) {
            $query->where('constituency', $constituency);
        }

        return $query;
    }

    private function statisticsRows(Request $request)
    {
        $column = $this->groupColumn($request);

        return $this->statisticsQuery($request)
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->selectRaw("
                `{$column}` AS name,
                COUNT(*) AS total_registered,
                SUM(CASE WHEN status <> 'deceased' AND eligible_to_vote = 1 THEN 1 ELSE 0 END) AS eligible,
                SUM(CASE WHEN status <> 'deceased' AND pre_registered = 1 THEN 1 ELSE 0 END) AS pre_registered,
                SUM(CASE WHEN status <> 'deceased' AND eligible_to_vote = 0 AND pre_registered = 0 THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'deceased' THEN 1 ELSE 0 END) AS deceased,
                SUM(CASE WHEN status <> 'deceased' AND gender = 'M' THEN 1 ELSE 0 END) AS male,
                SUM(CASE WHEN status <> 'deceased' AND gender = 'F' THEN 1 ELSE 0 END) AS female,
                SUM(CASE WHEN status <> 'deceased' AND registration_type = 'agent' THEN 1 ELSE 0 END) AS agent_registered
            ")
            ->groupBy($column)
            ->orderByDesc('total_registered')
            ->orderBy('name')
            ->get();
    }

    public function locationStats(Request $request)
    {
        $state = trim((string) $request->input('state'));
        $county = trim((string) $request->input('county'));

        $states = Voter::whereNull('deleted_at')->whereNotNull('state')->where('state', '!=', '')
            ->distinct()->orderBy('state')->pluck('state');
        $counties = Voter::whereNull('deleted_at')->whereNotNull('county')->where('county', '!=', '')
            ->when($state, fn ($q) => $q->where('state', $state))
            ->distinct()->orderBy('county')->pluck('county');
        $constituencies = Voter::whereNull('deleted_at')->whereNotNull('constituency')->where('constituency', '!=', '')
            ->when($state, fn ($q) => $q->where('state', $state))
            ->when($county, fn ($q) => $q->where('county', $county))
            ->distinct()->orderBy('constituency')->pluck('constituency');

        return view('reports.voter-stats', [
            'rows' => $this->statisticsRows($request),
            'groupColumn' => $this->groupColumn($request),
            'states' => $states,
            'counties' => $counties,
            'constituencies' => $constituencies,
        ]);
    }

    public function locationStatsCsv(Request $request)
    {
        $rows = $this->statisticsRows($request);
        $column = $this->groupColumn($request);
        $filename = 'voter_statistics_' . date('Y-m-d_His') . '.csv';

        $csv = function () use ($rows, $column) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                ucwords(str_replace('_', ' ', $column)),
                'Registered', 'Eligible', 'Pre-Registered', 'Pending',
                'Deceased', 'Male', 'Female', 'Agent-Assisted',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->name,
                    (int) $row->total_registered,
                    (int) $row->eligible,
                    (int) $row->pre_registered,
                    (int) $row->pending,
                    (int) $row->deceased,
                    (int) $row->male,
                    (int) $row->female,
                    (int) $row->agent_registered,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($csv, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
