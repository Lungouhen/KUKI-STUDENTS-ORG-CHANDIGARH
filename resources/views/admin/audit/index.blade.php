@extends('layouts.admin')

@section('title', 'System Audit Trail Logs | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-list-check me-2"></i> System Audit Trail Logs</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '' }}</td>
                            <td class="fw-bold text-dark">{{ $log->user->name ?? 'System' }}</td>
                            <td><span class="badge bg-primary-lt text-primary fw-bold">{{ $log->action }}</span></td>
                            <td><code>{{ $log->ip_address }}</code></td>
                            <td>{{ $log->details }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No audit logs recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>

@endsection
