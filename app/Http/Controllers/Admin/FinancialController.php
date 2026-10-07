<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\AuditLog;
use App\Models\Term;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $accounts = FinancialAccount::all();
        $activeAccounts = $accounts->where('is_active', true);
        $filters = $this->reportFilters($request);
        $query = $this->filteredTransactions($filters);
        $transactions = (clone $query)->with(['account', 'targetAccount'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
        $reportSummary = (clone $query)
            ->selectRaw('type, COUNT(*) as transaction_count, SUM(amount) as total_amount')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $term = Term::current();
        $summaryQuery = Transaction::query();

        if ($term) {
            $summaryQuery->whereBetween('transaction_date', [$term->start_date, $term->end_date]);
        }

        $totalIncome = (clone $summaryQuery)->where('type', 'Income')->sum('amount');
        $totalExpense = (clone $summaryQuery)->where('type', 'Expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('admin.financial.index', [
            'accounts' => $accounts,
            'activeAccounts' => $activeAccounts,
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'type' => $filters['type'] ?? null,
            'filters' => $filters,
            'reportSummary' => $reportSummary,
            'term' => $term,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->reportFilters($request);
        $transactions = $this->filteredTransactions($filters)
            ->with(['account', 'targetAccount'])
            ->orderBy('transaction_date')
            ->orderBy('id');

        return response()->streamDownload(function () use ($transactions) {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'Voucher',
                'Date',
                'Type',
                'Category',
                'Account',
                'Destination Account',
                'Amount',
                'Payment Method',
                'Reference',
                'Payer / Payee',
                'Narration',
            ]);

            $transactions->chunk(500, function ($batch) use ($output) {
                foreach ($batch as $transaction) {
                    fputcsv($output, [
                        $this->safeCsvText($transaction->voucher_no),
                        $transaction->transaction_date?->format('Y-m-d'),
                        $this->safeCsvText($transaction->type),
                        $this->safeCsvText($transaction->category),
                        $this->safeCsvText($transaction->account?->account_name ?? ''),
                        $this->safeCsvText($transaction->targetAccount?->account_name ?? ''),
                        $transaction->amount,
                        $this->safeCsvText($transaction->payment_method),
                        $this->safeCsvText($transaction->reference_no ?? ''),
                        $this->safeCsvText($transaction->payer_payee_name ?? ''),
                        $this->safeCsvText($transaction->narration),
                    ]);
                }
            });

            fclose($output);
        }, 'financial-transactions-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function print(Request $request): StreamedResponse
    {
        $filters = $this->reportFilters($request);
        $query = $this->filteredTransactions($filters);
        $reportSummary = (clone $query)
            ->selectRaw('type, COUNT(*) as transaction_count, SUM(amount) as total_amount')
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        $account = isset($filters['account_id'])
            ? FinancialAccount::find($filters['account_id'])
            : null;

        return response()->stream(function () use ($filters, $query, $reportSummary, $account) {
            echo view('admin.financial.print-header', [
                'filters' => $filters,
                'reportSummary' => $reportSummary,
                'account' => $account,
                'generatedAt' => now(),
                'transactionCount' => (clone $query)->count(),
            ])->render();

            $query->with(['account', 'targetAccount'])
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->chunk(500, function ($transactions) {
                    foreach ($transactions as $transaction) {
                        echo view('admin.financial.print-row', ['transaction' => $transaction])->render();
                    }
                });

            echo view('admin.financial.print-footer')->render();
        }, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="financial-report-' . now()->format('Y-m-d') . '.html"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function reportFilters(Request $request): array
    {
        $validated = $request->validate([
            'type' => 'sometimes|nullable|in:Income,Expense,Transfer',
            'account_id' => 'sometimes|nullable|integer|exists:financial_accounts,id',
            'from' => 'sometimes|nullable|date',
            'to' => 'sometimes|nullable|date|after_or_equal:from',
        ]);

        return array_filter($validated, fn ($value) => $value !== null && $value !== '');
    }

    private function filteredTransactions(array $filters)
    {
        $query = Transaction::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['account_id'])) {
            $accountId = (int) $filters['account_id'];
            $query->where(function ($accountQuery) use ($accountId) {
                $accountQuery->where('financial_account_id', $accountId)
                    ->orWhere('target_account_id', $accountId);
            });
        }

        if (isset($filters['from'])) {
            $query->whereDate('transaction_date', '>=', $filters['from']);
        }

        if (isset($filters['to'])) {
            $query->whereDate('transaction_date', '<=', $filters['to']);
        }

        return $query;
    }

    private function safeCsvText(string $value): string
    {
        return preg_match('/^\s*[=+\-@\t\r]/u', $value) ? "'" . $value : $value;
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'type' => 'required|in:Income,Expense,Transfer',
            'target_account_id' => 'required_if:type,Transfer|nullable|different:financial_account_id|exists:financial_accounts,id',
            'category' => 'required|string',
            'amount' => 'required|numeric|decimal:0,2|min:0.01|max:9999999999.99',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string',
            'payer_payee_name' => 'nullable|string',
            'narration' => 'required|string',
            'attachmentFile' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $voucherNo = 'VOUCH-' . date('Y') . '-' . strtoupper(Str::random(6));

        $attachmentPath = null;
        if ($request->hasFile('attachmentFile')) {
            $path = $request->file('attachmentFile')->store('uploads/vouchers', 'public');
            $attachmentPath = '/storage/' . $path;
        }

        DB::transaction(function () use ($validated, $voucherNo, $attachmentPath) {
            $accountIds = array_unique(array_filter([
                (int) $validated['financial_account_id'],
                isset($validated['target_account_id']) ? (int) $validated['target_account_id'] : null,
            ]));
            $accounts = FinancialAccount::whereIn('id', $accountIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $account = $accounts->get((int) $validated['financial_account_id']);
            $targetAccount = isset($validated['target_account_id'])
                ? $accounts->get((int) $validated['target_account_id'])
                : null;

            abort_unless($account && (! isset($validated['target_account_id']) || $targetAccount), 404);

            if (! $account->is_active || ($targetAccount && ! $targetAccount->is_active)) {
                throw ValidationException::withMessages([
                    'financial_account_id' => 'Transactions can only be posted to active accounts.',
                ]);
            }

            Transaction::create([
                'voucher_no' => $voucherNo,
                'financial_account_id' => $account->id,
                'target_account_id' => $targetAccount?->id,
                'type' => $validated['type'],
                'category' => $validated['category'],
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'payment_method' => $validated['payment_method'],
                'reference_no' => $validated['reference_no'] ?? null,
                'payer_payee_name' => $validated['payer_payee_name'] ?? null,
                'narration' => $validated['narration'],
                'attachment' => $attachmentPath,
                'created_by' => auth()->id(),
            ]);

            if ($validated['type'] === 'Income') {
                $account->increment('current_balance', $validated['amount']);
            } elseif ($validated['type'] === 'Expense') {
                $account->decrement('current_balance', $validated['amount']);
            } elseif ($validated['type'] === 'Transfer') {
                $account->decrement('current_balance', $validated['amount']);
                $targetAccount->increment('current_balance', $validated['amount']);
            }

            $details = "Voucher: {$voucherNo}, Amount: ₹{$validated['amount']}, Type: {$validated['type']}";
            if ($targetAccount) {
                $details .= ", From: {$account->account_code}, To: {$targetAccount->account_code}";
            }
            AuditLog::log('CREATE_TRANSACTION', $details);
        });

        return back()->with('success', "Transaction voucher {$voucherNo} recorded successfully!");
    }

    public function createAccount(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|unique:financial_accounts,account_code',
            'account_name' => 'required|string',
            'account_type' => 'required|in:Asset,Liability,Income,Expense,Equity',
            'current_balance' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        FinancialAccount::create($validated);
        AuditLog::log('CREATE_FINANCIAL_ACCOUNT', "Account: {$validated['account_name']} ({$validated['account_code']})");

        return back()->with('success', 'Financial ledger account created.');
    }
}
