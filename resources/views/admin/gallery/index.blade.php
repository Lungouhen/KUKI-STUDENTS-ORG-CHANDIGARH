@extends('layouts.admin')

@section('title', 'Gallery CMS | KSO CMS')

@section('content')
<div class="admin-gallery-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="galleryErrorsHeading">
            <h2 id="galleryErrorsHeading" class="h6 fw-bold">Review the gallery image details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <section class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                        <h2 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-images me-2" aria-hidden="true"></i>Gallery Photos</h2>
                        <form action="{{ route('admin.gallery.index') }}" method="GET" class="d-flex gap-2">
                            <label class="visually-hidden" for="gallery-search">Search gallery</label>
                            <input id="gallery-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search gallery">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @forelse($gallery as $item)
                            <div class="col-md-3 col-6">
                                <article class="admin-gallery-card card border p-2 position-relative text-center h-100">
                                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->mediaAsset?->alt_text ?: ($item->caption ?: $item->title) }}" class="img-fluid rounded mb-2" loading="lazy" decoding="async">
                                    <h3 class="h6 fw-bold text-truncate mb-1" title="{{ $item->title }}">{{ $item->title }}</h3>
                                    <span class="badge bg-light text-dark extra-small align-self-center">{{ $item->category }}</span>
                                    @if($item->caption)
                                        <p class="small text-muted mb-2">{{ $item->caption }}</p>
                                    @endif
                                    <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" class="mt-auto pt-2" data-gallery-delete>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" aria-label="Delete gallery image: {{ $item->title }}"><i class="fa-solid fa-trash me-1" aria-hidden="true"></i>Delete</button>
                                    </form>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border mb-0 text-center text-muted">No gallery photos match your search.</div>
                            </div>
                        @endforelse
                    </div>
                    @if($gallery->hasPages())
                        <div class="mt-3">{{ $gallery->links() }}</div>
                    @endif
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <section class="card border-0 shadow-sm rounded-4 p-4">
                <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-upload me-2" aria-hidden="true"></i>Upload Gallery Image</h2>
                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" data-gallery-form>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryTitle">Image Title</label>
                        <input type="text" id="galleryTitle" name="title" class="form-control" value="{{ old('title') }}" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryCategory">Category</label>
                        <input type="text" id="galleryCategory" name="category" class="form-control" value="{{ old('category') }}" maxlength="120" placeholder="e.g. Cultural / Sports / Freshers" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryAsset">Reuse an image from the media library</label>
                        <select id="galleryAsset" name="image_asset_path" class="form-select" data-gallery-asset>
                            <option value="">Upload a new image instead</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->path }}" @selected(old('image_asset_path') === $asset->path)>{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="small">Open media library</a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryImageFile">Select a new image file</label>
                        <input type="file" id="galleryImageFile" name="imageFile" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/avif,image/bmp" data-gallery-file>
                        <div class="form-text">Optional upload, up to 5 MB.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryAltText">Alternative text for a new upload</label>
                        <input type="text" id="galleryAltText" name="alt_text" class="form-control" value="{{ old('alt_text') }}" maxlength="255" data-gallery-alt>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="galleryCaption">Caption (optional)</label>
                        <input type="text" id="galleryCaption" name="caption" class="form-control" value="{{ old('caption') }}" maxlength="500">
                    </div>
                    <p class="small text-danger" data-gallery-selection-error role="alert" hidden>Choose an existing media image or select a new image file.</p>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Upload Photo</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
