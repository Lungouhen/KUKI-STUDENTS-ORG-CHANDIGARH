@extends('layouts.admin')

@section('title', 'Financial Management & General Ledger | KSO CMS')

@section('content')

<!-- Financial Overview Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-success">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Income Collections</small>
            <h3 class="fw-black text-success mb-0">₹{{ number_format($totalIncome, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-danger">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Expenses & Disbursemnts</small>
            <h3 class="fw-black text-danger mb-0">₹{{ number_format($totalExpense, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
            <small class="text-muted text-uppercase fw-bold extra-small">Net Reserve Balance</small>
            <h3 class="fw-black text-primary mb-0">₹{{ number_format($netBalance, 2) }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Transaction Ledger -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Financial Transaction Vouchers</h5>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.financial.index') }}" class="btn {{ !$type ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    <a href="{{ route('admin.financial.index', ['type' => 'Income']) }}" class="btn {{ $type == 'Income' ? 'btn-success' : 'btn-outline-success' }}">Income</a>
                    <a href="{{ route('admin.financial.index', ['type' => 'Expense']) }}" class="btn {{ $type == 'Expense' ? 'btn-danger' : 'btn-outline-danger' }}">Expense</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Voucher #</th>
                                <th>Account</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Payee / Payer</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $t->voucher_no }}</td>
                                    <td>{{ $t->account->account_name ?? 'General' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $t->category }}</span></td>
                                    <td><span class="badge {{ $t->type === 'Income' ? 'bg-success' : 'bg-danger' }}">{{ $t->type }}</span></td>
                                    <td class="fw-bold {{ $t->type === 'Income' ? 'text-success' : 'text-danger' }}">₹{{ number_format($t->amount, 2) }}</td>
                                    <td>{{ $t->payer_payee_name ?? '-' }}</td>
                                    <td>{{ $t->transaction_date ? $t->transaction_date->format('Y-m-d') : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Voucher Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Record Voucher Entry</h5>
            <form action="{{ route('admin.financial.storeTransaction') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Ledger Account</label>
                    <select name="financial_account_id" class="form-select" required>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_name }} ({{ $acc->account_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Income">Income (+)</option>
                        <option value="Expense">Expense (-)</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Donation">Donation</option>
                        <option value="Membership Fee">Membership Fee</option>
                        <option value="Medical Relief Grant">Medical Relief Grant</option>
                        <option value="Cultural Event">Cultural Event Expense</option>
                        <option value="Hostel Assistance">Hostel Assistance Expense</option>
                        <option value="Stationery/Printing">Stationery/Printing</option>
                        <option value="Misc">Miscellaneous</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-control fw-bold text-primary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Transaction Date</label>
                    <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="UPI">UPI</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Payer / Payee Name</label>
                    <input type="text" name="payer_payee_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Narration / Details</label>
                    <textarea name="narration" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Voucher Attachment / Bill</label>
                    <input type="file" name="attachmentFile" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Transaction Voucher</button>
            </form>
        </div>
    </div>
</div>

@endsection
