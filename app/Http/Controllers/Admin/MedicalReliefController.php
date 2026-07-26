<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalReliefClaim;
use App\Models\AuditLog;

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
        $oldStatus = $claim->status;
        $newStatus = $request->input('status');
        $amountApproved = $request->input('amount_approved', 0);

        $claim->status = $newStatus;
        $claim->amount_approved = $amountApproved;
        $claim->save();

        // Auto-log expense if newly approved
        if ($newStatus === 'Approved' && $oldStatus !== 'Approved' && $amountApproved > 0) {
            try {
                $account = \App\Models\FinancialAccount::where('account_code', '1002')->first(); // Emergency Relief Fund
                if ($account) {
                    $voucherNo = 'VOUCH-MED-' . strtoupper(\Illuminate\Support\Str::random(6));
                    \App\Models\Transaction::create([
                        'voucher_no' => $voucherNo,
                        'financial_account_id' => $account->id,
                        'type' => 'Expense',
                        'category' => 'Medical Relief',
                        'amount' => $amountApproved,
                        'transaction_date' => now()->toDateString(),
                        'payment_method' => 'Bank Transfer',
                        'payer_payee_name' => $claim->patient_name . ' (Hospital: ' . $claim->hospital_name . ')',
                        'narration' => "Medical relief grant for Claim #{$claim->id}. Patient: {$claim->patient_name}.",
                    ]);
                    $account->decrement('current_balance', $amountApproved);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info("Medical ledger auto-log skipped: " . $e->getMessage());
            }
        }

        AuditLog::log('MEDICAL_CLAIM_STATUS', "Claim #{$claim->id} status: {$claim->status}, Amount: {$claim->amount_approved}");

        return back()->with('success', 'Medical relief claim status updated and ledger record created.');
    }
}
