@extends('layouts.admin')

@section('title', 'Certificates & Documents | KSO CMS')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Certificates & Documents</h1>
            <p class="text-muted mb-0">Review requests, issue versioned templates, generate member batches, and verify issued documents.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('admin.memberDocumentTemplates.index') }}">Manage templates</a>
        </div>
    </div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5">Generate for one member</h2>
                    <p class="small text-muted">Only active, approved members are eligible. Review the rendered certificate before confirming.</p>
                    <form action="{{ route('admin.memberDocuments.previewDirect') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-5">
                            <label class="form-label" for="direct-member">Member ID</label>
                            <input id="direct-member" name="member_id" class="form-control" maxlength="50" value="{{ request('member_id', old('member_id')) }}" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="direct-template">Template version</label>
                            <select id="direct-template" name="template_id" class="form-select" required>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $types[$template->document_type]['label'] ?? $template->document_type }} · v{{ $template->version }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="direct-details">Certificate details</label>
                            <textarea id="direct-details" name="document_details" class="form-control" rows="2" maxlength="1500" minlength="5" required>{{ old('document_details') }}</textarea>
                        </div>
                        <div class="col-12"><button class="btn btn-primary" type="submit">Preview individual certificate</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5">Generate a batch</h2>
                    <p class="small text-muted">Enter member IDs separated by commas/newlines, or use an institution/course cohort. Maximum 500 requested recipients per batch; every recipient is rechecked at issue time.</p>
                    <form action="{{ route('admin.memberDocuments.previewBulk') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-sm-4">
                            <label class="form-label" for="bulk-mode">Recipient source</label>
                            <select id="bulk-mode" name="selection_mode" class="form-select" required>
                                <option value="selected" @selected(old('selection_mode', 'selected') === 'selected')>Member IDs</option>
                                <option value="filter" @selected(old('selection_mode') === 'filter')>Institution / course</option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="bulk-ids">Member IDs</label>
                            <input id="bulk-ids" name="member_ids" class="form-control" placeholder="KSO-CHD-2026-0001, KSO-CHD-2026-0002" value="{{ old('member_ids') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="bulk-institution">Institution (exact cohort filter)</label>
                            <input id="bulk-institution" name="institution" class="form-control" maxlength="150" value="{{ old('institution') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="bulk-course">Course (exact cohort filter)</label>
                            <input id="bulk-course" name="course" class="form-control" maxlength="150" value="{{ old('course') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bulk-template">Template version</label>
                            <select id="bulk-template" name="template_id" class="form-select" required>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $types[$template->document_type]['label'] ?? $template->document_type }} · v{{ $template->version }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bulk-details">Shared certificate details</label>
                            <input id="bulk-details" name="document_details" class="form-control" maxlength="1500" minlength="5" value="{{ old('document_details') }}" required>
                        </div>
                        <div class="col-12"><button class="btn btn-outline-primary" type="submit">Preview recipients and certificates</button></div>
                    </form>
                    @if($errors->any()) <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div> @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white"><h2 class="h5 mb-0">Recent batches</h2></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Batch</th><th>Type / template</th><th>Status</th><th>Issued</th><th>Skipped</th><th>Failed</th><th>Created</th><th></th></tr></thead>
                <tbody>
                @forelse($batches as $batch)
                    <tr>
                        <td>#{{ $batch->id }}</td>
                        <td>{{ $types[$batch->document_type]['label'] ?? $batch->document_type }} · v{{ $batch->template_version }}</td>
                        <td>{{ ucfirst($batch->status) }}</td>
                        <td>{{ $batch->issued_count }}/{{ $batch->recipient_count }}</td>
                        <td>{{ $batch->skipped_count }}</td>
                        <td>{{ $batch->failed_count }}</td>
                        <td>{{ $batch->created_at->format('d M Y H:i') }}</td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.memberDocuments.batches.show', $batch->id) }}">Details</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted text-center py-3">No generation batches yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-3">Document register / lookup</h2>
            <form method="GET" action="{{ route('admin.memberDocuments.index') }}" class="row g-2">
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-cert">Certificate number (exact)</label><input id="filter-cert" name="certificate_number" class="form-control" value="{{ $filters['certificate_number'] ?? '' }}"></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-member">Member ID (exact)</label><input id="filter-member" name="member_id" class="form-control" value="{{ $filters['member_id'] ?? '' }}"></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-name">Member name</label><input id="filter-name" name="member_name" class="form-control" value="{{ $filters['member_name'] ?? '' }}"></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-type">Type</label><select id="filter-type" name="document_type" class="form-select"><option value="">All</option>@foreach($types as $type => $definition)<option value="{{ $type }}" @selected(($filters['document_type'] ?? '') === $type)>{{ $definition['label'] }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-status">Status</label><select id="filter-status" name="status" class="form-select"><option value="">All</option>@foreach($statuses as $item)<option value="{{ $item }}" @selected(($filters['status'] ?? '') === $item)>{{ ucfirst($item) }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-batch">Batch ID</label><input id="filter-batch" type="number" min="1" name="batch_id" class="form-control" value="{{ $filters['batch_id'] ?? '' }}"></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-from">Issued from</label><input id="filter-from" type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}"></div>
                <div class="col-sm-6 col-lg-2"><label class="form-label small" for="filter-to">Issued to</label><input id="filter-to" type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}"></div>
                <div class="col-12 d-flex gap-2"><button class="btn btn-primary" type="submit">Search register</button><a class="btn btn-outline-secondary" href="{{ route('admin.memberDocuments.index') }}">Clear</a></div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Member</th><th>Document</th><th>Purpose / decision</th><th>Issued / submitted</th><th>Status</th><th>Template / issuer / notification</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($documents as $document)
                    <tr>
                        <td><a href="{{ route('admin.members.show', $document->member_id) }}"><strong>{{ $document->member->full_name }}</strong></a><div class="small text-muted">{{ $document->member_id }} · {{ $document->member->institution }}</div></td>
                        <td>{{ $document->typeLabel() }}<div class="small text-muted">{{ $document->certificate_number }}</div></td>
                        <td class="small"><div>{{ $document->purpose }}</div>@if($document->resolution_note)<div class="text-muted">Note: {{ $document->resolution_note }}</div>@endif</td>
                        <td>{{ ($document->issued_at ?? $document->created_at)->format('Y-m-d') }}</td>
                        <td><span class="badge {{ ['issued' => 'bg-success', 'rejected' => 'bg-danger', 'revoked' => 'bg-secondary'][$document->status] ?? 'bg-warning text-dark' }}">{{ ucfirst($document->status) }}</span>@if($document->batch)<div><a class="small" href="{{ route('admin.memberDocuments.batches.show', $document->batch_id) }}">Batch #{{ $document->batch_id }}</a></div>@endif</td>
                        <td class="small">
                            {{ $document->template ? 'v'.$document->template_version : 'Legacy template' }}
                            <div class="text-muted">{{ $document->issued_by_name ?: '—' }}</div>
                            <div class="text-muted">Email: {{ ucfirst(str_replace('_', ' ', $document->notification_status)) }}</div>
                        </td>
                        <td style="min-width:260px">
                            @if($document->status === 'pending')
                                @if($document->member->status === 'Approved' && $document->member->is_active)
                                    <form action="{{ route('admin.memberDocuments.previewPending', $document->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        <select name="template_id" class="form-select form-select-sm mb-2" required>
                                            @foreach($templates->where('document_type', $document->document_type) as $template)<option value="{{ $template->id }}">{{ $types[$template->document_type]['label'] ?? $template->document_type }} · v{{ $template->version }}</option>@endforeach
                                        </select>
                                        <textarea name="document_details" class="form-control form-control-sm mb-2" rows="2" maxlength="1500" minlength="5" placeholder="Certificate details / activity" required></textarea>
                                        <button class="btn btn-sm btn-success" type="submit">Preview and issue</button>
                                    </form>
                                @else <span class="small text-warning">Member must be active and approved.</span> @endif
                                <form action="{{ route('admin.memberDocuments.reject', $document->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm"><input name="resolution_note" class="form-control" maxlength="1000" minlength="5" placeholder="Reason if rejecting" required><button class="btn btn-outline-danger" type="submit">Reject</button></div>
                                </form>
                            @elseif($document->status === 'issued')
                                <a href="{{ route('admin.memberDocuments.previewIssued', $document->id) }}" class="btn btn-sm btn-outline-secondary mb-2">Private preview</a>
                                <a href="{{ route('documents.verify', $document->certificate_number) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mb-2">Verify</a>
                                <a href="{{ route('admin.members.show', $document->member_id) }}" class="btn btn-sm btn-outline-secondary mb-2">Member profile</a>
                                <form action="{{ route('admin.memberDocuments.revoke', $document->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm"><input name="resolution_note" class="form-control" maxlength="1000" minlength="5" placeholder="Reason if revoking" required><button class="btn btn-outline-danger" type="submit">Revoke</button></div>
                                </form>
                            @else <span class="text-muted small">No further actions</span> @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">No documents found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $documents->links() }}</div>
    </div>
</div>
@endsection
