@extends('layouts.app', ['title' => $page->title, 'active_page' => 'pages'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">{{ $page->title }}</h1>
                @if($page->meta_description)
                    <p class="text-white-50 mb-0">{{ $page->meta_description }}</p>
                @endif
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-file-lines text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($page->content)
                    <div class="cms-content">{!! $page->content !!}</div>
                @else
                    <div class="alert alert-info" role="alert">
                        This page is being updated. Please check back soon.
                    </div>
                @endif
                <div class="mt-5 text-center">
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-envelope me-2"></i> Need more information? Contact us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection