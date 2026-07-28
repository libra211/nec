@extends('admin.layouts.app')
@section('title', 'Staff Activity - ' . $staff->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-history text-primary me-2"></i>Activity: {{ $staff->name }}</h1>
    <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date/Time</th>
                        <th>Action</th>
                        <th>Entity Type</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($activity->action) }}</span></td>
                            <td>{{ $activity->entity_type ?? '-' }}</td>
                            <td>{{ Str::limit($activity->details ?? '-', 60) }}</td>
                            <td><code>{{ $activity->ip_address ?? '-' }}</code></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No activity recorded for this staff member.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $activities->links() }}
    </div>
</div>
@endsection
