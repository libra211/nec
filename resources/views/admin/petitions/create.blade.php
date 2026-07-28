@extends('admin.layouts.app')
@section('title', 'File Election Petition')
@section('content')
<a href="{{ route('admin.petitions.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Petitions</a>
<h2 class="mb-4"><i class="fas fa-gavel text-danger me-2"></i>File Election Petition</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.petitions.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Petition Number *</label>
                    <input type="text" name="petition_number" class="form-control" value="{{ old('petition_number') }}" required placeholder="e.g. PET-2026-001">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filing Date *</label>
                    <input type="date" name="filing_date" class="form-control" value="{{ old('filing_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['filed','hearing','decided','dismissed','withdrawn'] as $s)
                            <option {{ old('status','filed')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Petitioner Name *</label>
                    <input type="text" name="petitioner_name" class="form-control" value="{{ old('petitioner_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Respondent Name *</label>
                    <input type="text" name="respondent_name" class="form-control" value="{{ old('respondent_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Election Name *</label>
                    <input type="text" name="election_name" class="form-control" value="{{ old('election_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Constituency</label>
                    <input type="text" name="constituency" class="form-control" value="{{ old('constituency') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Grounds of Petition *</label>
                    <textarea name="grounds" class="form-control" rows="4" required>{{ old('grounds') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Relief Sought</label>
                    <textarea name="relief_sought" class="form-control" rows="3">{{ old('relief_sought') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Court Name</label>
                    <input type="text" name="court_name" class="form-control" value="{{ old('court_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Case Number</label>
                    <input type="text" name="case_number" class="form-control" value="{{ old('case_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Verdict Date</label>
                    <input type="date" name="verdict_date" class="form-control" value="{{ old('verdict_date') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Verdict</label>
                    <textarea name="verdict" class="form-control" rows="3">{{ old('verdict') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn px-4" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> File Petition</button>
                <a href="{{ route('admin.petitions.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
