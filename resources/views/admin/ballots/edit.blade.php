@extends('admin.layouts.app')
@section('title', 'Edit Ballot Record')
@section('content')
<a href="{{ route('admin.ballots.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Ballots</a>
<h2 class="mb-4"><i class="fas fa-edit text-info me-2"></i>Edit: {{ $ballot->election_name }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ballots.update', $ballot) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Election Name *</label>
                    <input type="text" name="election_name" class="form-control" value="{{ old('election_name', $ballot->election_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Election Type *</label>
                    <select name="election_type" class="form-select" required>
                        @foreach(['presidential','parliamentary','state_governor','county_commissioner','payam_administrator','other'] as $t)
                            <option {{ old('election_type', $ballot->election_type)===$t?'selected':'' }} value="{{ $t }}">{{ ucwords(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $ballot->state) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Constituency</label>
                    <input type="text" name="constituency" class="form-control" value="{{ old('constituency', $ballot->constituency) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['planned','printing','delivered','deployed','archived'] as $s)
                            <option {{ old('status', $ballot->status)===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Total Printed</label>
                    <input type="number" name="total_printed" class="form-control" value="{{ old('total_printed', $ballot->total_printed) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Serial Start</label>
                    <input type="text" name="serial_start" class="form-control" value="{{ old('serial_start', $ballot->serial_start) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Serial End</label>
                    <input type="text" name="serial_end" class="form-control" value="{{ old('serial_end', $ballot->serial_end) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Printer</label>
                    <input type="text" name="printer" class="form-control" value="{{ old('printer', $ballot->printer) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control" value="{{ old('delivery_date', optional($ballot->delivery_date)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Received Date</label>
                    <input type="date" name="received_date" class="form-control" value="{{ old('received_date', optional($ballot->received_date)->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ballot Design Notes</label>
                    <textarea name="ballot_design" class="form-control" rows="3">{{ old('ballot_design', $ballot->ballot_design) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $ballot->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn px-4" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> Update Ballot</button>
                <a href="{{ route('admin.ballots.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
