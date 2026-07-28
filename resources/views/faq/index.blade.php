@extends('layouts.app', ['title' => 'Frequently Asked Questions', 'active_page' => 'faq'])

@php
$faqRows = [];
try {
    $faqRows = \App\Models\Faq::where('status', 'published')->orderBy('sort_order')->orderBy('id')->get();
} catch (\Exception $e) {}

$categoryMap = [
    'voter-registration' => ['id' => 'faq-voter', 'label' => 'Voter Registration'],
    'voting-process' => ['id' => 'faq-voting', 'label' => 'Voting Process'],
    'candidates-parties' => ['id' => 'faq-candidates', 'label' => 'Candidates & Parties'],
    'observer-accreditation' => ['id' => 'faq-observers', 'label' => 'Observer Accreditation'],
    'general' => ['id' => 'faq-general', 'label' => 'General'],
];

$faqCategories = [];
foreach ($faqRows as $r) {
    $cat = $r->category ?: 'general';
    if (!isset($categoryMap[$cat])) $cat = 'general';
    $faqCategories[$cat][] = $r;
}
$firstCat = array_key_first($faqCategories) ?: 'general';
@endphp

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Frequently Asked Questions</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">FAQ</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-question-circle text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        @if($faqRows->isEmpty())
        <div class="text-center text-muted py-5"><i class="fas fa-question-circle mb-3" style="font-size:3rem;opacity:0.3;display:block;"></i>No FAQs published yet.</div>
        @else
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="list-group shadow-sm" id="faqTabs">
                    @php $first = true; @endphp
                    @foreach($faqCategories as $cat => $items)
                    <button class="list-group-item list-group-item-action fw-semibold {{ $first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ $categoryMap[$cat]['id'] }}" style="border-color: var(--nec-green);">{{ $categoryMap[$cat]['label'] }}</button>
                    @php $first = false; @endphp
                    @endforeach
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content">
                    @php $first = true; @endphp
                    @foreach($faqCategories as $cat => $items)
                    @php $tabId = $categoryMap[$cat]['id']; @endphp
                    <div class="tab-pane fade {{ $first ? 'show active' : '' }}" id="{{ $tabId }}">
                        <h3 class="fw-bold mb-4" style="color: var(--nec-green);">{{ $categoryMap[$cat]['label'] }}</h3>
                        <div class="accordion" id="accordion{{ $tabId }}">
                            @foreach($items as $i => $faq)
                            <div class="accordion-item border-0 mb-2 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $tabId . $i }}">
                                        <strong>{{ $faq->question }}</strong>
                                    </button>
                                </h2>
                                <div id="collapse{{ $tabId . $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#accordion{{ $tabId }}">
                                    <div class="accordion-body text-muted">{!! $faq->answer !!}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @php $first = false; @endphp
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
