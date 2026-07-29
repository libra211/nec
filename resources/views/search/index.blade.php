@extends('layouts.app', ['title' => 'Search Results - NEC South Sudan', 'active_page' => 'search'])

@section('hero')
<section class="page-hero">
    <div class="container">
        <h1 data-aos="fade-up">Search Results</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Search</li>
            </ol>
        </nav>
    </div>
</section>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="search-box mb-4" data-aos="fade-up">
                    <form action="{{ route('search.results') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control" placeholder="Search NEC website..." value="{{ request('q') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                @isset($search_query)
                <p class="text-muted" data-aos="fade-up">Showing results for "<strong>{{ e($search_query) }}</strong>"</p>
                @endisset

                @isset($results)
                    @forelse($results as $result)
                    <div class="search-result-item" data-aos="fade-up">
                        <h4><a href="{{ $result['url'] ?? '#' }}">{{ e($result['title'] ?? 'Untitled') }}</a></h4>
                        <p class="text-muted small">{{ $result['url'] ?? '' }}</p>
                        <p>{{ Str::limit($result['description'] ?? '', 200) }}</p>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No Results Found</h4>
                        <p class="text-muted">No results found for "{{ e(request('q', '')) }}". Try different keywords.</p>
                    </div>
                    @endforelse
                @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Search the NEC Website</h4>
                    <p class="text-muted">Enter a search term to find news, candidates, parties, and more.</p>
                </div>
                @endisset
            </div>
        </div>
    </div>
</section>
@endsection
