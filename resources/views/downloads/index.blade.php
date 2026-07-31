@extends('layouts.app', ['title' => 'Downloads', 'active_page' => 'downloads'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Downloads</h1>
                <p class="text-white-50 mb-2">Access important election documents, forms, and resources.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Downloads</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-download text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        @php
        $downloads = \App\Models\Download::select('id', 'title', 'description', 'file_path', 'file_size', 'file_type', 'category', 'downloads_count')
            ->where('status', 'published')
            ->orderBy('category')
            ->orderBy('title')
            ->get()
            ->toArray();
        $categories = [];
        foreach ($downloads as $d) {
            $categories[$d['category']][] = $d;
        }
        $fileIconClass = ['pdf' => 'pdf', 'doc' => 'doc', 'xls' => 'xls', 'zip' => 'zip'];
        $fileIconColors = ['pdf' => '#e74c3c', 'doc' => '#2980b9', 'xls' => '#27ae60', 'zip' => '#f39c12'];
        @endphp
        <div class="row g-4">
            @foreach($categories as $category => $items)
            <div class="col-lg-6" data-aos="fade-up">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom-0 pb-0 pt-3 px-4">
                        <h5 class="fw-bold mb-0" style="color: var(--nec-green);"><i class="fas fa-folder-open me-2"></i>{{ $category }}</h5>
                    </div>
                    <div class="card-body px-4">
                        <div class="list-group list-group-flush">
                            @foreach($items as $file)
                            <div class="list-group-item px-0 border-bottom d-flex align-items-center gap-3">
                                <div class="file-icon file-icon-{{ $fileIconClass[$file['file_type']] ?? 'pdf' }} flex-shrink-0" style="width: 40px; height: 48px; background: {{ $fileIconColors[$file['file_type']] ?? '#e74c3c' }}; border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff;">
                                    <i class="fas fa-file-{{ $fileIconClass[$file['file_type']] ?? 'pdf' }}" style="font-size: 1.1rem;"></i>
                                    <small style="font-size: 0.5rem; font-weight: 700; text-transform: uppercase; line-height: 1;">{{ strtoupper($file['file_type']) }}</small>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small fw-bold">{{ $file['title'] }}</h6>
                                    <small class="text-muted">{{ $file['file_size'] }} &middot; {{ $file['description'] }}</small>
                                    <div class="small text-muted mt-1" style="font-size:0.7rem;"><i class="fas fa-download me-1" style="color:var(--nec-green);"></i>{{ number_format($file['downloads_count'] ?? 0) }} downloads</div>
                                </div>
                                <a href="{{ route('downloads.serve', ['type' => 'file', 'id' => $file['id']]) }}" class="btn btn-sm btn-outline-success flex-shrink-0" style="border-color: var(--nec-green); color: var(--nec-green);" target="_blank"><i class="fas fa-download"></i></a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
