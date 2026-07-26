@extends('layouts.admin')

@section('title', 'Election Details & Results | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-0">{{ $election->position }} Election</h4>
        <p class="text-muted extra-small">Term: {{ $election->term->name }} • Date: {{ $election->election_date }}</p>
    </div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-light border btn-sm rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Candidates & Live Tally</h6>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Candidate
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted extra-small text-uppercase">
                            <tr>
                                <th class="ps-4">Candidate Name</th>
                                <th>Member ID</th>
                                <th>Votes</th>
                                <th class="text-end pe-4">Manage Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($election->candidates as $c)
                                <tr class="extra-small">
                                    <td class="ps-4 fw-bold text-dark">{{ $c->member->full_name }}</td>
                                    <td>{{ $c->member->id }}</td>
                                    <td class="fs-6 fw-black text-primary">{{ $c->votes_received }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.candidates.updateVotes', $c->id) }}" method="POST" class="d-inline-flex gap-1">
                                            @csrf
                                            <input type="number" name="votes" value="{{ $c->votes_received }}" class="form-control form-control-sm" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-save"></i></button>
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
            <h6 class="fw-bold mb-3">Election Status</h6>
            <div class="badge {{ $election->status === 'Completed' ? 'bg-success' : 'bg-primary' }} fs-6 py-2 mb-3">
                {{ strtoupper($election->status) }}
            </div>
            <hr>
            <div class="extra-small text-muted mb-2">Total Votes Polled</div>
            <h2 class="fw-black text-dark">{{ $election->candidates->sum('votes_received') }}</h2>
        </div>
    </div>
</div>

<!-- Add Candidate Modal -->
<div class="modal fade" id="addCandidateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Nominate Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.elections.addCandidate', $election->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Select Member</label>
                        <select name="member_id" class="form-select border shadow-none" required>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}">{{ $m->full_name }} ({{ $m->id }})</option>
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

@endsection
