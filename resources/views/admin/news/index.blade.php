@extends('layouts.admin')

@section('title', 'News & Announcements CMS | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-newspaper me-2"></i> All Published Notices</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Author</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($news as $n)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $n->title }}</td>
                                    <td><span class="badge bg-danger">{{ $n->category }}</span></td>
                                    <td>{{ $n->date ? $n->date->format('Y-m-d') : '' }}</td>
                                    <td>{{ $n->author }}</td>
                                    <td>
                                        <form action="{{ route('admin.news.destroy', $n->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete announcement?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Post Announcement</h5>
            <form action="{{ route('admin.news.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Headline Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Notice">Notice</option>
                        <option value="Welfare">Welfare</option>
                        <option value="Academic">Academic</option>
                        <option value="Press Release">Press Release</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Content</label>
                    <textarea name="content" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Author</label>
                    <input type="text" name="author" class="form-control" value="Executive Desk">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Publish Notice</button>
            </form>
        </div>
    </div>
</div>

@endsection
