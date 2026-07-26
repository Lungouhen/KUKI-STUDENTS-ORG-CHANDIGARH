@extends('layouts.admin')

@section('title', 'Pages CMS | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-lines me-2"></i> Custom Dynamic Web Pages</h5>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Build New Page</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Page Title</th>
                        <th>Slug / Route</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $p)
                        <tr>
                            <td class="fw-bold text-dark">{{ $p->title }}</td>
                            <td><code>/page/{{ $p->slug }}</code></td>
                            <td>{{ $p->view_count }}</td>
                            <td><span class="badge {{ $p->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $p->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td>
                                <a href="{{ route('page.show', $p->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Preview"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('admin.pages.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete page?')">
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
        <div class="p-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>

@endsection
