@extends('admin.layouts.app', ['title' => 'Manage Observers'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Observer Management</h2>
    <div>
        <button class="btn btn-outline-success me-1"><i class="fas fa-file-export me-1"></i> Export</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $stats['total'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Applications</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $stats['accredited'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Accredited</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Pending Review</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $stats['rejected'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Rejected</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="observersTable">
            <thead>
                <tr><th>#</th><th>Organization</th><th>Type</th><th>Contact</th><th>Observers</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @isset($observers)
                    @foreach($observers as $obs)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ e($obs->org_name) }}</td>
                        <td><span class="badge bg-info">{{ e($obs->org_type) }}</span></td>
                        <td>{{ e($obs->contact_person) }}</td>
                        <td>{{ $obs->observers_count ?? 0 }}</td>
                        <td>
                            @if($obs->status === 'accredited')
                                <span class="badge bg-success">Accredited</span>
                            @elseif($obs->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($obs->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                            @if(isset($obs->status) && $obs->status === 'pending')
                            <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>$(document).ready(function () { $('#observersTable').DataTable({ responsive: true }); });</script>
@endsection
