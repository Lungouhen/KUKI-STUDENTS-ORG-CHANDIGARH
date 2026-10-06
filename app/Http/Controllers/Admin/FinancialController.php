<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\AuditLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $accounts = FinancialAccount::all();
        $type = $request->query('type');
        
        $query = Transaction::with(['account', 'targetAccount']);
        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15);

        $totalIncome = Transaction::where('type', 'Income')->sum('amount');
        $totalExpense = Transaction::where('type', 'Expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('admin.financial.index', compact('accounts', 'transactions', 'totalIncome', 'totalExpense', 'netBalance', 'type'));
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'type' => 'required|in:Income,Expense,Transfer',
            'target_account_id' => 'required_if:type,Transfer|nullable|different:financial_account_id|exists:financial_accounts,id',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
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
