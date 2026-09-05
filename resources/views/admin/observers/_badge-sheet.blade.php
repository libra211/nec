@php
    /** @var \App\Models\ObserverApplication $app */
    $idNo = $app->form_type === 'domestic' ? ($app->national_id ?: '—') : ($app->passport_number ?: '—');
    $issued = $app->approved_at ? $app->approved_at->format('d M Y') : now()->format('d M Y');
    $verify = \App\Helpers\NecHelper::accreditation_verify_url($app) ?? url('/');
    $obvCode = $app->verification_token ? substr($app->verification_token, 0, 16) : 'OBV-NONE';
@endphp
<div class="page">
    <div class="sheet cert-sheet">
        <div class="guilloche"></div>
        <div class="watermark">VALID</div>
        <div style="position:relative;">
            <div class="header">
                <div class="crest"><img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Crest"></div>
                <div class="brand">
                    <h1>Republic of South Sudan</h1>
                    <h2>National Election Commission</h2>
                    <p>Election Observation Accreditation</p>
                </div>
                <div style="width:84px;display:flex;align-items:flex-end;flex-direction:column;gap:6px;">
                    <div style="font-family:'Courier New',monospace;font-size:.6rem;color:#166534;text-align:right;">OBV {{ substr($obvCode, 0, 12) }}…</div>
                    <div style="font-size:.58rem;color:#555;text-align:right;text-transform:uppercase;letter-spacing:.5px;">Anti-Photocopy Serial</div>
                </div>
            </div>

            <div class="title">
                <h3>Observer Accreditation</h3>
                <p>Election Observer Credential &middot; 2026 General Elections</p>
                <span class="ribbon">{{ $app->accreditation_number }}</span>
            </div>

            <div class="body">
                <div class="photo">
                    @if($app->passport_photo)
                        <img src="{{ Storage::disk('public')->url($app->passport_photo) }}" alt="photo" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
                <div class="holder">
                    <table>
                        <tr><td class="k">Holder Name</td><td class="v">{{ $app->full_name }}</td></tr>
                        <tr><td class="k">Category</td><td class="v">{{ ucfirst($app->form_type) }} Observer</td></tr>
                        <tr><td class="k">Nationality</td><td class="v">{{ $app->nationality }}</td></tr>
                        <tr><td class="k">Identity No.</td><td class="v">{{ $idNo }}</td></tr>
                        <tr><td class="k">Organization</td><td class="v">{{ $app->organization_name ?: '—' }}</td></tr>
                        <tr><td class="k">Languages</td><td class="v">{{ $app->languages ?: '—' }}</td></tr>
                        <tr><td class="k">Batch</td><td class="v">{{ $app->batch ? $app->batch->batch_number : '—' }}</td></tr>
                        <tr><td class="k">Accreditation No.</td><td class="v accred-no">{{ $app->accreditation_number }}</td></tr>
                    </table>
                </div>
                <div class="qr-panel">
                    {!! \App\Helpers\NecHelper::qr_svg($verify) !!}
                    <div class="lbl">Scan to verify</div>
                    <div class="code">{{ $obvCode }}</div>
                </div>
            </div>

            <div class="pane">
                <div class="years">
                    <div>
                        <div>Signed by Authority</div>
                        <div class="line">Chairperson, National Election Commission</div>
                    </div>
                    <div>
                        <div>Issued Date</div>
                        <div class="line">{{ $issued }}</div>
                    </div>
                </div>

                <div class="terms">
                    <span>1. This credential authorizes the named observer to monitor polling, counting and tallying processes.</span>
                    <span>2. Must be presented with a government-issued ID. Not transferable.</span>
                    <span>3. Authenticity can be confirmed on the NEC website (verify page) using the QR code or serial above.</span>
                </div>

                <div class="micro">
                    N E C - S O U T H &nbsp; S U D A N &nbsp; &middot; &nbsp; T H I S &nbsp; D O C U M E N T &nbsp; I S &nbsp; A N T I - P H O T O C O P Y &nbsp; P R O T E C T E D &nbsp; &middot; &nbsp; A N Y &nbsp; C O P Y &nbsp; I S &nbsp; I N V A L I D &nbsp; &middot; &nbsp; V E R I F Y &nbsp; O N L I N E &nbsp; &middot; &nbsp; {{ $app->accreditation_number }} &nbsp; &middot;
                </div>
            </div>
        </div>
    </div>
</div>