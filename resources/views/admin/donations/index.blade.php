@extends('layouts.admin')

@section('title', 'Donations Records | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-hand-holding-dollar me-2"></i> Donation Transactions Summary</h5>
        <div class="badge bg-success fs-6 px-3 py-2">Total Collected: ₹{{ number_format($totalAmount) }}</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Donor Name</th>
                        <th>Amount</th>
                        <th>Welfare Cause</th>
                        <th>Contact</th>
                        <th>Payment / UPI Ref</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $d)
                        <tr>
                            <td class="fw-bold text-dark">{{ $d->donor_name }}</td>
                            <td class="fw-bold text-success fs-6">₹{{ number_format($d->amount) }}</td>
                            <td><span class="badge bg-info-lt text-primary">{{ $d->cause }}</span></td>
                            <td>{{ $d->phone ?? '' }} {{ $d->email ? "({$d->email})" : '' }}</td>
                            <td><code>{{ $d->payment_ref }}</code></td>
                            <td>{{ $d->date ? $d->date->format('Y-m-d') : '' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.donations.receipt', $d->id) }}" target="_blank" class="btn btn-sm btn-light border">
                                    <i class="fa-solid fa-print me-1"></i> Receipt
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $donations->links() }}
        </div>
    </div>
</div>

@endsection
