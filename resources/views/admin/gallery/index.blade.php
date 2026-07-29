@extends('layouts.admin')

@section('title', 'Gallery CMS')
@section('subtitle', 'Upload and organise photos shown in the public gallery')

@push('styles')
    @include('partials.vendor-styles', ['libs' => ['lightgallery']])
@endpush

@section('content')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="fw-bold text-primary mb-0">
                    <i class="fa-solid fa-images me-2"></i> Gallery Photos
                    <span class="badge bg-light text-muted ms-1" data-filter-count>{{ $gallery->count() }}</span>
                </h5>
                <div class="position-relative" style="max-width: 240px;">
                    <input type="search" class="form-control form-control-sm ps-4"
                           placeholder="Filter photos…"
                           data-filter-input data-filter-target=".js-photo">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted"
                       style="left:.6rem; top:50%; transform:translateY(-50%); font-size:.7rem;"></i>
                </div>
            </div>

            <div class="card-body p-3">
                @if($gallery->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-images fs-1 d-block mb-3 opacity-50"></i>
                        <p class="mb-0">No photos uploaded yet. Use the form to add your first image.</p>
                    </div>
                @else
                    {{-- data-lightgallery lets an admin review uploads full-size
                         without leaving the CMS. --}}
                    <div class="row g-3" data-lightgallery>
                        @foreach($gallery as $g)
                            <div class="col-md-3 col-6 js-photo"
                                 data-filter-text="{{ $g->title }} {{ $g->category }}">
                                <div class="card border p-2 position-relative text-center h-100">
                                    <a href="{{ asset($g->image_url) }}" data-lg-item
                                       data-sub-html="<h4>{{ e($g->title) }}</h4><p>{{ e($g->category) }}</p>">
                                        <img src="{{ asset($g->image_url) }}"
                                             class="img-fluid rounded mb-2"
                                             style="height:120px; width:100%; object-fit:cover;"
                                             alt="{{ $g->title }}"
                                             onerror="this.src='{{ asset('images/gallery-1.jpg') }}'">
                                    </a>
                                    <small class="fw-bold text-truncate d-block">{{ $g->title }}</small>
                                    <span class="badge bg-light text-dark extra-small">{{ $g->category }}</span>

                                    <form action="{{ route('admin.gallery.destroy', $g->id) }}" method="POST"
                                          class="position-absolute top-0 end-0 m-1"
                                          data-confirm="Delete “{{ $g->title }}”? This cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm py-0 px-1" aria-label="Delete photo">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-muted text-center py-4 mb-0" data-filter-empty style="display:none;">
                        No photos match that search.
                    </p>
                @endif

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
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <input type="text" name="category" class="form-control" list="galleryCategories"
                           value="{{ old('category') }}" placeholder="e.g. Cultural / Sports / Freshers" required>
                    <datalist id="galleryCategories">
                        @foreach($gallery->pluck('category')->filter()->unique() as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Image File</label>
                    <input type="file" name="imageFile" class="form-control" accept="image/*"
                           data-image-preview="#galleryPreview" required>
                    <div class="form-text extra-small">JPG or PNG, up to 5 MB.</div>
                </div>

                {{-- Populated by kso-admin.js as soon as a file is chosen. --}}
                <img id="galleryPreview" src="" alt="Preview"
                     class="img-fluid rounded border mb-3"
                     style="display:none; max-height:170px; width:100%; object-fit:cover;">

                <button type="submit" class="btn btn-primary w-100 fw-bold">Upload Photo</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @include('partials.vendor-scripts', ['libs' => ['lightgallery']])
@endpush
