@extends('layouts.admin')

@section('title', 'Pages CMS | KSO CMS')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-lines me-2"></i> Custom Dynamic Web Pages</h5>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Build New Page</a>
        </div>
        <form action="{{ route('admin.pages.index') }}" method="GET" class="row g-2 mt-2">
            <div class="col-md-6">
                <label class="visually-hidden" for="page-search">Search pages</label>
                <input id="page-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title, slug, or summary">
            </div>
            <div class="col-md-3">
                <label class="visually-hidden" for="page-status">Filter pages by status</label>
                <select id="page-status" name="status" class="form-select form-select-sm">
                    @foreach(['all' => 'All statuses', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>

    <form action="{{ route('admin.pages.bulk') }}" method="POST" id="bulk-pages-form">
        @csrf
        <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
            <label for="bulk-page-action" class="small fw-bold">Selected pages:</label>
            <select name="action" id="bulk-page-action" class="form-select form-select-sm" style="max-width: 190px" required>
                <option value="">Choose action</option>
                <option value="publish">Publish</option>
                <option value="draft">Move to draft</option>
                <option value="review">Send to review</option>
                <option value="delete">Delete</option>
            </select>
            <button class="btn btn-sm btn-outline-primary">Apply</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 extra-small">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all-pages" aria-label="Select all pages"></th>
                            <th>Page Title</th>
                            <th>Slug / Route</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Scheduled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $page->id }}" class="page-selection" aria-label="Select {{ $page->title }}"></td>
                                <td class="fw-bold text-dark">{{ $page->title }}</td>
                                <td><code>/page/{{ $page->slug }}</code></td>
                                <td>{{ $page->view_count }}</td>
                                <td><span class="badge {{ $page->publication_status === 'published' ? 'bg-success' : ($page->publication_status === 'scheduled' ? 'bg-info text-dark' : 'bg-secondary') }}">{{ ucfirst($page->publication_status) }}</span></td>
                                <td>{{ $page->scheduled_publish_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('admin.pages.preview', $page->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" aria-label="Preview {{ $page->title }}"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-outline-secondary" aria-label="Edit {{ $page->title }}"><i class="fa-solid fa-pen"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">No pages match your search.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $pages->links() }}</div>
        </div>
    </form>
</div>
<script>
document.getElementById('select-all-pages')?.addEventListener('change', event => {
    document.querySelectorAll('.page-selection').forEach(input => input.checked = event.target.checked);
});
document.getElementById('bulk-pages-form')?.addEventListener('submit', event => {
    if (document.getElementById('bulk-page-action').value === 'delete' && !confirm('Delete the selected pages and their revision histories?')) {
        event.preventDefault();
    }
});
</script>
@endsection
