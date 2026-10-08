@extends('layouts.admin')

@section('title', 'SEO Pro | KSO Admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold text-dark">SEO Pro</h4>
    <p class="text-muted mb-0">Manage search and social-sharing defaults for public pages. Page-specific title and description fields override these defaults.</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.seo.update') }}">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <label for="seo-title" class="form-label fw-bold">Default SEO title</label>
                    <input id="seo-title" type="text" name="seoTitle" class="form-control" maxlength="255" value="{{ old('seoTitle', $settings['seoTitle']) }}" placeholder="Falls back to the organization name">
                    @error('seoTitle') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label for="seo-description" class="form-label fw-bold">Default meta description</label>
                    <textarea id="seo-description" name="seoDescription" class="form-control" rows="3" maxlength="500" placeholder="Falls back to the organization tagline">{{ old('seoDescription', $settings['seoDescription']) }}</textarea>
                    <div class="form-text">Used on public pages without a page-specific description.</div>
                    @error('seoDescription') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label for="seo-social-image" class="form-label fw-bold">Default social sharing image</label>
                    <select id="seo-social-image" name="seoSocialImage" class="form-select">
                        <option value="">No default image</option>
                        @foreach($images as $image)
                            <option value="{{ $image->path }}" @selected(old('seoSocialImage', $settings['seoSocialImage']) === $image->path)>{{ $image->original_name }}{{ $image->alt_text ? ' — '.$image->alt_text : '' }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Choose an uploaded image from the Media Library.</div>
                    @error('seoSocialImage') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="hidden" name="seoIndexingEnabled" value="0">
                        <input id="seo-indexing-enabled" type="checkbox" name="seoIndexingEnabled" value="1" class="form-check-input" @checked(old('seoIndexingEnabled', $settings['seoIndexingEnabled']))>
                        <label for="seo-indexing-enabled" class="form-check-label fw-bold">Allow search engine indexing</label>
                    </div>
                    <div class="form-text">When disabled, pages use noindex metadata and the sitemap is omitted; crawling remains allowed so search engines can see the noindex directive.</div>
                    @error('seoIndexingEnabled') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div class="small text-muted">
                        <a href="{{ route('robots') }}" target="_blank" rel="noopener">robots.txt</a>
                        <span class="mx-1">·</span>
                        <a href="{{ route('sitemap') }}" target="_blank" rel="noopener">sitemap.xml</a>
                    </div>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save SEO settings</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
