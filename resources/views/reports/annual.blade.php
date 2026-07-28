@extends('layouts.app', ['title' => 'Annual Reports', 'active_page' => 'reports'])

@php
$reports = [
    ['year' => 2025, 'title' => 'Annual Report 2025', 'file' => 'nec-annual-report-2025.pdf', 'size' => '2.4 MB'],
    ['year' => 2024, 'title' => 'Annual Report 2024', 'file' => 'nec-annual-report-2024.pdf', 'size' => '2.1 MB'],
    ['year' => 2023, 'title' => 'Annual Report 2023', 'file' => 'nec-annual-report-2023.pdf', 'size' => '1.8 MB'],
    ['year' => 2022, 'title' => 'Annual Report 2022', 'file' => 'nec-annual-report-2022.pdf', 'size' => '1.6 MB'],
    ['year' => 2021, 'title' => 'Annual Report 2021', 'file' => 'nec-annual-report-2021.pdf', 'size' => '1.5 MB'],
    ['year' => 2020, 'title' => 'Annual Report 2020', 'file' => 'nec-annual-report-2020.pdf', 'size' => '1.3 MB'],
];

try {
    $db_reports = \App\Models\Download::where('category', 'annual-reports')->orderByDesc('year')->get(['year', 'title', 'file', 'file_size as size'])->toArray();
    if (!empty($db_reports)) {
        $reports = $db_reports;
    }
} catch (\Exception $e) {}
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Annual Reports</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('media/publications') }}" class="text-white-50">Publications</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Annual Reports</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-file-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <p class="text-muted mb-4">Download annual reports from the National Elections Commission covering electoral activities, financial statements, and performance reviews.</p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">Year</th>
                                <th class="fw-bold">Title</th>
                                <th class="fw-bold">Size</th>
                                <th class="fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td><span class="badge bg-dark">{{ $report['year'] ?? $report->year ?? '' }}</span></td>
                                <td class="fw-semibold">{{ $report['title'] ?? $report->title ?? '' }}</td>
                                <td><small class="text-muted">{{ $report['size'] ?? $report->size ?? 'N/A' }}</small></td>
                                <td><a href="{{ url('downloads/' . ($report['file'] ?? $report->file ?? '')) }}" class="btn btn-sm btn-success" style="background: var(--nec-green); border-color: var(--nec-green);"><i class="fas fa-download me-1"></i> Download</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
