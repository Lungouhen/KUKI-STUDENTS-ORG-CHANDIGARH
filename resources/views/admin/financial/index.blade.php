@extends('layouts.admin')

@section('title', 'Financial Management & General Ledger | KSO CMS')

@section('content')

<section class="finance-workspace">
    <header class="finance-page-heading">
        <div>
            <span class="finance-eyebrow">KSO · Finance</span>
            <h1>Financial overview</h1>
            <p>Track income, expenses, accounts, and voucher entries in one place.</p>
        </div>
        <div class="finance-term-chip">
            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
            <span>{{ $term?->name ?? 'All time' }}</span>
        </div>
    </header>

    <div class="row g-3 mb-4 finance-summary-grid">
        <div class="col-6 col-sm-6 col-xl-3">
            <article class="finance-summary-card finance-summary-card--balance h-100">
                <span class="finance-summary-icon"><i class="fa-solid fa-landmark" aria-hidden="true"></i></span>
                <div>
                    <span class="finance-card-label">{{ $term ? 'Net Term Balance' : 'Net Balance' }}</span>
                    <strong>₹{{ number_format($netBalance, 2) }}</strong>
                    <small>{{ $term ? 'Current executive term' : 'Across all recorded periods' }}</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-6 col-xl-3">
            <article class="finance-summary-card h-100">
                <span class="finance-summary-icon finance-summary-icon--income"><i class="fa-solid fa-hand-holding-dollar" aria-hidden="true"></i></span>
                <div>
                    <span class="finance-card-label">{{ $term ? 'Term Income' : 'All-time Income' }}</span>
                    <strong class="text-success">₹{{ number_format($totalIncome, 2) }}</strong>
                    <small>Recorded incoming funds</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-6 col-xl-3">
            <article class="finance-summary-card h-100">
                <span class="finance-summary-icon finance-summary-icon--expense"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                <div>
                    <span class="finance-card-label">{{ $term ? 'Term Expenses' : 'All-time Expenses' }}</span>
                    <strong class="text-danger">₹{{ number_format($totalExpense, 2) }}</strong>
                    <small>Recorded outgoing funds</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-6 col-xl-3">
            <article class="finance-summary-card h-100">
                <span class="finance-summary-icon finance-summary-icon--accounts"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                <div>
                    <span class="finance-card-label">Active accounts</span>
                    <strong>{{ $activeAccounts->count() }}</strong>
                    <small>Across the organization ledger</small>
                </div>
            </article>
        </div>
    </div>

    <section class="finance-accounts-section mb-4" aria-labelledby="finance-accounts-heading">
        <div class="finance-section-heading">
            <div>
                <span class="finance-eyebrow">Your ledgers</span>
                <h2 id="finance-accounts-heading">Accounts</h2>
            </div>
            <span class="text-muted small">{{ $activeAccounts->count() }} active</span>
        </div>
        @if($activeAccounts->isNotEmpty())
            <div class="row g-3">
                @foreach($activeAccounts as $account)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="finance-account-card h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <span class="finance-account-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                                <span class="finance-account-code">{{ $account->account_code }}</span>
                            </div>
                            <h3>{{ $account->account_name }}</h3>
                            <span class="finance-account-type">{{ $account->account_type }}</span>
                            <strong class="finance-account-balance">₹{{ number_format((float) $account->current_balance, 2) }}</strong>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="finance-empty-state">No active ledger accounts are available.</div>
        @endif
    </section>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h2 class="h5 fw-bold text-primary mb-1">Transaction report</h2>
                <p class="text-muted small mb-0">Filter the ledger by date, account, and transaction type. Active-term totals above are separate.</p>
            </div>
            <a href="{{ route('admin.financial.export', $filters) }}" class="btn btn-outline-success btn-sm">
                <i class="fa-solid fa-file-csv me-1"></i> Export filtered CSV
            </a>
        </div>
        <form action="{{ route('admin.financial.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="report-from" class="form-label small fw-bold">From date</label>
                <input id="report-from" type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="report-to" class="form-label small fw-bold">To date</label>
                <input id="report-to" type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label for="report-account" class="form-label small fw-bold">Account</label>
                <select id="report-account" name="account_id" class="form-select">
                    <option value="">All accounts</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ (string) ($filters['account_id'] ?? '') === (string) $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label for="report-type" class="form-label small fw-bold">Type</label>
                <select id="report-type" name="type" class="form-select">
                    <option value="">All types</option>
                    @foreach(['Income', 'Expense', 'Transfer'] as $reportType)
                        <option value="{{ $reportType }}" {{ ($filters['type'] ?? '') === $reportType ? 'selected' : '' }}>
                            {{ $reportType }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Apply</button>
                <a href="{{ route('admin.financial.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
        <div class="row g-2 mt-3">
            @foreach(['Income' => 'success', 'Expense' => 'danger', 'Transfer' => 'primary'] as $summaryType => $color)
                @php($summary = $reportSummary->get($summaryType))
                <div class="col-12 col-sm-4">
                    <div class="finance-report-summary rounded border p-2 h-100">
                        <span class="small text-muted">{{ $summaryType }} · {{ $summary?->transaction_count ?? 0 }} entries</span>
                        <div class="fw-bold text-{{ $color }}">₹{{ number_format((float) ($summary?->total_amount ?? 0), 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Transaction Ledger -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                <h2 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-file-invoice-dollar me-2" aria-hidden="true"></i> Recent transactions</h2>
                <span class="badge bg-light text-dark">{{ $transactions->total() }} matching vouchers</span>
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
                            @forelse($transactions as $t)
                                <tr>
                                    <td data-label="Voucher" class="fw-bold text-primary">{{ $t->voucher_no }}</td>
                                    <td data-label="Account">
                                        {{ $t->account->account_name ?? 'General' }}
                                        @if($t->targetAccount)
                                            <span class="text-muted">→ {{ $t->targetAccount->account_name }}</span>
                                        @endif
                                    </td>
                                    <td data-label="Category"><span class="badge bg-light text-dark">{{ $t->category }}</span></td>
                                    <td data-label="Type"><span class="badge {{ $t->type === 'Income' ? 'bg-success' : ($t->type === 'Transfer' ? 'bg-primary' : 'bg-danger') }}">{{ $t->type }}</span></td>
                                    <td data-label="Amount" class="fw-bold {{ $t->type === 'Income' ? 'text-success' : ($t->type === 'Transfer' ? 'text-primary' : 'text-danger') }}">₹{{ number_format($t->amount, 2) }}</td>
                                    <td data-label="Payee / Payer">{{ $t->payer_payee_name ?? '-' }}</td>
                                    <td data-label="Date">{{ $t->transaction_date ? $t->transaction_date->format('Y-m-d') : '' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-5">No vouchers match these filters.</td></tr>
                            @endforelse
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
            <span class="finance-eyebrow">New entry</span>
            <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2" aria-hidden="true"></i> Record voucher</h2>
            <form action="{{ route('admin.financial.storeTransaction') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold" for="financial-account">Source Ledger Account</label>
                    <select id="financial-account" name="financial_account_id" class="form-select" required>
                        @foreach($activeAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_name }} ({{ $acc->account_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="target-account">Destination Account (required for transfers)</label>
                    <select id="target-account" name="target_account_id" class="form-select">
                        <option value="">Not a transfer</option>
                        @foreach($activeAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_name }} ({{ $acc->account_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="transaction-type">Type</label>
                    <select id="transaction-type" name="type" class="form-select" required>
                        <option value="Income">Income (+)</option>
                        <option value="Expense">Expense (-)</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="transaction-category">Category</label>
                    <select id="transaction-category" name="category" class="form-select" required>
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
                    <label class="form-label fw-bold" for="transaction-amount">Amount (₹)</label>
                    <input id="transaction-amount" type="number" step="0.01" min="0.01" max="9999999999.99" name="amount" class="form-control fw-bold text-primary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="transaction-date">Transaction date</label>
                    <input id="transaction-date" type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="payment-method">Payment method</label>
                    <select id="payment-method" name="payment_method" class="form-select" required>
                        <option value="UPI">UPI</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="payer-payee">Payer / payee name</label>
                    <input id="payer-payee" type="text" name="payer_payee_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="transaction-narration">Narration / details</label>
                    <textarea id="transaction-narration" name="narration" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="voucher-attachment">Voucher attachment / bill</label>
                    <input id="voucher-attachment" type="file" name="attachmentFile" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Transaction Voucher</button>
            </form>
        </div>
    </div>
</div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var type = document.getElementById('transaction-type');
        var target = document.getElementById('target-account');

        function updateTransferTarget() {
            target.required = type.value === 'Transfer';
            if (!target.required) target.value = '';
        }

        type.addEventListener('change', updateTransferTarget);
        updateTransferTarget();
    });
</script>
@endpush

@endsection
