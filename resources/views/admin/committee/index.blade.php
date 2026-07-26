@extends('layouts.admin')

@section('title', 'Executive Body Directory | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-users-gear me-2"></i> Executive Council Members</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Institution</th>
                                <th>Phone</th>
                                <th>Tenure</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($committee as $c)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $c->name }}</td>
                                    <td><span class="badge bg-teal text-white" style="background:#0d9488;">{{ $c->designation }}</span></td>
                                    <td>{{ $c->institution }}</td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->tenure }}</td>
                                    <td>
                                        <form action="{{ route('admin.committee.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove leader?')">
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
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-user-plus me-2"></i> Add Executive Member</h5>
            <form action="{{ route('admin.committee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Designation</label>
                    <input type="text" name="designation" class="form-control" placeholder="e.g. President / Gen Sec" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Institution</label>
                    <input type="text" name="institution" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tenure</label>
                    <input type="text" name="tenure" class="form-control" value="2025 - 2026">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Photo</label>
                    <input type="file" name="photoFile" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Leader</button>
            </form>
        </div>
    </div>
</div>

@endsection
