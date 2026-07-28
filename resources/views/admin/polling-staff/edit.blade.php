@extends('admin.layouts.app')
@section('title', 'Edit Polling Staff')
@section('content')
<a href="{{ route('admin.polling-staff.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Staff</a>
<h2 class="mb-4"><i class="fas fa-user-edit text-primary me-2"></i>Edit: {{ $staff->full_name }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.polling-staff.update', $staff) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $staff->full_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Role *</label>
                    <select name="role" class="form-select" required>
                        @foreach(['Presiding Officer','Deputy Presiding Officer','Poll Clerk','Queue Controller','Counter','Other'] as $r)
                            <option {{ old('role', $staff->role)===$r?'selected':'' }} value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status *</label>
                    <select name="status" class="form-select" required>
                        <option {{ old('status', $staff->status)==='active'?'selected':'' }} value="active">Active</option>
                        <option {{ old('status', $staff->status)==='inactive'?'selected':'' }} value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">State</label>
                    <select name="state" class="form-select">
                        <option value="">Select State</option>
                        @foreach($states as $st)<option {{ old('state', $staff->state)===$st->name?'selected':'' }} value="{{ $st->name }}">{{ $st->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Constituency</label>
                    <input type="text" name="constituency" class="form-control" value="{{ old('constituency', $staff->constituency) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Polling Station ID</label>
                    <input type="number" name="polling_station_id" class="form-control" value="{{ old('polling_station_id', $staff->polling_station_id) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Assignment Date</label>
                    <input type="date" name="assignment_date" class="form-control" value="{{ old('assignment_date', optional($staff->assignment_date)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-4">
                        <input type="hidden" name="trained" value="0">
                        <input type="checkbox" name="trained" value="1" class="form-check-input" {{ old('trained', $staff->trained) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold">Trained</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $staff->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn px-4" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> Update Staff</button>
                <a href="{{ route('admin.polling-staff.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
