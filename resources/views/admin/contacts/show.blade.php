@extends('admin.layouts.app', ['title' => 'Contact: ' . e($contact->subject ?? 'Message')])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Contact Message</h2>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $contact->subject ?? 'No Subject' }}</h5>
                @if($contact->status === 'new')
                    <span class="badge bg-warning">New</span>
                @elseif($contact->status === 'read')
                    <span class="badge bg-info">Read</span>
                @elseif($contact->status === 'replied')
                    <span class="badge bg-success">Replied</span>
                @elseif($contact->status === 'closed')
                    <span class="badge bg-secondary">Closed</span>
                @else
                    <span class="badge bg-light text-dark">{{ ucfirst($contact->status) }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <p><strong>Name:</strong> {{ $contact->name }}</p>
                    <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                    @if($contact->phone)
                        <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                    @endif
                    @if($contact->topic)
                        <p><strong>Topic:</strong> {{ $contact->topic }}</p>
                    @endif
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($contact->created_at)->format('d M Y H:i') }}</p>
                </div>
                <div class="message-body">
                    {!! nl2br(e($contact->message)) !!}
                </div>
            </div>
        </div>

        @if($contact->admin_reply)
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-reply me-1"></i> Admin Reply</h6>
            </div>
            <div class="card-body">
                <div class="mb-2"><strong>Replied by:</strong> {{ $contact->replied_by }} on {{ $contact->replied_at ? \Carbon\Carbon::parse($contact->replied_at)->format('d M Y H:i') : 'N/A' }}</div>
                <div>{!! nl2br(e($contact->admin_reply)) !!}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Actions</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST" class="mb-3">
                    @csrf @method('PATCH')
                    <label class="form-label fw-bold">Update Status</label>
                    <select name="status" class="form-select mb-2">
                        <option value="new" {{ $contact->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        <option value="closed" {{ $contact->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button class="btn btn-sm btn-primary w-100"><i class="fas fa-save me-1"></i> Update Status</button>
                </form>
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject ?? '') }}" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="fas fa-reply me-1"></i> Reply via Email
                </a>
                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100"><i class="fas fa-trash me-1"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
