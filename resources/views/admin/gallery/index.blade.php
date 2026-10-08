@extends('layouts.admin')

@section('title', 'Gallery CMS | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-images me-2"></i> Gallery Photos</h5>
                    <form action="{{ route('admin.gallery.index') }}" method="GET" class="d-flex gap-2">
                        <label class="visually-hidden" for="gallery-search">Search gallery</label>
                        <input id="gallery-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search gallery">
                        <button class="btn btn-sm btn-outline-primary">Search</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    @foreach($gallery as $g)
                        <div class="col-md-3 col-6">
                            <div class="card border p-2 position-relative text-center">
                                <img src="{{ asset($g->image_url) }}" alt="{{ $g->mediaAsset?->alt_text ?: ($g->caption ?: $g->title) }}" class="img-fluid rounded mb-2" style="height:120px; object-fit:cover;" onerror="this.src='/images/gallery-1.jpg'">
                                <small class="fw-bold text-truncate d-block">{{ $g->title }}</small>
                                <span class="badge bg-light text-dark extra-small">{{ $g->category }}</span>
                                <form action="{{ route('admin.gallery.destroy', $g->id) }}" method="POST" class="position-absolute top-0 end-0 m-1" onsubmit="return confirm('Delete photo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm py-0 px-1"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $gallery->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-upload me-2"></i> Upload Gallery Image</h5>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Image Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Cultural / Sports / Freshers" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Image File</label>
                    <label class="form-label fw-bold">Reuse an image</label>
                    <select name="image_asset_path" class="form-select">
                        <option value="">Upload a new image instead</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->path }}">{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="small">Open media library</a>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select new image file</label>
                    <input type="file" name="imageFile" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alternative text for new upload</label>
                    <input type="text" name="alt_text" class="form-control" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Caption (optional)</label>
                    <input type="text" name="caption" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Upload Photo</button>
            </form>
        </div>
    </div>
</div>

@endsection
