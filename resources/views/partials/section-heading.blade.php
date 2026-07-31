{{-- Section heading: gold icon + green label badge, with fading line. Align: 'left' or 'center' --}}
@php($align = $align ?? 'left')
<div class="d-flex align-items-center mb-3 {{ $align === 'center' ? 'justify-content-center' : '' }}">
    @if($align === 'center')
    <div style="flex:0 1 80px;height:2px;background:linear-gradient(to right,rgba(0,145,76,0) 0%,var(--nec-gold) 100%);margin-right:12px;"></div>
    @endif
    <span class="d-inline-flex align-items-stretch flex-shrink-0" style="border-radius:4px;overflow:hidden;">
        <span style="background:var(--nec-gold);color:#000;padding:8px 12px;display:flex;align-items:center;"><i class="fas {{ $icon }}"></i></span>
        <span class="fw-bold text-white px-3 py-2 d-flex align-items-center" style="background:var(--nec-green);letter-spacing:2px;font-size:0.7rem;">{{ $label }}</span>
    </span>
    <div style="flex:1;height:2px;background:linear-gradient(to right,var(--nec-gold) 0%,var(--nec-green) 50%,rgba(0,145,76,0) 100%);margin-left:12px;"></div>
</div>
