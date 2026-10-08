@extends('layouts.admin')

@section('title', 'Certificate Batch #' . $batch->id . ' | KSO CMS')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div><h1 class="h3 fw-bold mb-1">Batch #{{ $batch->id }}</h1><div class="text-muted">{{ $batch->created_at->format('d M Y H:i') }} · {{ $batch->template?->title ?? 'Template unavailable' }} · version {{ $batch->template_version }}</div></div>
        <div class="d-flex gap-2">
            @if($batch->issued_count > 0)<a href="{{ route('admin.memberDocuments.batches.print', $batch->id) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">Print batch</a>@endif
            @if(in_array($batch->status, ['partial', 'failed', 'processing'], true))
                <form action="{{ route('admin.memberDocuments.batches.retry', $batch->id) }}" method="POST">@csrf<button class="btn btn-warning" type="submit">{{ $batch->status === 'processing' ? 'Resume incomplete recipients' : 'Retry failed recipients' }}</button></form>
            @endif
            <a href="{{ route('admin.memberDocuments.index') }}" class="btn btn-outline-secondary">Back to register</a>
        </div>
    </div>

    @php $progress = $batch->recipient_count ? (int) round(($batch->issued_count / $batch->recipient_count) * 100) : 0; @endphp
    <div class="card border-0 shadow-sm mb-4"><div class="card-body">
        <div class="d-flex flex-wrap gap-4 mb-3">
            <div><strong>Status</strong><div>{{ ucfirst($batch->status) }}</div></div>
            <div><strong>Template</strong><div>{{ $batch->document_type }} · v{{ $batch->template_version }}</div></div>
            <div><strong>Recipients</strong><div>{{ $batch->recipient_count }}</div></div>
            <div><strong>Issued</strong><div>{{ $batch->issued_count }}</div></div>
            <div><strong>Skipped</strong><div>{{ $batch->skipped_count }}</div></div>
            <div><strong>Failed</strong><div>{{ $batch->failed_count }}</div></div>
        </div>
        <div class="progress" role="progressbar" aria-label="Batch issuance progress" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width:{{ $progress }}%">{{ $progress }}%</div></div>
        <p class="small text-muted mt-3 mb-0">Shared certificate details: {{ $batch->shared_details }}</p>
    </div></div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Member</th><th>Outcome</th><th>Certificate</th><th>Notification</th><th>Result / error</th></tr></thead>
            <tbody>
            @foreach($batch->items as $item)
                <tr>
                    <td><a href="{{ route('admin.members.show', $item->member_id) }}">{{ $item->member?->full_name ?? 'Member record' }}</a><div class="small text-muted">{{ $item->member_id }}</div></td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>@if($item->document)<a href="{{ route('documents.verify', $item->document->certificate_number) }}">{{ $item->document->certificate_number }}</a>@else—@endif</td>
                    <td>{{ $item->document ? ucfirst(str_replace('_', ' ', $item->document->notification_status)) : '—' }}</td>
                    <td>{{ $item->message ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    </div>
</div>
@endsection
