@extends('layouts.admin')

@section('title', 'Medical Emergency Relief Desk | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-danger mb-0"><i class="fa-solid fa-notes-medical me-2"></i> Medical Emergency Relief Applications</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Member ID</th>
                        <th>Patient Name</th>
                        <th>Hospital</th>
                        <th>Nature of Illness</th>
                        <th>Requested Amount</th>
                        <th>Approved Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $c)
                        <tr>
                            <td class="fw-bold text-primary">{{ $c->member_id }}</td>
                            <td class="fw-bold text-dark">{{ $c->patient_name }}</td>
                            <td>{{ $c->hospital_name }}</td>
                            <td>{{ $c->nature_of_illness }}</td>
                            <td class="fw-bold text-primary">₹{{ number_format($c->amount_requested) }}</td>
                            <td class="fw-bold text-success">₹{{ number_format($c->amount_approved) }}</td>
                            <td><span class="badge {{ $c->status === 'Disbursed' ? 'bg-success' : ($c->status === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ $c->status }}</span></td>
                            <td>
                                <form action="{{ route('admin.medical.updateStatus', $c->id) }}" method="POST" class="d-inline-flex gap-1">
                                    @csrf
                                    <input type="number" name="amount_approved" value="{{ $c->amount_requested }}" class="form-control form-control-sm" style="width:90px;">
                                    <select name="status" class="form-select form-select-sm" style="width:110px;">
                                        <option value="Pending" {{ $c->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ $c->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Disbursed" {{ $c->status == 'Disbursed' ? 'selected' : '' }}>Disbursed</option>
                                        <option value="Rejected" {{ $c->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary py-0">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No active medical relief claims.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $claims->links() }}
        </div>
    </div>
</div>

@endsection
