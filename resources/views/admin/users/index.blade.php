@extends('layouts.admin')

@section('title', 'Admin User Management | KSO Admin')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0 d-flex justify-content-between">
        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-user-shield me-2"></i> System Administrators</h5>
        <button class="btn btn-primary btn-sm rounded-pill"><i class="fa-solid fa-plus me-1"></i> New Admin</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge bg-primary text-white text-uppercase">{{ $u->role ?? 'Admin' }}</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
