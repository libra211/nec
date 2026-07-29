@extends('admin.layouts.app')
@section('title', 'Trashed Voters')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-trash-alt text-danger me-2"></i>Trashed Voters</h1>
    <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Voter ID</th>
                        <th>Full Name</th>
                        <th>State</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voters as $voter)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ e($voter->voter_id) }}</code></td>
                        <td>{{ e($voter->full_name) }}</td>
                        <td>{{ e($voter->state ?? 'N/A') }}</td>
                        <td>{{ $voter->deleted_at ? $voter->deleted_at->format('d M Y H:i') : 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.voters.restore', $voter->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore"><i class="fas fa-undo"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No trashed voters found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $voters->links() }}
    </div>
</div>
@endsection
