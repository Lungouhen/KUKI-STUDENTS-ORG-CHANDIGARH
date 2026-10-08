@extends('layouts.admin')

@section('title', 'Executive Terms & Years | KSO Admin')

@section('content')

<div class="admin-terms-page">
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="termErrorsHeading">
        <h2 id="termErrorsHeading" class="h6 fw-bold">Review the executive term details</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Executive Terms Management</h1>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addTermModal">
        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> New Term
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="visually-hidden">Executive terms and active status</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Term Name</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($terms as $t)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $t->name }}</td>
                            <td>{{ $t->start_date }}</td>
                            <td>{{ $t->end_date }}</td>
                            <td>
                                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $t->is_active ? 'Active' : 'Past' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No executive terms have been defined.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Term Modal -->
<div class="modal fade" id="addTermModal" tabindex="-1" aria-labelledby="addTermModalTitle" data-reopen-on-error="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h2 class="h5 fw-bold" id="addTermModalTitle">Define Executive Term</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close executive term form"></button>
            </div>
            <form action="{{ route('admin.terms.store') }}" method="POST" id="createTermForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="termName" class="form-label extra-small fw-bold">Term Label (e.g. 2026-2027)</label>
                        <input id="termName" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="termStartDate" class="form-label extra-small fw-bold">Start Date</label>
                            <input id="termStartDate" type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="termEndDate" class="form-label extra-small fw-bold">End Date</label>
                            <input id="termEndDate" type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
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

</div>
@endsection
