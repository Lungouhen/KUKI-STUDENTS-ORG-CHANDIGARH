@extends('layouts.admin')

@section('title', 'Membership Fee Records | KSO Admin')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0">
        <h5 class="fw-bold mb-0 text-success"><i class="fa-solid fa-money-bill-transfer me-2"></i> Membership Fee Tracking</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Member ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Annual Fee (2025-26)</th>
                        <th>Payment Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $m->id }}</td>
                            <td>{{ $m->full_name }}</td>
                            <td>{{ $m->membership_category }}</td>
                            <td>{{ $m->status }}</td>
                            <td>₹250.00</td>
                            <td>
                                <span class="badge bg-success">Paid</span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border"><i class="fa-solid fa-file-invoice"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
