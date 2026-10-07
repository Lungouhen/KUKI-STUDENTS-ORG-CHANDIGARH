@extends('layouts.admin')

@section('title', 'Membership Fee Records | KSO Admin')

@section('content')

@if($errors->any())
    <div class="alert alert-danger rounded-4 small">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-success"><i class="fa-solid fa-money-bill-transfer me-2"></i> Membership Fee Tracking</h5>
        <span class="badge bg-primary rounded-pill px-3 py-2 extra-small">Current Period: {{ $currentPeriod }}</span>
    </div>
    <div class="card-body p-0">
        @if($accounts->isEmpty())
            <div class="alert alert-warning m-3 small mb-0">No active financial accounts found. <a href="{{ route('admin.financial.index') }}">Create an account</a> before recording fee payments.</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Member ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Fee ({{ $currentPeriod }})</th>
                        <th>Last Payment</th>
                        <th>Total Paid</th>
                        <th class="text-end pe-4">Actions</th>
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
                                        @if($accounts->isEmpty()) disabled @endif>
                                    <i class="fa-solid fa-plus me-1"></i> Record Payment
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
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Record Fee Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="recordFeeForm">
                @csrf
                <div class="modal-body">
                    <p class="extra-small text-muted mb-3">Member: <strong id="recordFeeMember"></strong></p>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Deposit Account</label>
                        <select name="financial_account_id" class="form-select border shadow-none" required>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_name }} ({{ $account->account_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Membership Period</label>
                            <input type="text" name="period" class="form-control border shadow-none" value="{{ $currentPeriod }}" pattern="\d{4}-\d{2}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Amount (₹)</label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-control border shadow-none" value="250.00" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-select border shadow-none" required>
                                <option>Cash</option>
                                <option>UPI</option>
                                <option>Bank Transfer</option>
                                <option>Cheque</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Paid On</label>
                            <input type="date" name="paid_on" class="form-control border shadow-none" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label extra-small fw-bold">Reference No. <span class="text-muted">(optional)</span></label>
                        <input type="text" name="reference_no" class="form-control border shadow-none" placeholder="UPI / cheque reference">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fa-solid fa-save me-1"></i> Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('recordFeeModal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('recordFeeForm').setAttribute('action', button.getAttribute('data-action'));
            document.getElementById('recordFeeMember').textContent = button.getAttribute('data-member');
        });
    });
</script>
@endpush
