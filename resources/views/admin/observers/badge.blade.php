@php $app = $application; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $app->accreditation_number }} - NEC Observer Accreditation</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Georgia, 'Times New Roman', serif; background: #e8eaed; color: #111; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .page { max-width: 880px; margin: 24px auto; background: #fff; position: relative; overflow: hidden; }
        .guilloche {
            position: absolute; inset: 0; pointer-events: none; opacity: .55;
            background:
                repeating-linear-gradient(0deg, rgba(22,101,52,.05) 0 1px, transparent 1px 9px),
                repeating-linear-gradient(90deg, rgba(22,101,52,.05) 0 1px, transparent 1px 9px),
                radial-gradient(circle at 20% 20%, rgba(22,101,52,.04) 0 12%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(22,101,52,.04) 0 12%, transparent 40%);
        }
        .sheet { position: relative; border: 10px solid #166534; outline: 2px solid #f0fdf4; padding: 22px; }
        .sheet.cert-sheet { outline: 2px dashed #16a34a; outline-offset: 6px; }
        .header { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px double #166534; padding-bottom: 14px; }
        .header .crest img { height: 84px; }
        .header .brand { text-align: center; }
        .header .brand h1 { font-size: 1.35rem; letter-spacing: .3px; color: #14532d; font-weight: 800; text-transform: uppercase; }
        .header .brand h2 { font-size: 1rem; color: #166534; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
        .header .brand p { font-size: .74rem; color: #555; margin-top: 3px; letter-spacing: .6px; text-transform: uppercase; }
        .title { text-align: center; margin: 20px 0 4px; }
        .title h3 { font-size: 1.6rem; color: #0f2a17; letter-spacing: 4px; text-transform: uppercase; font-weight: 700; }
        .title p { font-size: .8rem; color: #555; letter-spacing: 1px; margin-top: 4px; }
        .ribbon { display: inline-block; margin-top: 10px; padding: 8px 26px; background: linear-gradient(90deg,#166534,#1f7a46); color: #fff; font-size: 1.15rem; letter-spacing: 3px; font-family: 'Courier New', monospace; font-weight: 700; }
        .body { display: flex; gap: 22px; margin-top: 22px; }
        .photo { width: 124px; height: 164px; border: 2px solid #166534; object-fit: cover; flex-shrink: 0; background: #f3f4f6; }
        .holder { flex: 1; }
        .holder table { width: 100%; border-collapse: collapse; font-size: .95rem; }
        .holder td { padding: 7px 8px; border-bottom: 1px dotted #9ca3af; }
        .holder td.k { color: #444; width: 170px; font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; }
        .holder td.v { color: #0f172a; font-weight: 600; }
        .accred-no { font-family: 'Courier New', monospace; color: #166534; font-size: 1.05rem; letter-spacing: 1px; }
        .qr-panel { width: 190px; flex-shrink: 0; border-left: 2px solid #166534; padding-left: 14px; text-align: center; }
        .qr-panel svg { width: 118px; height: 118px; }
        .qr-panel .code { font-family: 'Courier New', monospace; font-size: .66rem; color: #166534; letter-spacing: 1px; word-break: break-all; margin-top: 6px; }
        .qr-panel .lbl { font-size: .68rem; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
        .pane { margin: 18px 0 0; }
        .years { display: flex; justify-content: space-between; margin-top: 18px; }
        .years div { font-size: .78rem; color: #444; text-align: center; }
        .years .line { border-top: 1px solid #555; width: 200px; margin-top: 4px; padding-top: 3px; }
        .terms { margin-top: 16px; border-top: 1px solid #166534; padding-top: 10px; display: flex; justify-content: space-between; gap: 12px; font-size: .62rem; color: #555; line-height: 1.45; }
        .micro { font-size: .5rem; color: #6b7280; letter-spacing: 2px; text-transform: uppercase; text-align: center; margin-top: 12px; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-size: 7rem; font-weight: 900; color: rgba(22,101,52,.045); letter-spacing: 14px; user-select: none; z-index: 0; white-space: nowrap; }
        .toolbar { max-width: 880px; margin: 16px auto 8px; text-align: center; }
        .toolbar button { font-family: sans-serif; background: #166534; color: #fff; border: 0; padding: 10px 22px; border-radius: 6px; font-size: .9rem; cursor: pointer; margin-right: 6px; }
        @media print {
            body { background: #fff; }
            .page { margin: 0; max-width: 100%; box-shadow: none; }
            .toolbar { display: none; }
            @page { size: A4 landscape; margin: 8mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar not-print">
        <button onclick="window.print()">Print Accreditation</button>
    </div>
    @include('admin.observers._badge-sheet', ['app' => $app])
</body>
</html>