@extends('layouts.admin')

@section('title', 'NGO Projects & Campaigns | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Project & Impact Management</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        <i class="fa-solid fa-plus me-1"></i> New Project
    </button>
</div>

<div class="row g-4">
    @foreach($projects as $p)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-lt text-primary extra-small">{{ $p->term->name ?? 'N/A' }} Term</span>
                        <span class="badge {{ $p->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $p->status }}</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $p->title }}</h5>
                    <p class="text-muted extra-small mb-3 line-clamp-3">{{ $p->description ?? 'No description provided.' }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="extra-small text-muted">Project Budget</div>
                        <div class="fw-bold text-primary">₹{{ number_format($p->budget) }}</div>
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">View Details</button>
                        <button class="btn btn-sm btn-light border text-danger"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Initiate New NGO Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.projects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Active Executive Term</label>
                        <select name="term_id" class="form-select" required>
                            @foreach($terms ?? [] as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Project Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Budget Allocation (₹)</label>
                        <input type="number" name="budget" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Initial Status</label>
                        <select name="status" class="form-select">
                            <option>Planned</option>
                            <option>Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Start Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
