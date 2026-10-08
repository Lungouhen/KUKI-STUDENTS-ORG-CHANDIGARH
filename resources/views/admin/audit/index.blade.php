@extends('layouts.admin')

@section('title', 'System Audit Trail Logs | KSO CMS')

@section('content')

<div class="admin-audit-page">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="h5 fw-bold text-primary mb-1"><i class="fa-solid fa-list-check me-2" aria-hidden="true"></i> System Audit Trail Logs</h1>
            <p class="small text-muted mb-0">Filter entries on this page; the stored audit history is unchanged.</p>
        </div>
        <div class="admin-audit-search">
            <label class="visually-hidden" for="auditLogSearch">Filter audit entries by user, action, IP, or details</label>
            <input id="auditLogSearch" type="search" class="form-control" placeholder="Filter current page">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <caption class="visually-hidden">Recent administrative audit entries</caption>
                <thead class="table-light">
                    <tr>
                        <th scope="col">Timestamp</th>
                        <th scope="col">User</th>
                        <th scope="col">Action</th>
                        <th scope="col">IP Address</th>
                        <th scope="col">Details</th>
                    </tr>
                </thead>
                <tbody id="auditLogRows">
                    @forelse($logs as $log)
                        <tr data-audit-log-row>
                            <td>{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '' }}</td>
                            <td class="fw-bold text-dark">{{ $log->user->name ?? 'System' }}</td>
                            <td><span class="badge bg-primary-lt text-primary fw-bold">{{ $log->action }}</span></td>
                            <td><code>{{ $log->ip_address }}</code></td>
                            <td>{{ $log->details }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No audit logs recorded yet.</td></tr>
                    @endforelse
                    @if($logs->isNotEmpty())
                        <tr id="auditLogNoResults" hidden><td colspan="5" class="text-center py-4 text-muted">No audit entries match that filter.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <p id="auditLogSearchStatus" class="visually-hidden" role="status" aria-live="polite"></p>
        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>

</div>
@endsection
