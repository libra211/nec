@extends('layouts.app', ['title' => 'Find Polling Station - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Find Polling Station</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}">Voter Services</a></li>
                <li class="breadcrumb-item active">Find Polling Station</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4" data-aos="fade-right">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-nec-green text-white">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Polling Station</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('voter.polling-finder.search') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Voter ID Number</label>
                                <input type="text" name="voter_id" class="form-control" placeholder="Enter Voter ID" value="{{ old('voter_id') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">State</label>
                                <select name="state" class="form-select">
                                    <option value="">Select State</option>
                                    @foreach($states as $s)
                                    <option value="{{ $s->name }}" {{ old('state') === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">County</label>
                                <input type="text" name="county" class="form-control" placeholder="Enter County">
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-map-marker-alt me-2"></i>Find Station</button>
                        </form>
                    </div>
                </div>

                @isset($polling_station)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Your Polling Station</h5>
                        <p><strong>Station:</strong> {{ $polling_station->name }}</p>
                        <p><strong>Address:</strong> {{ $polling_station->address }}</p>
                        <p><strong>Constituency:</strong> {{ $polling_station->constituency->name ?? 'N/A' }}</p>
                        <p><strong>Operating Hours:</strong> 6:00 AM - 6:00 PM</p>
                    </div>
                </div>
                @endisset
            </div>
            <div class="col-lg-8" data-aos="fade-left">
                <div id="polling-map" style="height: 500px; border-radius: 8px;"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('polling-map')) {
            var map = L.map('polling-map').setView([7.0, 30.0], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([7.0, 30.0]).addTo(map).bindPopup('South Sudan');
        }
    });
</script>
@endsection
