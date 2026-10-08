@extends('layouts.admin')

@section('title', 'Edit Announcement - ' . $news->title)

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

    <section class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit Notice / Announcement</h1>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>Back to List</a>
        </div>

        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" data-news-form>
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold" for="newsTitle">Headline Title</label>
                    <input type="text" id="newsTitle" name="title" class="form-control" value="{{ old('title', $news->title) }}" maxlength="255" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="newsCategory">Category</label>
                    <select id="newsCategory" name="category" class="form-select" required>
                        @if(!in_array(old('category', $news->category), ['Notice', 'Welfare', 'Academic', 'Press Release'], true))
                            <option value="{{ old('category', $news->category) }}" selected>{{ old('category', $news->category) }}</option>
                        @endif
                        @foreach(['Notice', 'Welfare', 'Academic', 'Press Release'] as $category)
                            <option value="{{ $category }}" @selected(old('category', $news->category) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="newsContent">Content</label>
                    <textarea id="newsContent" name="content" class="form-control" rows="6" maxlength="50000" required>{{ old('content', $news->content) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="newsAuthor">Author</label>
                    <input type="text" id="newsAuthor" name="author" class="form-control" value="{{ old('author', $news->author) }}" maxlength="255">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="news-publication-status">Editorial status</label>
                    <select id="news-publication-status" name="publication_status" class="form-select" data-news-schedule-status>
                        @foreach(['published' => 'Published', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('publication_status', $news->publication_status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="newsPublishAt">Publish at (scheduled only)</label>
                    <input type="datetime-local" id="newsPublishAt" name="scheduled_publish_at" value="{{ old('scheduled_publish_at', $news->scheduled_publish_at?->format('Y-m-d\TH:i')) }}" class="form-control" data-news-schedule-time>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i>Update Announcement</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
