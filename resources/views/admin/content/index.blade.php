@extends('layouts.admin')

@section('title', $title . ' | KSO Admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h4 class="fw-bold text-dark mb-0">{{ $title }}</h4>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addContentModal">
        <i class="fa-solid fa-plus me-1"></i> Add New
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white">
        <form action="{{ route('admin.content.index') }}" method="GET" class="row g-2">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="col-md-5"><label class="visually-hidden" for="content-search">Search content</label><input id="content-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title or description"></div>
            <div class="col-md-3">
                <label class="visually-hidden" for="content-status">Filter publication status</label>
                <select id="content-status" name="status" class="form-select form-select-sm">
                    @foreach(['all' => 'All statuses', 'published' => 'Published', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2"><button class="btn btn-sm btn-outline-primary">Filter</button><a href="{{ route('admin.content.index', ['type' => $type]) }}" class="btn btn-sm btn-outline-secondary">Clear</a></div>
        </form>
    </div>

    <form action="{{ route('admin.content.bulk') }}" method="POST" id="bulk-content-form">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
            <label for="bulk-content-action" class="small fw-bold">Selected items:</label>
            <select name="action" id="bulk-content-action" class="form-select form-select-sm" style="max-width: 190px" required>
                <option value="">Choose action</option>
                <option value="publish">Publish</option>
                <option value="unpublish">Unpublish</option>
                <option value="review">Send to review</option>
                <option value="scheduled">Schedule</option>
                <option value="reorder">Save order</option>
                <option value="delete">Delete</option>
            </select>
            <input type="datetime-local" name="scheduled_publish_at" class="form-control form-control-sm" style="max-width:210px" aria-label="Scheduled publish time">
            <button class="btn btn-sm btn-outline-primary">Apply</button>
            <span class="small text-muted">Set display order below and select those rows to reorder.</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted extra-small text-uppercase">
                        <tr>
                            <th><input type="checkbox" id="select-all-content" aria-label="Select all content"></th>
                            <th class="ps-3">@if(in_array($type, ['slider', 'certificate'])) Image @else Title @endif</th>
                            @if($type != 'slider' && $type != 'certificate') <th>Summary</th> @endif
                            <th>Display order</th>
                            <th>Published</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contents as $content)
                            <tr class="extra-small">
                                <td><input type="checkbox" name="ids[]" value="{{ $content->id }}" class="content-selection" aria-label="Select {{ $content->title }}"></td>
                                <td class="ps-3">
                                    @if($content->image)
                                        <img src="{{ asset($content->image) }}" alt="{{ $content->mediaAsset?->alt_text ?? '' }}" class="rounded border" width="60" height="40" style="object-fit:cover;">
                                    @endif
                                    <span class="fw-bold text-dark ms-2">{{ $content->title }}</span>
                                </td>
                                @if($type != 'slider' && $type != 'certificate')
                                    <td>{{ Str::limit($content->content, 50) }}</td>
                                @endif
                                <td><input type="number" name="display_orders[{{ $content->id }}]" value="{{ $content->display_order }}" min="0" class="form-control form-control-sm" style="width:90px" aria-label="Display order for {{ $content->title }}"></td>
                                <td>
                                    <span class="badge {{ $content->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($content->publication_status) }}</span>
                                    @if($content->scheduled_publish_at)<small class="d-block">{{ $content->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                </td>
                                <td class="text-end pe-4"><span class="text-muted">Use bulk actions</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No items match this search.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $contents->links() }}</div>
        </div>
    </form>
</div>

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
                    <div class="mb-3"><label class="form-label extra-small fw-bold">Title / Heading</label><input type="text" name="title" class="form-control" required></div>
                    @if($type != 'slider')
                        <div class="mb-3"><label class="form-label extra-small fw-bold">Description / Content</label><textarea name="content" class="form-control" rows="3"></textarea></div>
                    @endif
                    <div class="mb-3"><label class="form-label extra-small fw-bold">Link (Optional)</label><input type="text" name="link" class="form-control" placeholder="https://..."></div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Reuse a media asset</label>
                        <select name="image_asset_path" class="form-select">
                            <option value="">No image / upload instead</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->path }}">{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="small">Open media library</a>
                    </div>
                    <div class="mb-3"><label class="form-label extra-small fw-bold">Or upload image</label><input type="file" name="imageFile" class="form-control" accept="image/*"></div>
                    <div class="mb-3"><label class="form-label extra-small fw-bold">Alternative text for uploaded image</label><input type="text" name="alt_text" class="form-control" maxlength="255"></div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Editorial status</label>
                        <select name="publication_status" class="form-select">
                            @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label extra-small fw-bold">Publish at (scheduled only)</label><input type="datetime-local" name="scheduled_publish_at" class="form-control"></div>
                </div>
                <div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 fw-bold shadow">Save Content</button></div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('select-all-content')?.addEventListener('change', event => {
    document.querySelectorAll('.content-selection').forEach(input => input.checked = event.target.checked);
});
document.getElementById('bulk-content-form')?.addEventListener('submit', event => {
    if (document.getElementById('bulk-content-action').value === 'delete' && !confirm('Delete the selected content items?')) {
        event.preventDefault();
    }
});
</script>
@endsection
