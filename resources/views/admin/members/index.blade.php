@extends('layouts.admin')

@section('title', 'Membership Management | KSO CMS')

@section('content')

<div class="admin-members-page">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h2 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-id-card me-2" aria-hidden="true"></i> All Registered Student Members</h2>
        <div class="admin-members-controls d-flex gap-2">
            <form action="{{ route('admin.members.index') }}" method="GET" class="admin-members-filters d-flex gap-2" id="adminMemberFilters">
                <label class="visually-hidden" for="admin-member-search">Search members</label>
                <input type="search" id="admin-member-search" name="search" class="form-control form-control-sm" placeholder="Search name/ID/college..." value="{{ $search }}" autocomplete="off">
                <label class="visually-hidden" for="admin-member-status">Filter members by status</label>
                <select id="admin-member-status" name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending Only</option>
                    <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved Only</option>
                    <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected Only</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>
            <a href="{{ route('admin.members.exportCsv') }}" class="btn btn-success btn-sm text-nowrap fw-bold"><i class="fa-solid fa-file-csv me-1" aria-hidden="true"></i> Export CSV</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <caption class="visually-hidden">Registered members and available management actions</caption>
                <thead class="table-light">
                    <tr>
                        <th scope="col">Photo</th>
                        <th scope="col">Member ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Institution</th>
                        <th scope="col">Course & Year</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                        <tr>
                            <td><img src="{{ asset($m->photo) }}" alt="Member photo for {{ $m->full_name }}" class="admin-member-photo rounded-circle" width="36" height="36" loading="lazy" onerror="this.src='/images/default-avatar-m.png'"></td>
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
                                            <button type="submit" class="btn btn-success" aria-label="Approve member {{ $m->full_name }}" title="Approve"><i class="fa-solid fa-check" aria-hidden="true"></i></button>
                                        </form>
                                        <form action="{{ route('admin.members.updateStatus', $m->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="btn btn-warning text-dark" aria-label="Reject member {{ $m->full_name }}" title="Reject"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('membership.idCard', $m->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary" aria-label="View ID card for {{ $m->full_name }}" title="View Card"><i class="fa-solid fa-id-card" aria-hidden="true"></i></a>
                                    <form action="{{ route('admin.members.destroy', $m->id) }}" method="POST" class="d-inline" data-member-delete data-confirm-message="Delete member {{ $m->full_name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" aria-label="Delete member {{ $m->full_name }}" title="Delete"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
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

</div>
@endsection
