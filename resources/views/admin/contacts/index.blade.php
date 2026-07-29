@extends('admin.layouts.app', ['title' => 'Contact Messages'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Contact Messages</h2>
    <span class="badge bg-primary">{{ $contacts->total() }} total</span>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="contactsTable">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($contacts as $msg)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ e($msg->name) }}</td>
                    <td>{{ e($msg->email) }}</td>
                    <td>{{ e($msg->phone ?? 'N/A') }}</td>
                    <td>{{ e($msg->subject) }}</td>
                    <td>{{ \Carbon\Carbon::parse($msg->created_at)->format('d M Y H:i') }}</td>
                    <td>
                        @if($msg->status === 'new')
                            <span class="badge bg-warning">New</span>
                        @elseif($msg->status === 'read')
                            <span class="badge bg-info">Read</span>
                        @elseif($msg->status === 'replied')
                            <span class="badge bg-success">Replied</span>
                        @elseif($msg->status === 'closed')
                            <span class="badge bg-secondary">Closed</span>
                        @else
                            <span class="badge bg-light text-dark">{{ ucfirst($msg->status) }}</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.contacts.show', $msg->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $contacts->links() }}
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
    $(document).ready(function () {
        $('#contactsTable').DataTable({ responsive: true });
    });
</script>
@endsection
