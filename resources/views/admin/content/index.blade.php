@extends('layouts.admin')

@section('title', $title . ' | KSO Admin')

@section('content')
<div class="admin-content-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="contentErrorsHeading">
            <h2 id="contentErrorsHeading" class="h6 fw-bold">Review the content details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="h4 fw-bold text-dark mb-0">{{ $title }}</h1>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addContentModal">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add New
        </button>
    </div>

    <section class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white">
            <form action="{{ route('admin.content.index') }}" method="GET" class="row g-2">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="col-md-5">
                    <label class="visually-hidden" for="content-search">Search content</label>
                    <input id="content-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title or description">
                </div>
                <div class="col-md-3">
                    <label class="visually-hidden" for="content-status">Filter publication status</label>
                    <select id="content-status" name="status" class="form-select form-select-sm">
                        @foreach(['all' => 'All statuses', 'published' => 'Published', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('admin.content.index', ['type' => $type]) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
            <form action="{{ route('admin.content.bulk') }}" method="POST" id="bulk-content-form" class="admin-content-bulk">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <label for="bulk-content-action" class="small fw-bold">Selected items:</label>
                <select name="action" id="bulk-content-action" class="form-select form-select-sm" required data-content-action>
                    <option value="">Choose action</option>
                    <option value="publish">Publish</option>
                    <option value="unpublish">Unpublish</option>
                    <option value="review">Send to review</option>
                    <option value="scheduled">Schedule</option>
                    <option value="reorder">Save order</option>
                    <option value="delete">Delete</option>
                </select>
                <label class="visually-hidden" for="contentBulkSchedule">Scheduled publish time</label>
                <input id="contentBulkSchedule" type="datetime-local" name="scheduled_publish_at" class="form-control form-control-sm" aria-describedby="contentBulkScheduleHint" data-content-schedule>
                <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                <span id="contentBulkScheduleHint" class="visually-hidden">Required only when scheduling selected content.</span>
                <span class="small text-muted">Set order below and select rows to reorder.</span>
            </form>
            <p class="w-100 small text-danger mb-0" data-content-selection-error role="alert" hidden>Select at least one content item before applying a bulk action.</p>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" aria-describedby="contentTableCaption">
                    <caption id="contentTableCaption" class="visually-hidden">{{ $title }} matching the selected filters</caption>
                    <thead class="bg-light text-muted extra-small text-uppercase">
                        <tr>
                            <th scope="col"><input type="checkbox" id="select-all-content" aria-label="Select all content on this page" form="bulk-content-form"></th>
                            <th scope="col" class="ps-3">@if(in_array($type, ['slider', 'certificate'])) Image @else Title @endif</th>
                            @if(!in_array($type, ['slider', 'certificate']))<th scope="col">Summary</th>@endif
                            <th scope="col">Display order</th>
                            <th scope="col">Publication status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contents as $content)
                            <tr class="extra-small">
                                <td><input type="checkbox" name="ids[]" value="{{ $content->id }}" class="content-selection" aria-label="Select {{ $content->title }}" form="bulk-content-form"></td>
                                <td class="ps-3">
                                    @if($content->image)
                                        <img src="{{ asset($content->image) }}" alt="{{ $content->mediaAsset?->alt_text ?? '' }}" class="admin-content-thumbnail rounded border" loading="lazy" decoding="async">
                                    @endif
                                    <span class="fw-bold text-dark ms-2">{{ $content->title }}</span>
                                </td>
                                @if(!in_array($type, ['slider', 'certificate']))
                                    <td>{{ Str::limit($content->content, 50) }}</td>
                                @endif
                                <td>
                                    <label class="visually-hidden" for="content-order-{{ $content->id }}">Display order for {{ $content->title }}</label>
                                    <input type="number" id="content-order-{{ $content->id }}" name="display_orders[{{ $content->id }}]" value="{{ $content->display_order }}" min="0" class="form-control form-control-sm admin-content-order" aria-label="Display order for {{ $content->title }}" form="bulk-content-form">
                                </td>
                                <td>
                                    <span class="badge {{ $content->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($content->publication_status) }}</span>
                                    @if($content->scheduled_publish_at)<small class="d-block">{{ $content->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ in_array($type, ['slider', 'certificate']) ? 4 : 5 }}" class="text-center py-5 text-muted">No items match this search.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($contents->hasPages())
                <div class="p-3">{{ $contents->links() }}</div>
            @endif
        </div>
    </section>

    <div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalTitle" data-reopen-on-error="{{ $errors->any() && old('type') === $type ? 'true' : 'false' }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h2 class="h5 fw-bold" id="addContentModalTitle">Add to {{ $title }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close content form"></button>
                </div>
                <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data" data-content-create-form>
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentTitle">Title / Heading</label>
                            <input type="text" id="contentTitle" name="title" value="{{ old('title') }}" class="form-control" maxlength="255" required>
                        </div>
                        @if($type !== 'slider')
                            <div class="mb-3">
                                <label class="form-label extra-small fw-bold" for="contentDescription">Description / Content</label>
                                <textarea id="contentDescription" name="content" class="form-control" rows="4">{{ old('content') }}</textarea>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentLink">Link (optional)</label>
                            <input type="text" id="contentLink" name="link" value="{{ old('link') }}" class="form-control" maxlength="2048" placeholder="https://...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentMediaAsset">Reuse a media asset</label>
                            <select id="contentMediaAsset" name="image_asset_path" class="form-select" data-content-asset>
                                <option value="">No image / upload instead</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->path }}" @selected(old('image_asset_path') === $asset->path)>{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="small">Open media library</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentImageFile">Or upload image</label>
                            <input type="file" id="contentImageFile" name="imageFile" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/avif,image/bmp" data-content-file>
                        </div>
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentAltText">Alternative text for uploaded image</label>
                            <input type="text" id="contentAltText" name="alt_text" value="{{ old('alt_text') }}" class="form-control" maxlength="255" data-content-alt>
                        </div>
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentPublicationStatus">Editorial status</label>
                            <select id="contentPublicationStatus" name="publication_status" class="form-select" data-content-status>
                                @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('publication_status', 'published') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold" for="contentPublishAt">Publish at (scheduled only)</label>
                            <input type="datetime-local" id="contentPublishAt" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="form-control" data-content-schedule>
                        </div>
                    </div>
                    <div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 fw-bold shadow">Save Content</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
