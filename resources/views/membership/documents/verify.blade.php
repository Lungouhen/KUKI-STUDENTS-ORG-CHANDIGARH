<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Certificate verification | KSO Chandigarh</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pages/certificate-verification.css') }}" rel="stylesheet">
</head>
<body class="certificate-verification-page bg-light">
    <main class="container py-5">
        <section class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <p class="text-uppercase small fw-bold text-primary mb-2">Kuki Students’ Organisation Chandigarh</p>
                @if(!$document)
                    <div class="verification-status verification-status--missing" role="alert">
                        <h1 class="h3">Certificate not found</h1>
                        <p class="mb-0">No issued certificate matches this certificate number.</p>
                    </div>
                @elseif($document->status === 'issued')
                    <div class="verification-status verification-status--valid" role="status" aria-live="polite">
                        <h1 class="h3">Valid certificate</h1>
                        <p class="mb-0">This certificate number matches an active record in the organisation’s register.</p>
                    </div>
                @else
                    <div class="verification-status verification-status--revoked" role="status" aria-live="polite">
                        <h1 class="h3">Certificate revoked</h1>
                        <p class="mb-0">This certificate is no longer valid. Contact KSO Chandigarh if you need more information.</p>
                    </div>
                @endif
                @if($document)
                <dl class="row mt-4 mb-0">
                    <dt class="col-sm-4">Certificate</dt><dd class="col-sm-8">{{ $document->certificate_number }}</dd>
                    <dt class="col-sm-4">Document type</dt><dd class="col-sm-8">{{ $document->typeLabel() }}</dd>
                    <dt class="col-sm-4">Member</dt><dd class="col-sm-8">{{ $document->member_snapshot['full_name'] ?? 'Member record' }}</dd>
                    <dt class="col-sm-4">Member ID</dt><dd class="col-sm-8">••••{{ substr($document->member_snapshot['id'] ?? $document->member_id, -4) }}</dd>
                    <dt class="col-sm-4">Issued</dt><dd class="col-sm-8">{{ $document->issued_at?->format('d M Y') }}</dd>
                </dl>
                <p class="small text-muted border-top pt-3 mt-4 mb-0">This page verifies certificate status only. It does not display the certificate’s purpose or personal contact information.</p>
                @endif
            </div>
        </section>
    </main>
</body>
</html>
