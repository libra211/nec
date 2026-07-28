@extends('layouts.app', ['title' => 'Transfer Registration - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Transfer Registration</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">Transfer Registration</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="card shadow-sm">
                    <div class="card-header bg-nec-green text-white">
                        <h4 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transfer Voter Registration</h4>
                    </div>
                    <div class="card-body p-4">
                        <p>If you have moved to a new address, you can transfer your voter registration to your new constituency.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Transfers must be completed at least 30 days before Election Day. You will need to provide proof of your new address.
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('voter.transfer.submit') }}" method="POST">
                            @csrf
                            <h5 class="mb-3">Current Registration Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Voter ID Number *</label>
                                    <input type="text" name="voter_id" class="form-control" value="{{ old('voter_id') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">New Address Details</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">New State *</label>
                                    <select name="new_state" class="form-select" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $s)
                                        <option value="{{ $s->name }}" {{ old('new_state') === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">New County *</label>
                                    <input type="text" name="new_county" class="form-control" value="{{ old('new_county') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">New Payam *</label>
                                    <input type="text" name="new_payam" class="form-control" value="{{ old('new_payam') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">New Physical Address *</label>
                                    <textarea name="new_address" class="form-control" rows="2" required>{{ old('new_address') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Supporting Document (Proof of Address)</label>
                                    <input type="file" name="proof_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="confirmTransfer" required>
                                <label class="form-check-label" for="confirmTransfer">I confirm that I have relocated to the new address and request transfer of my voter registration.</label>
                            </div>

                            <button type="submit" class="btn btn-nec-green mt-3"><i class="fas fa-paper-plane me-2"></i>Submit Transfer Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
