@extends('layouts.admin')

@section('title', 'News & Announcements CMS | KSO CMS')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-newspaper me-2"></i> News & Announcements</h5>
                <form action="{{ route('admin.news.index') }}" method="GET" class="row g-2">
                    <div class="col-md-6">
                        <label for="news-search" class="visually-hidden">Search announcements</label>
                        <input id="news-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title, category, or author">
                    </div>
                    <div class="col-md-4">
                        <label for="news-status" class="visually-hidden">Filter editorial status</label>
                        <select id="news-status" name="status" class="form-select form-select-sm">
                            @foreach(['all' => 'All statuses', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><button class="btn btn-sm btn-outline-primary w-100">Filter</button></div>
                </form>
            </div>
            <form action="{{ route('admin.news.bulk') }}" method="POST">
                @csrf
                <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                    <label for="news-bulk-status" class="small fw-bold">Selected:</label>
                    <select id="news-bulk-status" name="publication_status" class="form-select form-select-sm" style="max-width:150px" required>
                        <option value="">Set status</option>
                        @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="datetime-local" name="scheduled_publish_at" class="form-control form-control-sm" style="max-width:210px" aria-label="Scheduled publish time">
                    <button class="btn btn-sm btn-outline-primary">Apply</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="select-all-news" aria-label="Select all announcements"></th>
                                    <th>Title</th><th>Category</th><th>Date</th><th>Author</th><th>Editorial status</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news as $item)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="news-selection" aria-label="Select {{ $item->title }}"></td>
                                        <td class="fw-bold text-dark">{{ $item->title }} @if($item->is_member_post)<span class="badge bg-info-lt text-info ms-1">Member Post</span>@endif</td>
                                        <td><span class="badge bg-danger">{{ $item->category }}</span></td>
                                        <td>{{ $item->date?->format('Y-m-d') }}</td>
                                        <td>{{ $item->author }}</td>
                                        <td>
                                            <span class="badge {{ $item->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($item->publication_status) }}</span>
                                            @if($item->scheduled_publish_at)<small class="d-block">{{ $item->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                        </td>
                                        <td><a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit post"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No announcements match these filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $news->links() }}</div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Create Announcement</h5>
            <form action="{{ route('admin.news.store') }}" method="POST">
                @csrf
                <div class="mb-3"><label class="form-label fw-bold">Headline Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Notice">Notice</option><option value="Welfare">Welfare</option><option value="Academic">Academic</option><option value="Press Release">Press Release</option>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Content</label><textarea name="content" class="form-control" rows="4" required></textarea></div>
                <div class="mb-3"><label class="form-label fw-bold">Author</label><input type="text" name="author" class="form-control" value="Executive Desk"></div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="news-publication-status">Editorial status</label>
                    <select id="news-publication-status" name="publication_status" class="form-select">
                        @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Publish at (scheduled only)</label><input type="datetime-local" name="scheduled_publish_at" class="form-control"></div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Announcement</button>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('select-all-news')?.addEventListener('change', event => {
    document.querySelectorAll('.news-selection').forEach(input => input.checked = event.target.checked);
});
</script>
@endsection
