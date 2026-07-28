@extends('admin.layouts.app', ['title' => 'Settings'])

@section('content')
<div class="mb-4">
    <h2>Site Settings</h2>
</div>

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">General Settings</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'National Elections Commission' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? 'Free, Fair, and Transparent Elections' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'info@necss.org.ss' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '+211 920 000 000' }}">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Social Media</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Twitter URL</label>
                            <input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Election Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Next Election Date</label>
                            <input type="date" name="election_date" class="form-control" value="{{ $settings['election_date'] ?? '2026-12-22' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Election Year</label>
                            <input type="text" name="election_year" class="form-control" value="{{ $settings['election_year'] ?? '2026' }}">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-nec-green btn-lg"><i class="fas fa-save me-1"></i> Save Settings</button>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Quick Stats</h5></div>
            <div class="card-body">
                <p><strong>Total Voters:</strong> {{ number_format($stats['total_voters'] ?? 0) }}</p>
                <p><strong>Total Parties:</strong> {{ $stats['parties'] ?? 0 }}</p>
                <p><strong>Total Candidates:</strong> {{ $stats['candidates'] ?? 0 }}</p>
                <p><strong>Total Observers:</strong> {{ $stats['observers'] ?? 0 }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">System Info</h5></div>
            <div class="card-body">
                <p class="mb-1"><strong>PHP Version:</strong> {{ phpversion() }}</p>
                <p class="mb-1"><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                <p class="mb-0"><strong>Server:</strong> {{ php_uname('s') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
