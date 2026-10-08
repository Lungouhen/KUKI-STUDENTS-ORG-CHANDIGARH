@extends('layouts.admin')

@section('title', 'NGO Projects & Campaigns | KSO Admin')

@section('content')

<div class="admin-projects-page">
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="projectErrorsHeading">
        <h2 id="projectErrorsHeading" class="h6 fw-bold">Review the project details</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Project & Impact Management</h1>
    @if($terms->isEmpty())
        <a href="{{ route('admin.terms.index') }}" class="btn btn-outline-primary">Add an executive term before creating a project</a>
    @else
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> New Project
        </button>
    @endif
</div>
@if($terms->isEmpty())
    <p class="small text-muted">Projects must be associated with an executive term.</p>
@endif

<div class="row g-4">
    @forelse($projects as $p)
        <div class="col-md-4">
            <article class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-lt text-primary extra-small">{{ $p->term->name ?? 'N/A' }} Term</span>
                        <span class="badge {{ $p->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $p->status }}</span>
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-2">{{ $p->title }}</h2>
                    <p class="text-muted extra-small mb-3 line-clamp-3">{{ $p->description ?? 'No description provided.' }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="extra-small text-muted">Project Budget</div>
                        <div class="fw-bold text-primary">₹{{ number_format($p->budget, 2) }}</div>
                    </div>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-muted mb-0">No projects have been created yet.</div>
        </div>
    @endforelse
</div>
<div class="mt-3">{{ $projects->links() }}</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalTitle" data-reopen-on-error="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h2 class="h5 fw-bold" id="addProjectModalTitle">Initiate New NGO Project</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close project form"></button>
            </div>
            <form action="{{ route('admin.projects.store') }}" method="POST" id="createProjectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projectTerm" class="form-label extra-small fw-bold">Executive Term</label>
                        <select id="projectTerm" name="term_id" class="form-select" required>
                            @foreach($terms ?? [] as $t)
                                <option value="{{ $t->id }}" @selected(old('term_id') == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="project-template">Start from a built-in template</label>
                        <select id="project-template" class="form-select">
                            <option value="">Start from scratch</option>
                            @foreach($projectTemplates as $key => $template)
                                <option value="{{ $key }}" data-title="{{ $template['title'] }}" data-description="{{ $template['description'] }}" data-summary="{{ $template['summary'] }}">{{ $template['name'] }}</option>
                            @endforeach
                        </select>
                        <div id="project-template-summary" class="form-text" role="status" aria-live="polite">Choose an optional starting point; you can edit all generated text.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="project-title">Project Title</label>
                        <input type="text" name="title" id="project-title" class="form-control" value="{{ old('title') }}" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="project-description">Project Description</label>
                        <textarea name="description" id="project-description" class="form-control" rows="4" maxlength="10000">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="projectBudget">Budget Allocation (₹)</label>
                        <input id="projectBudget" type="number" name="budget" class="form-control" value="{{ old('budget', '0') }}" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="projectStatus">Initial Status</label>
                        <select id="projectStatus" name="status" class="form-select" required>
                            <option value="Planned" @selected(old('status', 'Planned') === 'Planned')>Planned</option>
                            <option value="Active" @selected(old('status') === 'Active')>Active</option>
                            <option value="Completed" @selected(old('status') === 'Completed')>Completed</option>
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
