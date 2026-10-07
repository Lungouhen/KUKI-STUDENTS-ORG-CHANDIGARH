<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $document->typeLabel() }} · {{ $document->member_snapshot['full_name'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; background: #eef2f7; color: #17233b; font: 16px/1.6 Georgia, "Times New Roman", serif; }
        .toolbar { max-width: 980px; margin: 0 auto 16px; display: flex; justify-content: space-between; font: 14px Arial, sans-serif; }
        .certificate { position: relative; max-width: 980px; min-height: 680px; margin: auto; padding: 56px 70px; background: #fff; border: 12px double #17365d; box-shadow: 0 12px 40px #14243b22; text-align: center; }
        .logo { width: 86px; height: 86px; object-fit: contain; }
        .org { margin: 10px 0 0; color: #17365d; font: bold 14px Arial, sans-serif; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 34px 0 8px; color: #17365d; font-size: 34px; text-transform: uppercase; letter-spacing: .06em; }
        .lead { color: #68758b; font: 14px Arial, sans-serif; }
        .name { margin: 20px 0; font-size: 32px; font-weight: bold; }
        .body { max-width: 700px; margin: 0 auto; font-size: 19px; }
        .details { max-width: 700px; margin: 20px auto 0; font-size: 17px; white-space: pre-line; }
        .member { margin: 22px 0; color: #46536a; font: 14px/1.8 Arial, sans-serif; }
        .bottom { display: flex; justify-content: space-between; align-items: end; gap: 24px; margin-top: 60px; font: 12px Arial, sans-serif; text-align: left; }
        .signature { min-width: 220px; padding-top: 10px; border-top: 1px solid #59677b; text-align: center; }
        .verification { max-width: 300px; overflow-wrap: anywhere; color: #526078; }
        @media (max-width: 700px) { body { padding: 10px; } .certificate { min-height: 0; padding: 30px 20px; } .bottom { flex-direction: column; align-items: stretch; } h1 { font-size: 25px; } .name { font-size: 25px; } }
        @media print { body { padding: 0; background: #fff; } .toolbar { display: none; } .certificate { max-width: none; min-height: 100vh; border-width: 10px; box-shadow: none; break-inside: avoid; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('membership.portalDashboard') }}">← Return to member portal</a>
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
    </div>
    <main class="certificate">
        <img class="logo" src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Chandigarh emblem">
        <p class="org">Kuki Students’ Organisation Chandigarh</p>
        <h1>{{ $document->typeLabel() }}</h1>
        <p class="lead">Certificate No. {{ $document->certificate_number }}</p>
        <p class="body">This is to certify that</p>
        <p class="name">{{ $document->member_snapshot['full_name'] }}</p>
        <p class="body">{{ $document->typeStatement() }}</p>
        <p class="details">{{ $document->document_details }}</p>
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
                <strong>Verify:</strong> {{ route('documents.verify', $document->certificate_number) }}
            </div>
            <div class="signature">
                <strong>{{ $document->issued_by_name ?: 'Authorised Officer' }}</strong><br>
                For Kuki Students’ Organisation Chandigarh
            </div>
        </div>
    </main>
</body>
</html>
