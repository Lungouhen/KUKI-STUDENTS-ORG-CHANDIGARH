<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $document->typeLabel() }} · {{ $document->member_snapshot['full_name'] }}</title>
    <link href="{{ asset('css/pages/member-certificate.css') }}" rel="stylesheet">
    <script src="{{ asset('js/pages/member-certificate.js') }}" defer></script>
</head>
<body>
    <nav class="toolbar" aria-label="Certificate actions">
        <a href="{{ !empty($adminPreview) ? route('admin.members.show', $document->member_id) : route('membership.portalDashboard') }}">← {{ !empty($adminPreview) ? 'Return to member profile' : 'Return to member portal' }}</a>
        <button type="button" id="printMemberCertificate">Print / Save as PDF</button>
    </nav>
    @php
        $snapshot = $document->content_snapshot ?? [];
        $verificationUrl = $snapshot['verification_url'] ?? secure_url(route('documents.verify', $document->certificate_number, false));
        $certificateStyle = in_array($snapshot['style'] ?? 'classic', ['classic', 'blue', 'forest'], true) ? ($snapshot['style'] ?? 'classic') : 'classic';
    @endphp
    <main class="certificate {{ $certificateStyle }}">
        <img class="logo" src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Chandigarh emblem">
        <p class="org">Kuki Students’ Organisation Chandigarh</p>
        <h1>{{ $snapshot['title'] ?? $document->typeLabel() }}</h1>
        <p class="lead">Certificate No. {{ $document->certificate_number }}</p>
        <p class="body">This is to certify that</p>
        <p class="name">{{ $document->member_snapshot['full_name'] }}</p>
        <p class="body">{{ $snapshot['statement'] ?? $document->typeStatement() }}</p>
        <p class="details">{{ $snapshot['details'] ?? $document->document_details }}</p>
        <p class="member">
            Member ID: {{ $document->member_snapshot['id'] }}<br>
            Institution: {{ $document->member_snapshot['institution'] }}<br>
            @if($document->member_snapshot['course'])
                Course: {{ $document->member_snapshot['course'] }}@if($document->member_snapshot['year_of_study']) · {{ $document->member_snapshot['year_of_study'] }}@endif<br>
            @endif
        </p>
        <div class="bottom">
            <div class="verification">
                <strong>Issued:</strong> {{ $document->issued_at->format('d M Y') }}<br>
                <strong>Verify:</strong> {{ $verificationUrl }}<br>
                <img src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl={{ urlencode($verificationUrl) }}" alt="QR code for certificate verification" referrerpolicy="no-referrer">
            </div>
            <div class="signature">
                <strong>{{ $document->issued_by_name ?: 'Authorised Officer' }}</strong><br>
                For Kuki Students’ Organisation Chandigarh
            </div>
        </div>
    </main>
</body>
</html>
