@extends('layouts.admin')

@section('title', 'Donations Records | KSO CMS')

@section('content')

<div class="admin-donations-page">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h1 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-hand-holding-dollar me-2" aria-hidden="true"></i> Donation Transactions Summary</h1>
        <div class="badge bg-success fs-6 px-3 py-2">Total Collected: ₹{{ number_format($totalAmount, 2) }}</div>
    </div>
    <div class="card-body p-0">
        <div class="admin-donation-search px-3 pt-3">
            <label class="visually-hidden" for="donationSearch">Filter donations by donor, cause, contact, reference, or status</label>
            <input id="donationSearch" type="search" class="form-control" placeholder="Filter donations">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <caption class="visually-hidden">Donation records and payment statuses</caption>
                <thead class="table-light">
                    <tr>
                        <th scope="col">Donor Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Welfare Cause</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Payment / UPI Ref</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $d)
                        <tr data-donation-row>
                            <td class="fw-bold text-dark">{{ $d->donor_name }}</td>
                            <td class="fw-bold text-success fs-6">₹{{ number_format($d->amount) }}</td>
                            <td><span class="badge bg-info-lt text-primary">{{ $d->cause }}</span></td>
                            <td>{{ $d->phone ?? '' }} {{ $d->email ? "({$d->email})" : '' }}</td>
                            <td><code>{{ $d->payment_ref }}</code></td>
                            <td>{{ $d->date ? $d->date->format('Y-m-d') : '' }}</td>
                            <td><span class="badge {{ ['Completed' => 'bg-success', 'Pending' => 'bg-warning text-dark', 'Failed' => 'bg-danger'][$d->status] ?? 'bg-secondary' }}">{{ $d->status }}</span></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.donations.receipt', $d->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light border" aria-label="Open receipt for {{ $d->donor_name }}">
                                    <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No donations have been recorded.</td></tr>
                    @endforelse
                    @if($donations->isNotEmpty())
                        <tr id="donationNoResults" hidden><td colspan="8" class="text-center text-muted py-4">No donations match that filter.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <p id="donationSearchStatus" class="visually-hidden" role="status" aria-live="polite"></p>
        <div class="p-3">
            {{ $donations->links() }}
        </div>
    </div>
</div>

</div>
@endsection
