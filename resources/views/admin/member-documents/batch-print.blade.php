<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Batch #{{ $batch->id }} certificates</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 20px; font: 14px/1.5 Georgia, serif; color: #17233b; }
        .toolbar { margin: 0 auto 16px; max-width: 980px; font: 14px Arial, sans-serif; }
        .certificate { min-height: 680px; max-width: 980px; margin: 0 auto 24px; padding: 48px; border: 10px double var(--certificate-color); text-align: center; break-after: page; display: flex; flex-direction: column; justify-content: space-between; }
        .certificate:last-child { break-after: auto; }
        .title { color: var(--certificate-color); font-size: 32px; text-transform: uppercase; }
        .name { font-size: 30px; font-weight: bold; }
        .statement { max-width: 700px; margin: 16px auto; font-size: 18px; }
        .bottom { display: flex; justify-content: space-between; gap: 20px; text-align: left; font: 12px Arial,sans-serif; }
        .qr { width: 90px; height: 90px; }
        @media print { body { padding: 0; } .toolbar { display: none; } .certificate { max-width: none; margin: 0; min-height: 100vh; break-after: page; } }
    </style>
</head>
<body>
<div class="toolbar"><button type="button" onclick="window.print()">Print / Save batch as PDF</button></div>
@foreach($batch->documents as $document)
    @php
        $snapshot = $document->content_snapshot ?? [];
        $verificationUrl = $snapshot['verification_url'] ?? secure_url(route('documents.verify', $document->certificate_number, false));
        $color = ['classic' => '#17365d', 'blue' => '#175ea8', 'forest' => '#246b4b'][$snapshot['style'] ?? 'classic'] ?? '#17365d';
        $member = $document->member_snapshot ?? [];
    @endphp
    <main class="certificate" style="--certificate-color:{{ $color }}">
        <div><p class="text-uppercase">Kuki Students’ Organisation Chandigarh</p><h1 class="title">{{ $snapshot['title'] ?? $document->typeLabel() }}</h1><p>Certificate No. {{ $document->certificate_number }}</p></div>
        <div><p>This is to certify that</p><p class="name">{{ $member['full_name'] ?? '' }}</p><p class="statement">{{ $snapshot['statement'] ?? $document->typeStatement() }}</p><p>{{ $snapshot['details'] ?? $document->document_details }}</p></div>
        <div class="bottom">
            <div><strong>Issued:</strong> {{ $document->issued_at?->format('d M Y') }}<br><strong>Institution:</strong> {{ $member['institution'] ?? '' }}<br><strong>Issuer:</strong> {{ $document->issued_by_name }}</div>
            <div><img class="qr" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl={{ urlencode($verificationUrl) }}" alt="QR code for certificate verification" referrerpolicy="no-referrer"><br>{{ $document->certificate_number }}</div>
        </div>
    </main>
@endforeach
</body>
</html>
