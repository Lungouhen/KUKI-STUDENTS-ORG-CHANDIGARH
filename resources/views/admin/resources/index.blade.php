@extends('layouts.admin')

@section('title', 'Student Resource Library | KSO Admin')

@section('content')

<div class="admin-resources-page">
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="resourceErrorsHeading">
        <h2 id="resourceErrorsHeading" class="h6 fw-bold">The resource could not be saved</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Student Academic Resource Library</h1>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadResourceModal">
        <i class="fa-solid fa-upload me-1" aria-hidden="true"></i> Upload Resource
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="visually-hidden">Student resources, visibility, and download counts</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">File</th>
                        <th scope="col">Downloads</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resources as $r)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $r->title }}</td>
                            <td><span class="badge bg-info-lt text-info">{{ $r->category }}</span></td>
                            <td>{{ strtoupper(pathinfo($r->file_path, PATHINFO_EXTENSION)) }} • {{ $r->humanFileSize() }}</td>
                            <td class="fw-bold text-primary">{{ $r->download_count }}</td>
                            <td>
                                <span class="badge {{ $r->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $r->is_active ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.resources.toggle', $r->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light border me-1" aria-label="{{ $r->is_active ? 'Hide' : 'Show' }} {{ $r->title }} {{ $r->is_active ? 'from' : 'in' }} the member portal">
                                        <i class="fa-solid {{ $r->is_active ? 'fa-eye-slash' : 'fa-eye' }}" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.resources.destroy', $r->id) }}" method="POST" class="d-inline" id="delete-resource-{{ $r->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger resource-delete-button" data-form-id="delete-resource-{{ $r->id }}" data-confirm-message="Remove {{ $r->title }} from the student resource library?" aria-label="Delete {{ $r->title }}">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No resources uploaded yet. Upload guides, prospectuses, and question banks for members.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $resources->links() }}
        </div>
    </div>
</div>

<!-- Upload Resource Modal -->
<div class="modal fade" id="uploadResourceModal" tabindex="-1" aria-labelledby="uploadResourceModalTitle" data-reopen-on-error="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="h5 fw-bold" id="uploadResourceModalTitle">Upload Student Resource</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close resource upload form"></button>
            </div>
            <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data" id="uploadResourceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="resourceTitle" class="form-label extra-small fw-bold">Title</label>
                        <input id="resourceTitle" type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="255" placeholder="e.g. PU Exam Calendar (2026-2027)" required>
                    </div>
                    <div class="mb-3">
                        <label for="resourceCategory" class="form-label extra-small fw-bold">Category</label>
                        <select id="resourceCategory" name="category" class="form-select" required>
                            @foreach(\App\Models\StudentResource::CATEGORIES as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="resourceFile" class="form-label extra-small fw-bold">File</label>
                        <input id="resourceFile" type="file" name="resourceFile" class="form-control" accept=".pdf,.zip,.doc,.docx,.ppt,.pptx,.xls,.xlsx" aria-describedby="resourceFileHelp" required>
                        <div id="resourceFileHelp" class="form-text extra-small">PDF, ZIP, Office documents. Max size 20MB.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow">Upload to Library</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
