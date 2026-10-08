@extends('layouts.admin')

@section('title', 'Preview Document Template | KSO CMS')

@section('content')
<div class="container-fluid py-3">
    <h1 class="h3 fw-bold">Template preview</h1>
    <p class="text-muted">Sample values show where member information and issuance metadata will appear. No document has been issued.</p>
    <section class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="mx-auto text-center p-5 border border-4 {{ ['classic' => 'border-dark', 'blue' => 'border-primary', 'forest' => 'border-success'][$preview['style']] ?? 'border-dark' }}" style="max-width:900px; min-height:400px;">
                <p class="text-uppercase fw-bold">Kuki Students’ Organisation Chandigarh</p>
                <h2 class="display-6">{{ $preview['title'] }}</h2>
                <p class="text-muted">Certificate No. {{ $preview['verification_url'] === '' ? '' : 'PREVIEW-CERTIFICATE-NUMBER' }}</p>
                <p class="fs-4">{{ $preview['statement'] }}</p>
                <p class="mt-4">{{ $preview['details'] }}</p>
                <p class="small text-muted">Issued {{ now()->format('d M Y') }} · Sample Member · Panjab University</p>
            </div>
        </div>
    </section>
    <form action="{{ route('admin.memberDocumentTemplates.store') }}" method="POST" class="d-flex gap-2">
        @csrf
        @foreach($templateData as $key => $value)<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endforeach
        <a class="btn btn-outline-secondary" href="{{ route('admin.memberDocumentTemplates.index') }}">Back</a>
        <button class="btn btn-success" type="submit">Save as a new version</button>
    </form>
</div>
@endsection
