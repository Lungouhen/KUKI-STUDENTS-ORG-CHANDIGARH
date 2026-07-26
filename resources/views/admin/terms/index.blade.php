@extends('layouts.admin')

@section('title', 'Executive Terms & Years | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Executive Terms Management</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addTermModal">
        <i class="fa-solid fa-plus me-1"></i> New Term
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Term Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terms as $t)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $t->name }}</td>
                            <td>{{ $t->start_date }}</td>
                            <td>{{ $t->end_date }}</td>
                            <td>
                                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $t->is_active ? 'Active' : 'Past' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Term Modal -->
<div class="modal fade" id="addTermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">Define Executive Term</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.terms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Term Label (e.g. 2026-2027)</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Term</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
