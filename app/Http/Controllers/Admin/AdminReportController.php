<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::withTrashed();

        if ($request->filled('status')) {
            $query->where('status', InputSanitizer::clean($request->input('status')));
        }
        if ($request->filled('category')) {
            $query->where('category', InputSanitizer::clean($request->input('category')));
        }
        if ($request->filled('search')) {
            $search = InputSanitizer::clean($request->input('search'));
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $reports = $query->latest('report_date')->paginate(20)->appends($request->query());

        return view('admin.reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|string|max:100',
            'report_date' => 'required|date',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
        ]);

        $data = InputSanitizer::clean($validated);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('reports', 'public');
        }
        $data['status'] = 'published';
        $data['published_at'] = now();

        $report = Report::create($data);

        $this->logActivity('report_created', "Created report: {$report->title}", $report);

        return back()->with('success', 'Report uploaded successfully.');
    }

    public function destroy(Report $report)
    {
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }
        $report->delete();

        $this->logActivity('report_deleted', "Deleted report: {$report->title}", $report);

        return back()->with('success', 'Report deleted.');
    }

    public function restore($id)
    {
        $report = Report::withTrashed()->findOrFail($id);
        $report->restore();

        $this->logActivity('report_restored', "Restored report: {$report->title}", $report);

        return back()->with('success', 'Report restored.');
    }
}
