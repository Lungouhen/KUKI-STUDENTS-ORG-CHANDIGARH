@extends('layouts.admin')

@section('title', 'Membership Fee Records | KSO Admin')

@section('content')

<div class="admin-member-fees-page">
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="feeErrorsHeading">
        <h2 id="feeErrorsHeading" class="h6 fw-bold">The fee payment could not be recorded</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0 d-flex justify-content-between align-items-center">
        <h2 class="h5 fw-bold mb-0 text-success"><i class="fa-solid fa-money-bill-transfer me-2" aria-hidden="true"></i> Membership Fee Tracking</h2>
        <span class="badge bg-primary rounded-pill px-3 py-2 extra-small">Current Period: {{ $currentPeriod }}</span>
    </div>
    <div class="card-body p-0">
        @if($accounts->isEmpty())
            <div class="alert alert-warning m-3 small mb-0">No active financial accounts found. <a href="{{ route('admin.financial.index') }}">Create an account</a> before recording fee payments.</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <caption class="visually-hidden">Membership fee payments and balances for {{ $currentPeriod }}</caption>
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="ps-4">Member ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Fee ({{ $currentPeriod }})</th>
                        <th scope="col">Last Payment</th>
                        <th scope="col">Total Paid</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        @php
                            $currentPayment = $m->feePayments->firstWhere('period', $currentPeriod);
                            $lastPayment = $m->feePayments->first();
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold">{{ $m->id }}</td>
                            <td>{{ $m->full_name }}</td>
                            <td>{{ $m->membership_category }}</td>
                            <td>
                                @if($currentPayment)
                                    <span class="badge bg-success">Paid ₹{{ number_format($currentPayment->amount, 2) }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Due</span>
                                @endif
                            </td>
                            <td>
                                @if($lastPayment)
                                    {{ $lastPayment->period }} • {{ $lastPayment->paid_on?->format('d M Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">₹{{ number_format($m->feePayments->sum('amount'), 2) }}</td>
                            <td class="text-end pe-4">
                                <button type="button"
                                        class="btn btn-sm btn-primary rounded-pill px-3 record-fee-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#recordFeeModal"
                                        data-action="{{ route('admin.members.recordFee', $m->id) }}"
                                        data-member="{{ $m->full_name }} ({{ $m->id }})"
                                        aria-label="Record membership fee payment for {{ $m->full_name }}"
                                        @if($accounts->isEmpty()) disabled @endif>
                                    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Record Payment
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No approved members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Record Fee Payment Modal -->
<div class="modal fade" id="recordFeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="h5 fw-bold" id="recordFeeModalTitle">Record Fee Payment</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close fee payment form"></button>
            </div>
            <form method="POST" id="recordFeeForm">
                @csrf
                <div class="modal-body">
                    <p class="extra-small text-muted mb-3" aria-live="polite">Member: <strong id="recordFeeMember"></strong></p>
                    <div class="mb-3">
                        <label for="feeFinancialAccount" class="form-label extra-small fw-bold">Deposit Account</label>
                        <select id="feeFinancialAccount" name="financial_account_id" class="form-select border shadow-none" required>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" @selected(old('financial_account_id') == $account->id)>{{ $account->account_name }} ({{ $account->account_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="feePeriod" class="form-label extra-small fw-bold">Membership Period</label>
                            <input id="feePeriod" type="text" name="period" class="form-control border shadow-none" value="{{ old('period', $currentPeriod) }}" pattern="\d{4}-\d{2}" required>
                        </div>
                        <div class="col-6">
                            <label for="feeAmount" class="form-label extra-small fw-bold">Amount (₹)</label>
                            <input id="feeAmount" type="number" name="amount" step="0.01" min="0.01" class="form-control border shadow-none" value="{{ old('amount', '250.00') }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-6">
                            <label for="feePaymentMethod" class="form-label extra-small fw-bold">Payment Method</label>
                            <select id="feePaymentMethod" name="payment_method" class="form-select border shadow-none" required>
                                @foreach(['Cash', 'UPI', 'Bank Transfer', 'Cheque'] as $method)
                                    <option value="{{ $method }}" @selected(old('payment_method', 'Cash') === $method)>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="feePaidOn" class="form-label extra-small fw-bold">Paid On</label>
                            <input id="feePaidOn" type="date" name="paid_on" class="form-control border shadow-none" value="{{ old('paid_on', now()->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="feeReference" class="form-label extra-small fw-bold">Reference No. <span class="text-muted">(optional)</span></label>
                        <input id="feeReference" type="text" name="reference_no" class="form-control border shadow-none" value="{{ old('reference_no') }}" placeholder="UPI / cheque reference">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fa-solid fa-save me-1" aria-hidden="true"></i> Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
