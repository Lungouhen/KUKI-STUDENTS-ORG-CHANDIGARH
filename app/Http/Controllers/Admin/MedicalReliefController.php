<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use App\Models\MedicalReliefClaim;
use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MedicalReliefController extends Controller
{
    public function index()
    {
        $claims = MedicalReliefClaim::with('member')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.medical.index', compact('claims'));
    }

    public function updateStatus(Request $request, $id)
    {
        $claim = MedicalReliefClaim::findOrFail($id);
        $validated = Validator::make($request->all(), [
            'status' => ['required', 'in:Pending,Approved,Disbursed,Rejected'],
            'amount_approved' => [
                'required_if:status,Approved,Disbursed',
                'nullable',
                'numeric',
                'decimal:0,2',
                'min:0.01',
                'max:' . $claim->amount_requested,
            ],
        ])->validate();

        DB::transaction(function () use ($validated, $claim) {
            $claim = MedicalReliefClaim::whereKey($claim->id)->lockForUpdate()->firstOrFail();
            $newStatus = $validated['status'];
            $transitions = [
                'Pending' => ['Pending', 'Approved', 'Rejected'],
                'Approved' => ['Approved', 'Disbursed'],
                'Disbursed' => ['Disbursed'],
                'Rejected' => ['Rejected'],
            ];

            if (! in_array($newStatus, $transitions[$claim->status] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'This claim status transition is not allowed.',
                ]);
            }

            $amountApproved = in_array($newStatus, ['Approved', 'Disbursed'], true)
                ? $validated['amount_approved']
                : 0;

            if (in_array($claim->status, ['Approved', 'Disbursed'], true)
                && number_format((float) $amountApproved, 2, '.', '') !== number_format((float) $claim->amount_approved, 2, '.', '')) {
                throw ValidationException::withMessages([
                    'amount_approved' => 'The approved amount cannot be changed after approval.',
                ]);
            }

            if ($newStatus === 'Approved' && $claim->status === 'Pending') {
                $reference = 'MED-CLAIM-' . $claim->id;
                if (Transaction::where('reference_no', $reference)->exists()) {
                    throw ValidationException::withMessages([
                        'status' => 'A ledger voucher already exists for this claim.',
                    ]);
                }

                $account = FinancialAccount::where('account_code', '1002')
                    ->lockForUpdate()
                    ->first();

                if (! $account || ! $account->is_active) {
                    throw ValidationException::withMessages([
                        'status' => 'The Emergency Relief Fund account is unavailable.',
                    ]);
                }

                Transaction::create([
                    'voucher_no' => 'VOUCH-MED-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'financial_account_id' => $account->id,
                    'type' => 'Expense',
                    'category' => 'Medical Relief',
                    'amount' => $amountApproved,
                    'transaction_date' => now()->toDateString(),
                    'payment_method' => 'Bank Transfer',
                    'reference_no' => $reference,
                    'payer_payee_name' => $claim->patient_name . ' (Hospital: ' . $claim->hospital_name . ')',
                    'narration' => "Medical relief grant for Claim #{$claim->id}. Patient: {$claim->patient_name}.",
                    'created_by' => auth()->id(),
                ]);

                $account->decrement('current_balance', $amountApproved);
            }

            $claim->status = $newStatus;
            $claim->amount_approved = $amountApproved;
            $claim->save();

            AuditLog::log('MEDICAL_CLAIM_STATUS', "Claim #{$claim->id} status: {$claim->status}, Amount: {$claim->amount_approved}");
        });

        return back()->with('success', 'Medical relief claim status updated.');
    }
}
