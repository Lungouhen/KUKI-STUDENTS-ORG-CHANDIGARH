@extends('layouts.admin')

@section('title', 'News & Announcements CMS | KSO CMS')

@section('content')
<div class="admin-news-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="newsErrorsHeading">
            <h2 id="newsErrorsHeading" class="h6 fw-bold">Review the announcement details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3">
                    <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-newspaper me-2" aria-hidden="true"></i>News &amp; Announcements</h2>
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
                        <div class="col-md-2"><button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button></div>
                    </form>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                    <form action="{{ route('admin.news.bulk') }}" method="POST" id="newsBulkForm" class="d-flex flex-wrap align-items-center gap-2">
                        @csrf
                        <label for="news-bulk-status" class="small fw-bold">Selected announcements:</label>
                        <select id="news-bulk-status" name="publication_status" class="form-select form-select-sm" required data-news-schedule-status>
                            <option value="">Set status</option>
                            @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('publication_status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="visually-hidden" for="news-bulk-publish-at">Scheduled publish time</label>
                        <input id="news-bulk-publish-at" type="datetime-local" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="form-control form-control-sm" aria-describedby="newsBulkScheduleHint" data-news-schedule-time>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                    </form>
                    <span id="newsBulkScheduleHint" class="visually-hidden">Required only when setting selected announcements to scheduled.</span>
                    <p class="w-100 small text-danger mb-0" data-news-bulk-error role="alert" hidden>Select at least one announcement before applying a bulk status.</p>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small" aria-describedby="newsTableCaption">
                            <caption id="newsTableCaption" class="visually-hidden">Announcements matching the selected search and editorial filters</caption>
                            <thead class="table-light">
                                <tr>
                                    <th scope="col"><input type="checkbox" id="select-all-news" aria-label="Select all announcements on this page" form="newsBulkForm"></th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Editorial status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news as $item)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="news-selection" aria-label="Select {{ $item->title }}" form="newsBulkForm"></td>
                                        <td class="fw-bold text-dark">{{ $item->title }} @if($item->is_member_post)<span class="badge bg-info-lt text-info ms-1">Member Post</span>@endif</td>
                                        <td><span class="badge bg-danger">{{ $item->category }}</span></td>
                                        <td>{{ $item->date?->format('Y-m-d') }}</td>
                                        <td>{{ $item->author }}</td>
                                        <td>
                                            <span class="badge {{ $item->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($item->publication_status) }}</span>
                                            @if($item->scheduled_publish_at)<small class="d-block">{{ $item->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                        </td>
                                        <td><a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit announcement: {{ $item->title }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No announcements match these filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $news->links() }}</div>
                </div>
            </section>
        </div>

        <div class="col-lg-5">
            <section class="card border-0 shadow-sm rounded-4 p-4">
                <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2" aria-hidden="true"></i>Create Announcement</h2>
                <form action="{{ route('admin.news.store') }}" method="POST" data-news-form>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="newsTitle">Headline Title</label>
                        <input type="text" id="newsTitle" name="title" class="form-control" value="{{ old('title') }}" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="newsCategory">Category</label>
                        <select id="newsCategory" name="category" class="form-select" required>
                            @foreach(['Notice', 'Welfare', 'Academic', 'Press Release'] as $category)
                                <option value="{{ $category }}" @selected(old('category', 'Notice') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="newsContent">Content</label>
                        <textarea id="newsContent" name="content" class="form-control" rows="4" maxlength="50000" required>{{ old('content') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="newsAuthor">Author</label>
                        <input type="text" id="newsAuthor" name="author" class="form-control" value="{{ old('author', 'Executive Desk') }}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="news-publication-status">Editorial status</label>
                        <select id="news-publication-status" name="publication_status" class="form-select" data-news-schedule-status>
                            @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('publication_status', 'published') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="newsPublishAt">Publish at (scheduled only)</label>
                        <input type="datetime-local" id="newsPublishAt" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="form-control" data-news-schedule-time>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Announcement</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
