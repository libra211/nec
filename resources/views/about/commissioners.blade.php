@extends('layouts.app', ['title' => 'Commissioners', 'active_page' => 'about', 'meta_description' => 'Meet the Commissioners of the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Commissioners</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Commissioners</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-users text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color: var(--nec-black);">NEC Commissioners</h2>
            <p class="text-muted">The Commission is composed of a Chairperson, Deputy Chairperson, and seven other Commissioners appointed by the President with the approval of the Transitional National Legislative Assembly.</p>
        </div>
        <div class="row g-4">
            @isset($commissioners)
                @foreach($commissioners as $c)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="row g-0 h-100">
                            <div class="col-4 d-flex align-items-center justify-content-center bg-white p-3">
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--nec-green), #1a3c8f); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="font-size: 2.2rem; color: rgba(255,255,255,0.8);"></i>
                                </div>
                            </div>
                            <div class="col-8 p-3">
                                <span class="badge bg-success mb-1" style="background: var(--nec-green) !important; font-size: 0.7rem;">{{ $c->position }}</span>
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: var(--nec-black);">{{ $c->name }}</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">{{ $c->bio ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                @php
                $commissioners = [
                    (object)['id' => 1, 'name' => 'Hon. Prof. Abednego A. A. Akok', 'position' => 'Chairperson', 'bio' => 'Professor of Law and distinguished jurist with over 30 years of experience in legal practice, academia, and public service.', 'position_order' => 1],
                    (object)['id' => 2, 'name' => 'Hon. Sarah N. Wani', 'position' => 'Deputy Chairperson', 'bio' => 'Senior legal practitioner specializing in constitutional law, human rights, and gender equality.', 'position_order' => 2],
                    (object)['id' => 3, 'name' => 'Hon. James L. Maker', 'position' => 'Commissioner', 'bio' => 'Former Under-Secretary with extensive experience in public administration and electoral logistics.', 'position_order' => 3],
                    (object)['id' => 4, 'name' => 'Hon. Dr. Mary A. Nyandeng', 'position' => 'Commissioner', 'bio' => 'PhD in Political Science with expertise in civic education, voter engagement, and democratic governance.', 'position_order' => 4],
                    (object)['id' => 5, 'name' => 'Hon. John M. Deng', 'position' => 'Commissioner', 'bio' => 'Career diplomat who has represented South Sudan in multiple international forums and election observation missions.', 'position_order' => 5],
                    (object)['id' => 6, 'name' => 'Hon. Rebecca N. Kiden', 'position' => 'Commissioner', 'bio' => 'Human rights lawyer and advocate for inclusive electoral processes and women political participation.', 'position_order' => 6],
                    (object)['id' => 7, 'name' => 'Hon. David L. Wol', 'position' => 'Commissioner', 'bio' => 'Media and communication expert with experience in public information campaigns and stakeholder engagement.', 'position_order' => 7],
                    (object)['id' => 8, 'name' => 'Hon. Grace A. Nyok', 'position' => 'Commissioner', 'bio' => 'Civil society leader with extensive grassroots experience in peacebuilding and community mobilization.', 'position_order' => 8],
                    (object)['id' => 9, 'name' => 'Hon. Peter M. Achier', 'position' => 'Commissioner', 'bio' => 'Finance and administration specialist with a background in public financial management and auditing.', 'position_order' => 9],
                ];
                @endphp
                @foreach($commissioners as $c)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="row g-0 h-100">
                            <div class="col-4 d-flex align-items-center justify-content-center bg-white p-3">
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--nec-green), #1a3c8f); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="font-size: 2.2rem; color: rgba(255,255,255,0.8);"></i>
                                </div>
                            </div>
                            <div class="col-8 p-3">
                                <span class="badge bg-success mb-1" style="background: var(--nec-green) !important; font-size: 0.7rem;">{{ $c->position }}</span>
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: var(--nec-black);">{{ $c->name }}</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">{{ $c->bio ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endisset
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3" style="color: var(--nec-black);">Appointment & Tenure</h3>
                <p class="text-muted">Commissioners are appointed by the President of the Republic of South Sudan, subject to approval by the Transitional National Legislative Assembly. The Chairperson and Deputy Chairperson serve full-time, while other Commissioners may serve on a part-time basis as determined by the Commission.</p>
                <p class="text-muted">The term of office for Commissioners is six years, renewable once. A Commissioner may be removed from office only for inability to perform functions, misconduct, or bankruptcy, following a process set out in the National Elections Act.</p>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-dark text-white" style="background: var(--nec-black) !important;">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-gavel fs-1 mb-3" style="color: var(--nec-gold);"></i>
                        <h5 class="fw-bold">Independent & Impartial</h5>
                        <p class="small text-white-50 mb-0">The Commission operates independently and is not subject to direction or control by any person or authority in the discharge of its functions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
