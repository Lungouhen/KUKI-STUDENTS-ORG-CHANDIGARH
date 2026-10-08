@extends('layouts.admin')

@section('title', 'Media Library | KSO Admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div><h4 class="fw-bold text-dark mb-1">Media Library</h4><p class="text-muted mb-0">Reusable images, accessible alt text, and unused-file cleanup.</p></div>
    <form action="{{ route('admin.media.index') }}" method="GET" class="d-flex gap-2">
        <label class="visually-hidden" for="media-search">Search images</label>
        <input id="media-search" name="q" value="{{ $search }}" class="form-control" placeholder="Search file name or alt text">
        <select name="usage" class="form-select" aria-label="Filter assets by use">
            @foreach(['all' => 'All assets', 'used' => 'In use', 'unused' => 'Unused'] as $value => $label)
                <option value="{{ $value }}" @selected($usage === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary">Search</button>
    </form>
</div>

@if($errors->has('media'))
    <div class="alert alert-danger">{{ $errors->first('media') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-5"><label class="form-label fw-bold">Upload image</label><input type="file" name="imageFile" class="form-control" accept="image/*" required></div>
        <div class="col-md-5"><label class="form-label fw-bold">Alternative text</label><input type="text" name="alt_text" class="form-control" maxlength="255" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Add image</button></div>
    </form>
</div>

<div class="row g-3">
    @forelse($assets as $asset)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="{{ asset($asset->path) }}" alt="{{ $asset->alt_text }}" class="w-100" style="height:180px;object-fit:cover" loading="lazy">
                <div class="card-body">
                    <div class="d-flex justify-content-between gap-2">
                        <strong class="text-truncate" title="{{ $asset->original_name }}">{{ $asset->original_name }}</strong>
                        <span class="badge {{ $asset->is_used ? 'bg-success' : 'bg-secondary' }}">{{ $asset->is_used ? 'In use' : 'Unused' }}</span>
                    </div>
                    <small class="text-muted">{{ number_format($asset->size / 1024, 1) }} KB</small>
                    <form action="{{ route('admin.media.update', $asset->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PUT')
                        <label class="form-label small">Alternative text</label>
                        <input type="text" name="alt_text" value="{{ $asset->alt_text }}" class="form-control form-control-sm" maxlength="255" required>
                        <button class="btn btn-sm btn-outline-primary mt-2">Save alt text</button>
                    </form>
                    @if(!$asset->is_used)
                        <form action="{{ route('admin.media.destroy', $asset->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Permanently delete this unused image?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete unused image</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light">No images match these filters.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $assets->links() }}</div>
@endsection
