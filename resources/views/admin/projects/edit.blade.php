@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Project</h4>
        <p class="text-muted extra-small mb-0">
            {{ $project->project_code ?? 'Project #'.$project->id }} — update the campaign details.
        </p>
    </div>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-light border btn-sm rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Projects
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Project Title</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $project->title) }}" required>
                        @error('title')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Executive Term</label>
                            <select name="term_id" class="form-select" required>
                                @foreach($terms as $t)
                                    <option value="{{ $t->id }}" @selected(old('term_id', $project->term_id) == $t->id)>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('term_id')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['Planned', 'Active', 'Completed', 'On Hold'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Budget (₹)</label>
                            <input type="number" step="0.01" min="0" name="budget" class="form-control"
                                   value="{{ old('budget', $project->budget) }}" required>
                            @error('budget')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label extra-small fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light border rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
