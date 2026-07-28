@extends('admin.layouts.app')
@section('title', 'Add Ballot Record')
@section('content')
<a href="{{ route('admin.ballots.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Ballots</a>
<h2 class="mb-4"><i class="fas fa-plus-circle text-info me-2"></i>Add Ballot Record</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ballots.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Election Name *</label>
                    <input type="text" name="election_name" class="form-control" value="{{ old('election_name') }}" required placeholder="e.g. 2026 General Elections">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Election Type *</label>
                    <select name="election_type" class="form-select" required>
                        <option value="">Select Type</option>
                        @foreach(['presidential','parliamentary','state_governor','county_commissioner','payam_administrator','other'] as $t)
                            <option {{ old('election_type')===$t?'selected':'' }} value="{{ $t }}">{{ ucwords(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Constituency</label>
                    <input type="text" name="constituency" class="form-control" value="{{ old('constituency') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['planned','printing','delivered','deployed','archived'] as $s)
                            <option {{ old('status','planned')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Total Printed</label>
                    <input type="number" name="total_printed" class="form-control" value="{{ old('total_printed', 0) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Serial Start</label>
                    <input type="text" name="serial_start" class="form-control" value="{{ old('serial_start') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Serial End</label>
                    <input type="text" name="serial_end" class="form-control" value="{{ old('serial_end') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Printer</label>
                    <input type="text" name="printer" class="form-control" value="{{ old('printer') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control" value="{{ old('delivery_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Received Date</label>
                    <input type="date" name="received_date" class="form-control" value="{{ old('received_date') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ballot Design Notes</label>
                    <textarea name="ballot_design" class="form-control" rows="3">{{ old('ballot_design') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn px-4" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> Save Ballot</button>
                <a href="{{ route('admin.ballots.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
