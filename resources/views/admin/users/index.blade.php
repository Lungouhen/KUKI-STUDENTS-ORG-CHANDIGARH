@extends('layouts.admin')

@section('title', 'Admin User Management | KSO Admin')

@section('content')

<div class="admin-user-directory-page">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="h4 fw-bold mb-1 text-dark"><i class="fa-solid fa-user-shield me-2" aria-hidden="true"></i> System Users</h1>
            <p class="small text-muted mb-0">Review account roles and administrator access.</p>
        </div>
        <div class="admin-user-search">
            <label class="visually-hidden" for="adminUserSearch">Filter users by name, email, or role</label>
            <input id="adminUserSearch" type="search" class="form-control" placeholder="Filter users">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <caption class="visually-hidden">System user accounts and administrator access</caption>
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="ps-4">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Admin Access</th>
                    </tr>
                </thead>
                <tbody id="adminUserRows">
                    @forelse($users as $u)
                        <tr data-admin-user-row>
                            <td class="ps-4 fw-bold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge bg-primary text-white text-uppercase">{{ $u->is_admin ? 'Administrator' : ($u->role ?? 'User') }}</span></td>
                            <td><span class="badge {{ $u->is_admin ? 'bg-success' : 'bg-secondary' }}">{{ $u->is_admin ? 'Enabled' : 'Not assigned' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No user accounts found.</td></tr>
                    @endforelse
                    @if($users->isNotEmpty())
                        <tr id="adminUserNoResults" hidden><td colspan="4" class="text-center text-muted py-4">No users match that filter.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <p id="adminUserSearchStatus" class="visually-hidden" role="status" aria-live="polite"></p>
    </div>
</div>

</div>
@endsection
