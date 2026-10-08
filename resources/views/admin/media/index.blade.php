@extends('layouts.admin')

@section('title', 'Media Library | KSO Admin')

@section('content')
<div class="admin-media-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Media Library</h1>
            <p class="text-muted mb-0">Reusable images, accessible alternative text, and unused-file cleanup.</p>
        </div>
        <form action="{{ route('admin.media.index') }}" method="GET" class="admin-media-search d-flex gap-2">
            <label class="visually-hidden" for="media-search">Search images</label>
            <input id="media-search" name="q" value="{{ $search }}" class="form-control" placeholder="Search file name or alt text">
            <label class="visually-hidden" for="media-usage">Filter assets by use</label>
            <select id="media-usage" name="usage" class="form-select">
                @foreach(['all' => 'All assets', 'used' => 'In use', 'unused' => 'Unused'] as $value => $label)
                    <option value="{{ $value }}" @selected($usage === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-primary">Search</button>
        </form>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4" role="alert" aria-labelledby="mediaErrorsHeading">
            <h2 id="mediaErrorsHeading" class="h6 fw-bold">Review the media details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card border-0 shadow-sm rounded-4 p-3 mb-4">
        <h2 class="h5 fw-bold text-primary mb-3">Upload image</h2>
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end" data-media-form>
            @csrf
            <div class="col-md-5">
                <label class="form-label fw-bold" for="mediaUploadFile">Image file</label>
                <input type="file" id="mediaUploadFile" name="imageFile" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/avif,image/bmp" required>
                <div class="form-text">Supported image files, up to 5 MB.</div>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold" for="mediaUploadAlt">Alternative text</label>
                <input type="text" id="mediaUploadAlt" name="alt_text" value="{{ old('alt_text') }}" class="form-control" maxlength="255" required>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Add image</button></div>
        </form>
    </section>

    <div class="row g-3">
        @forelse($assets as $asset)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <article class="card admin-media-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="{{ asset($asset->path) }}" alt="{{ $asset->alt_text }}" class="admin-media-preview w-100" loading="lazy" decoding="async">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <h2 class="h6 fw-bold text-truncate mb-1" title="{{ $asset->original_name }}">{{ $asset->original_name }}</h2>
                            <span class="badge {{ $asset->is_used ? 'bg-success' : 'bg-secondary' }}">{{ $asset->is_used ? 'In use' : 'Unused' }}</span>
                        </div>
                        <small class="text-muted">{{ number_format($asset->size / 1024, 1) }} KB</small>
                        <form action="{{ route('admin.media.update', $asset->id) }}" method="POST" class="mt-3" data-media-form>
                            @csrf
                            @method('PUT')
                            <label class="form-label small" for="media-alt-{{ $asset->id }}">Alternative text</label>
                            <input type="text" id="media-alt-{{ $asset->id }}" name="alt_text" value="{{ $asset->alt_text }}" class="form-control form-control-sm" maxlength="255" required>
                            <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Save alt text</button>
                        </form>
                        @if(!$asset->is_used)
                            <form action="{{ route('admin.media.destroy', $asset->id) }}" method="POST" class="mt-2" data-media-delete>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete unused image</button>
                            </form>
                        @endif
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-light border mb-0">No images match these filters.</div></div>
        @endforelse
    </div>
    @if($assets->hasPages())
        <div class="mt-3">{{ $assets->links() }}</div>
    @endif
</div>
@endsection
