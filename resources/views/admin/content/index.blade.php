@extends('layouts.admin')

@section('title', $title . ' | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">{{ $title }}</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addContentModal">
        <i class="fa-solid fa-plus me-1"></i> Add New
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">@if($type == 'slider' || $type == 'certificate') Image @else Title @endif</th>
                        @if($type != 'slider' && $type != 'certificate') <th>Summary</th> @endif
                        <th>Published</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $c)
                        <tr class="extra-small">
                            <td class="ps-4">
                                @if($c->image)
                                    <img src="{{ asset($c->image) }}" class="rounded border" width="60" height="40" style="object-fit: cover;">
                                @endif
                                <span class="fw-bold text-dark ms-2">{{ $c->title }}</span>
                            </td>
                            @if($type != 'slider' && $type != 'certificate')
                                <td>{{ Str::limit($c->content, 50) }}</td>
                            @endif
                            <td>
                                <span class="badge {{ $c->is_published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $c->is_published ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.content.destroy', $c->id) }}" method="POST" class="d-inline" id="delete-content-{{ $c->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger" onclick="confirmDelete('delete-content-{{ $c->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">No items found for this category.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Add to {{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Title / Heading</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    @if($type != 'slider')
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold">Description / Content</label>
                            <textarea name="content" class="form-control" rows="3"></textarea>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Link (Optional)</label>
                        <input type="text" name="link" class="form-control" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Image Asset</label>
                        <input type="file" name="imageFile" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow">Save Content</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
