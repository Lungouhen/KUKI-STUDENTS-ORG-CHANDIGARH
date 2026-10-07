@extends('layouts.admin')

@section('title', 'Student Resource Library | KSO Admin')

@section('content')

@if($errors->any())
    <div class="alert alert-danger rounded-4 small">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Student Academic Resource Library</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadResourceModal">
        <i class="fa-solid fa-upload me-1"></i> Upload Resource
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Category</th>
                        <th>File</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
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
                                    <button type="submit" class="btn btn-sm btn-light border me-1" title="{{ $r->is_active ? 'Hide from portal' : 'Show in portal' }}">
                                        <i class="fa-solid {{ $r->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.resources.destroy', $r->id) }}" method="POST" class="d-inline" id="delete-resource-{{ $r->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger" onclick="confirmDelete('delete-resource-{{ $r->id }}')">
                                        <i class="fa-solid fa-trash"></i>
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
<div class="modal fade" id="uploadResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Upload Student Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. PU Exam Calendar (2026-2027)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Category</label>
                        <select name="category" class="form-select" required>
                            @foreach(\App\Models\StudentResource::CATEGORIES as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label extra-small fw-bold">File</label>
                        <input type="file" name="resourceFile" class="form-control" accept=".pdf,.zip,.doc,.docx,.ppt,.pptx,.xls,.xlsx" required>
                        <div class="form-text extra-small">PDF, ZIP, Office documents. Max size 20MB.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow">Upload to Library</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
