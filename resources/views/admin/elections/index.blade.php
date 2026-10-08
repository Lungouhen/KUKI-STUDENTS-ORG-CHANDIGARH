@extends('layouts.admin')

@section('title', 'KSO Executive Elections | KSO Admin')

@section('content')

<div class="admin-elections-page">
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="electionErrorsHeading">
        <h2 id="electionErrorsHeading" class="h6 fw-bold">Review the election details</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Election Management System</h1>
    @if($terms->isEmpty())
        <a href="{{ route('admin.terms.index') }}" class="btn btn-outline-primary">Add an executive term before scheduling</a>
    @else
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addElectionModal">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Create New Election
        </button>
    @endif
</div>
@if($terms->isEmpty())
    <p class="small text-muted">Create a term first so the election can be associated with an executive period.</p>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="visually-hidden">Scheduled and completed executive elections</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Term</th>
                        <th scope="col">Position</th>
                        <th scope="col">Election Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elections as $e)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $e->term->name }}</td>
                            <td>{{ $e->position }}</td>
                            <td>{{ $e->election_date }}</td>
                            <td>
                                <span class="badge {{ ['Completed' => 'bg-success', 'Ongoing' => 'bg-primary', 'Scheduled' => 'bg-secondary'][$e->status] ?? 'bg-secondary' }}">
                                    {{ $e->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.elections.show', $e->id) }}" class="btn btn-sm btn-light border" aria-label="View results for {{ $e->position }} election"><i class="fa-solid fa-eye me-1" aria-hidden="true"></i> View Results</a>
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
<div class="modal fade" id="addElectionModal" tabindex="-1" aria-labelledby="addElectionModalTitle" data-reopen-on-error="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h2 class="h5 fw-bold" id="addElectionModalTitle">Schedule New Election</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close election form"></button>
            </div>
            <form action="{{ route('admin.elections.store') }}" method="POST" id="createElectionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="electionTerm" class="form-label extra-small fw-bold">Executive Term</label>
                        <select id="electionTerm" name="term_id" class="form-select" required>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}" @selected(old('term_id') == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="electionPosition" class="form-label extra-small fw-bold">Position Title (e.g. President)</label>
                        <input id="electionPosition" type="text" name="position" class="form-control" value="{{ old('position') }}" required placeholder="President / General Secretary">
                    </div>
                    <div class="mb-3">
                        <label for="electionDate" class="form-label extra-small fw-bold">Election Date</label>
                        <input id="electionDate" type="date" name="election_date" class="form-control" value="{{ old('election_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="electionStatus" class="form-label extra-small fw-bold">Status</label>
                        <select id="electionStatus" name="status" class="form-select">
                            @foreach(['Scheduled', 'Ongoing', 'Completed'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'Scheduled') === $status)>{{ $status }}</option>
                            @endforeach
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

</div>
@endsection
