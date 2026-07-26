@extends('layouts.admin')

@section('title', 'Membership Management | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-id-card me-2"></i> All Registered Student Members</h5>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.members.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/ID/college..." value="{{ $search }}">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending Only</option>
                    <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved Only</option>
                    <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected Only</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>
            <a href="{{ route('admin.members.exportCsv') }}" class="btn btn-success btn-sm text-nowrap fw-bold"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Member ID</th>
                        <th>Full Name</th>
                        <th>Institution</th>
                        <th>Course & Year</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                        <tr>
                            <td><img src="{{ asset($m->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover;" onerror="this.src='/images/default-avatar-m.png'"></td>
                            <td class="fw-bold text-primary">{{ $m->id }}</td>
                            <td class="fw-bold text-dark">{{ $m->full_name }}</td>
                            <td>{{ $m->institution }}</td>
                            <td>{{ $m->course }} ({{ $m->year_of_study }})</td>
                            <td>{{ $m->phone }}</td>
                            <td><span class="badge {{ $m->status === 'Approved' ? 'bg-success' : ($m->status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ $m->status }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($m->status === 'Pending')
                                        <form action="{{ route('admin.members.updateStatus', $m->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Approved">
                                            <button class="btn btn-success" title="Approve"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin.members.updateStatus', $m->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Rejected">
                                            <button class="btn btn-warning text-dark" title="Reject"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('membership.idCard', $m->id) }}" target="_blank" class="btn btn-outline-primary" title="View Card"><i class="fa-solid fa-id-card"></i></a>
                                    <form action="{{ route('admin.members.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection
