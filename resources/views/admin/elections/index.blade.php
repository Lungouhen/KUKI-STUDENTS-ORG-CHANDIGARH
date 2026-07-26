@extends('layouts.admin')

@section('title', 'KSO Executive Elections | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Election Management System</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addElectionModal">
        <i class="fa-solid fa-plus me-1"></i> Create New Election
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Term</th>
                        <th>Position</th>
                        <th>Election Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elections as $e)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $e->term->name }}</td>
                            <td>{{ $e->position }}</td>
                            <td>{{ $e->election_date }}</td>
                            <td>
                                <span class="badge {{ $e->status === 'Completed' ? 'bg-success' : 'bg-primary' }}">
                                    {{ $e->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.elections.show', $e->id) }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-eye me-1"></i> View Results</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No elections scheduled yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Election Modal -->
<div class="modal fade" id="addElectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="fw-bold">Schedule New Election</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.elections.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Executive Term</label>
                        <select name="term_id" class="form-select" required>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Position Title (e.g. President)</label>
                        <input type="text" name="position" class="form-control" required placeholder="President / General Secretary">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Election Date</label>
                        <input type="date" name="election_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option>Scheduled</option>
                            <option>Ongoing</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Schedule Election</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
