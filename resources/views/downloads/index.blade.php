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
        $downloads = [];
        try {
            $downloads = \App\Models\Download::select('id', 'title', 'description', 'file_url', 'file_size', 'file_type', 'category')
                ->orderBy('category')
                ->orderBy('title')
                ->get()
                ->toArray();
        } catch (\Exception $e) {}
        if (empty($downloads)) {
            $downloads = [
                ['category' => 'Voter Forms', 'title' => 'Voter Registration Form', 'description' => 'Form for registering as a voter', 'file_url' => '#', 'file_size' => '245 KB', 'file_type' => 'pdf'],
                ['category' => 'Voter Forms', 'title' => 'Voter Transfer Application', 'description' => 'Request to transfer voter registration', 'file_url' => '#', 'file_size' => '180 KB', 'file_type' => 'pdf'],
                ['category' => 'Voter Forms', 'title' => 'Voter Details Correction Form', 'description' => 'Correct errors in voter registration details', 'file_url' => '#', 'file_size' => '210 KB', 'file_type' => 'pdf'],
                ['category' => 'Candidate Forms', 'title' => 'Nomination Form - President', 'description' => 'Nomination form for presidential candidates', 'file_url' => '#', 'file_size' => '420 KB', 'file_type' => 'pdf'],
                ['category' => 'Candidate Forms', 'title' => 'Nomination Form - MP', 'description' => 'Nomination form for parliamentary candidates', 'file_url' => '#', 'file_size' => '380 KB', 'file_type' => 'pdf'],
                ['category' => 'Candidate Forms', 'title' => 'Candidate Code of Conduct', 'description' => 'Code of conduct agreement for all candidates', 'file_url' => '#', 'file_size' => '150 KB', 'file_type' => 'pdf'],
                ['category' => 'Party Forms', 'title' => 'Political Party Registration Form', 'description' => 'Form for registering a new political party', 'file_url' => '#', 'file_size' => '520 KB', 'file_type' => 'pdf'],
                ['category' => 'Party Forms', 'title' => 'Party Candidate List Template', 'description' => 'Template for submitting candidate lists', 'file_url' => '#', 'file_size' => '195 KB', 'file_type' => 'xls'],
                ['category' => 'Party Forms', 'title' => 'Party Financial Disclosure Form', 'description' => 'Annual financial disclosure for political parties', 'file_url' => '#', 'file_size' => '310 KB', 'file_type' => 'doc'],
                ['category' => 'Observer Forms', 'title' => 'Observer Accreditation Application', 'description' => 'Application form for observer accreditation', 'file_url' => '#', 'file_size' => '280 KB', 'file_type' => 'pdf'],
                ['category' => 'Observer Forms', 'title' => 'Observer Code of Conduct', 'description' => 'Code of conduct for accredited observers', 'file_url' => '#', 'file_size' => '120 KB', 'file_type' => 'pdf'],
                ['category' => 'Observer Forms', 'title' => 'Observer Report Template', 'description' => 'Template for submitting observation reports', 'file_url' => '#', 'file_size' => '340 KB', 'file_type' => 'doc'],
                ['category' => 'Legal Documents', 'title' => 'Electoral Act 2024', 'description' => 'The principal electoral legislation', 'file_url' => '#', 'file_size' => '2.1 MB', 'file_type' => 'pdf'],
                ['category' => 'Legal Documents', 'title' => 'NEC Regulations 2025', 'description' => 'Regulations governing electoral operations', 'file_url' => '#', 'file_size' => '1.8 MB', 'file_type' => 'pdf'],
                ['category' => 'Reports', 'title' => 'Annual Report 2024-2025', 'description' => 'Commission annual report', 'file_url' => '#', 'file_size' => '4.2 MB', 'file_type' => 'pdf'],
                ['category' => 'Reports', 'title' => 'Voter Registration Statistics', 'description' => 'Statistical report on voter registration', 'file_url' => '#', 'file_size' => '980 KB', 'file_type' => 'xls'],
            ];
        }
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
                                </div>
                                <a href="{{ $file['file_url'] }}" class="btn btn-sm btn-outline-success flex-shrink-0" style="border-color: var(--nec-green); color: var(--nec-green);"><i class="fas fa-download"></i></a>
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
