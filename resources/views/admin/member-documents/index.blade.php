@extends('layouts.admin')

@section('title', 'Certificates & Documents | KSO CMS')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Certificates & Documents</h1>
            <p class="text-muted mb-0">Review member requests, issue printable certificates, and revoke invalid documents.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <label for="document-status" class="visually-hidden">Filter by status</label>
            <select id="document-status" name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach($statuses as $item)
                    <option value="{{ $item }}" @selected($status === $item)>{{ ucfirst($item) }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Member</th><th>Document</th><th>Purpose / decision</th><th>Submitted</th><th>Status</th><th style="min-width: 310px;">Action</th></tr>
                </thead>
                <tbody>
                @forelse($documents as $document)
                    <tr>
                        <td>
                            <strong>{{ $document->member->full_name }}</strong>
                            <div class="small text-muted">{{ $document->member_id }}</div>
                            <div class="small text-muted">{{ $document->member->institution }}</div>
                        </td>
                        <td>{{ $document->typeLabel() }}</td>
                        <td class="small">
                            <div>{{ $document->purpose }}</div>
                            @if($document->resolution_note)
                                <div class="text-muted mt-1">Note: {{ $document->resolution_note }}</div>
                            @endif
                            @if($document->certificate_number)
                                <div class="text-muted mt-1">{{ $document->certificate_number }}</div>
                            @endif
                        </td>
                        <td>{{ $document->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge {{ ['issued' => 'bg-success', 'rejected' => 'bg-danger', 'revoked' => 'bg-secondary'][$document->status] ?? 'bg-warning text-dark' }}">{{ ucfirst($document->status) }}</span></td>
                        <td>
                            @if($document->status === 'pending')
                                @if($document->member->status === 'Approved' && $document->member->is_active)
                                    <form action="{{ route('admin.memberDocuments.issue', $document->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        <label for="details-{{ $document->id }}" class="form-label small fw-bold">Certificate details / activity</label>
                                        <textarea id="details-{{ $document->id }}" name="document_details" class="form-control form-control-sm mb-2" rows="2" maxlength="1500" minlength="5" required>{{ old('document_details') }}</textarea>
                                        <button class="btn btn-sm btn-success" type="submit"><i class="fa-solid fa-certificate me-1"></i> Issue</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning py-2 small mb-2">Member must be active and approved before issue.</div>
                                @endif
                                <form action="{{ route('admin.memberDocuments.reject', $document->id) }}" method="POST">
                                    @csrf
                                    <label for="reject-{{ $document->id }}" class="form-label small">Reason if rejecting</label>
                                    <div class="input-group input-group-sm">
                                        <input id="reject-{{ $document->id }}" name="resolution_note" class="form-control" maxlength="1000" minlength="5" required>
                                        <button class="btn btn-outline-danger" type="submit">Reject</button>
                                    </div>
                                </form>
                            @elseif($document->status === 'issued')
                                <a href="{{ route('documents.verify', $document->certificate_number) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mb-2">Verify</a>
                                <form action="{{ route('admin.memberDocuments.revoke', $document->id) }}" method="POST">
                                    @csrf
                                    <label for="revoke-{{ $document->id }}" class="form-label small">Reason if revoking</label>
                                    <div class="input-group input-group-sm">
                                        <input id="revoke-{{ $document->id }}" name="resolution_note" class="form-control" maxlength="1000" minlength="5" required>
                                        <button class="btn btn-outline-danger" type="submit">Revoke</button>
                                    </div>
                                </form>
                            @else
                                <span class="text-muted small">No further actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No document requests found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $documents->links() }}</div>
    </div>
</div>
@endsection
