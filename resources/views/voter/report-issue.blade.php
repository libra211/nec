@extends('layouts.app', ['title' => 'Report an Issue - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Report an Issue</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">Report an Issue</li>
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
                    <div class="card-header bg-nec-red text-white">
                        <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Report an Issue</h4>
                    </div>
                    <div class="card-body p-4">
                        <p>Use this form to report any issues, irregularities, or concerns related to voter registration, polling stations, or the electoral process.</p>

                        @if(session('success'))
                        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('voter.report-issue.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" name="reporter_name" class="form-control" value="{{ old('reporter_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="reporter_phone" class="form-control" value="{{ old('reporter_phone') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="reporter_email" class="form-control" value="{{ old('reporter_email') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Issue Category *</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <option value="registration_fraud" {{ old('category') === 'registration_fraud' ? 'selected' : '' }}>Registration Fraud</option>
                                        <option value="impersonation" {{ old('category') === 'impersonation' ? 'selected' : '' }}>Impersonation</option>
                                        <option value="duplicate_registration" {{ old('category') === 'duplicate_registration' ? 'selected' : '' }}>Duplicate Registration</option>
                                        <option value="polling_station_issue" {{ old('category') === 'polling_station_issue' ? 'selected' : '' }}>Polling Station Issue</option>
                                        <option value="voter_intimidation" {{ old('category') === 'voter_intimidation' ? 'selected' : '' }}>Voter Intimidation</option>
                                        <option value="vote_buying" {{ old('category') === 'vote_buying' ? 'selected' : '' }}>Vote Buying</option>
                                        <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <select name="state" class="form-select">
                                        <option value="">Select State</option>
                                        @foreach($states as $s)
                                        <option value="{{ $s->name }}" {{ old('state') === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">County</label>
                                    <input type="text" name="county" class="form-control" value="{{ old('county') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description of Issue *</label>
                                    <textarea name="description" class="form-control" rows="5" required placeholder="Please provide as much detail as possible...">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Upload Evidence (optional)</label>
                                    <input type="file" name="evidence[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.mp4">
                                    <small class="text-muted">You can upload multiple files (images, documents, videos)</small>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="anonymous" name="anonymous">
                                    <label class="form-check-label" for="anonymous">Submit anonymously (your identity will be protected)</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-nec-red mt-3"><i class="fas fa-flag me-2"></i>Submit Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
