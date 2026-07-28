<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportController extends Controller
{
    public function annual()
    {
        $reports = Report::where('status', 'published')
            ->orderByDesc('report_date')
            ->get();

        return view('reports.annual', compact('reports'));
    }
}
