@extends('layouts.admin')

@section('title', 'Election Details & Results | KSO Admin')

@section('content')

<div class="admin-election-results-page">
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="electionResultErrorsHeading">
        <h2 id="electionResultErrorsHeading" class="h6 fw-bold">Review the election update</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-0">{{ $election->position }} Election</h1>
        <p class="text-muted extra-small">Term: {{ $election->term->name }} • Date: {{ $election->election_date }}</p>
    </div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-light border btn-sm rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Back to List
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-0 d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Candidates & Live Tally</h2>
                @if($members->isEmpty())
                    <span class="small text-muted">No approved members are available to nominate.</span>
                @else
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Add Candidate
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <caption class="visually-hidden">Candidates and recorded vote totals for {{ $election->position }} election</caption>
                        <thead class="bg-light text-muted extra-small text-uppercase">
                            <tr>
                                <th scope="col" class="ps-4">Candidate Name</th>
                                <th scope="col">Member ID</th>
                                <th scope="col">Votes</th>
                                <th scope="col" class="text-end pe-4" data-column-manager-exclude>Manage Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($election->candidates as $c)
                                <tr class="extra-small">
                                    <td class="ps-4 fw-bold text-dark">{{ $c->member->full_name }}</td>
                                    <td>{{ $c->member->id }}</td>
                                    <td class="fs-6 fw-black text-primary">{{ $c->votes_received }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.candidates.updateVotes', $c->id) }}" method="POST" class="d-inline-flex gap-1 candidate-vote-form">
                                            @csrf
                                            <label class="visually-hidden" for="candidateVotes-{{ $c->id }}">Vote total for {{ $c->member->full_name }}</label>
                                            <input id="candidateVotes-{{ $c->id }}" type="number" name="votes" value="{{ $c->votes_received }}" min="0" max="2147483647" step="1" class="form-control form-control-sm" required>
                                            <button type="submit" class="btn btn-sm btn-success" aria-label="Save vote total for {{ $c->member->full_name }}"><i class="fa-solid fa-save" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No candidates added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4">
            <h2 class="h6 fw-bold mb-3">Election Status</h2>
            <div class="badge {{ ['Completed' => 'bg-success', 'Ongoing' => 'bg-primary', 'Scheduled' => 'bg-secondary'][$election->status] ?? 'bg-secondary' }} fs-6 py-2 mb-3">
                {{ strtoupper($election->status) }}
            </div>
            <hr>
            <div class="extra-small text-muted mb-2">Total Votes Polled</div>
            <p class="h2 fw-black text-dark" aria-label="Total votes polled: {{ $election->candidates->sum('votes_received') }}">{{ $election->candidates->sum('votes_received') }}</p>
        </div>
    </div>
</div>

<!-- Add Candidate Modal -->
<div class="modal fade" id="addCandidateModal" tabindex="-1" aria-labelledby="addCandidateModalTitle" data-reopen-on-candidate-error="{{ $errors->has('member_id') ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="h5 fw-bold" id="addCandidateModalTitle">Nominate Candidate</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close candidate form"></button>
            </div>
            <form action="{{ route('admin.elections.addCandidate', $election->id) }}" method="POST" class="candidate-add-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="candidateMember" class="form-label extra-small fw-bold">Select Member</label>
                        <select id="candidateMember" name="member_id" class="form-select border shadow-none" required>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" @selected(old('member_id') === $m->id)>{{ $m->full_name }} ({{ $m->id }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Add to Ballot</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
