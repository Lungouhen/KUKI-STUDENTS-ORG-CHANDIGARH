@extends('layouts.admin')

@section('title', 'Certificate Preview | KSO CMS')

@section('content')
@php
    $submitRoute = match($mode) {
        'pending' => route('admin.memberDocuments.issue', $document_id),
        'direct' => route('admin.memberDocuments.generateDirect'),
        default => route('admin.memberDocuments.generateBulk'),
    };
    $borderColor = ['classic' => '#17365d', 'blue' => '#175ea8', 'forest' => '#246b4b'][$preview['style']] ?? '#17365d';
@endphp
<div class="container-fluid py-3">
    <h1 class="h3 fw-bold">Review certificate before issue</h1>
    @if($mode === 'bulk')
        <div class="alert {{ $excludedCount ? 'alert-warning' : 'alert-info' }}">
            <strong>{{ $eligibleCount }}</strong> active, approved members can be issued a certificate;
            <strong>{{ $excludedCount }}</strong> selected members are currently ineligible.
            @if($recipients->isNotEmpty())
                <div class="small mt-2">Previewing first recipient: {{ $recipients->first()->full_name }} ({{ $recipients->first()->id }}). {{ max(0, $recipientCount - 25) }} additional IDs may be included.</div>
            @endif
        </div>
        @if($eligibleCount === 0)
            <div class="alert alert-danger">There are no eligible recipients. No batch can be issued.</div>
        @endif
    @else
        <p class="text-muted">Recipient: <strong>{{ $member->full_name }}</strong> ({{ $member->id }}) · {{ $types[$template->document_type]['label'] ?? $template->document_type }} · template v{{ $template->version }}</p>
    @endif

    <section class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="mx-auto text-center p-5 border border-4" style="max-width:900px; min-height:420px; border-color:{{ $borderColor }} !important;">
                <p class="text-uppercase fw-bold">Kuki Students’ Organisation Chandigarh</p>
                <p class="small text-muted">Template version {{ $template->version }} · {{ ucfirst($template->style) }} style</p>
                <h2 class="display-6">{{ $preview['title'] }}</h2>
                <p class="small text-muted">Certificate No. PREVIEW-CERTIFICATE-NUMBER</p>
                <p class="body fs-4">{{ $preview['statement'] }}</p>
                <p class="mt-4">{{ $preview['details'] }}</p>
                <p class="small text-muted">Issued {{ now()->format('d M Y') }} · Preview only; final certificate number is generated on issue.</p>
            </div>
        </div>
    </section>

    <form action="{{ $submitRoute }}" method="POST" class="d-flex flex-wrap gap-2">
        @csrf
        <input type="hidden" name="template_id" value="{{ $template->id }}">
        <input type="hidden" name="document_details" value="{{ $details }}">
        @if($mode === 'direct') <input type="hidden" name="member_id" value="{{ $member->id }}"> @endif
        @if($mode === 'bulk')
            <input type="hidden" name="member_ids" value="{{ implode(',', $memberIds) }}">
            <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">
        @endif
        <a href="{{ route('admin.memberDocuments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-success" @disabled($mode === 'bulk' && $eligibleCount === 0)>
            @if($mode === 'bulk') Generate batch ({{ $eligibleCount }} eligible) @else Confirm and issue @endif
        </button>
    </form>
</div>
@endsection
