@extends('admin.layouts.app', ['title' => 'Contact Messages'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Contact Messages</h2>
    <span class="badge bg-primary">{{ $contacts->total() }} total</span>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="contactsTable">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">NAME</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">EMAIL</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">PHONE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">SUBJECT</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">DATE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">STATUS</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $msg)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#1e293b;">{{ $msg->name }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $msg->email }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $msg->phone ?? 'N/A' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $msg->subject }}</td>
                        <td style="padding:10px 12px;color:#64748b;">{{ \Carbon\Carbon::parse($msg->created_at)->format('d M Y H:i') }}</td>
                        <td style="padding:10px 12px;color:#475569;">
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
                        <td style="padding:10px 16px 10px 12px;text-align:right;">
                            <a href="{{ route('admin.contacts.show', $msg->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($contacts->hasPages() || $contacts->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} messages</span>
        {{ $contacts->links() }}
    </div>
    @endif
</div>
@endsection

@section('extra_scripts')
<script>
    $(document).ready(function () {
        $('#contactsTable').DataTable({ responsive: true });
    });
</script>
@endsection
