<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Donation;
use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationReceiptMail;

class PaymentGatewayService
{
    /**
     * Create Razorpay Order
     */
    public static function createRazorpayOrder(float $amount, string $cause, string $donorName, string $email, string $phone): array
    {
        $keyId = \App\Models\Setting::get('razorpayKey', config('services.razorpay.key'));
        $orderId = 'order_' . Str::random(12);

        return [
            'key' => $keyId,
            'amount' => (int)($amount * 100), // in paise
            'currency' => 'INR',
            'order_id' => $orderId,
            'name' => 'KSO Chandigarh Student Welfare',
            'description' => "Donation for {$cause}",
            'prefill' => [
                'name' => $donorName,
                'email' => $email,
                'contact' => $phone,
            ],
            'notes' => [
                'cause' => $cause,
            ],
            'theme' => [
                'color' => '#003566'
            ]
        ];
    }

    /**
     * Process Completed Donation Payment and Auto-Log Ledger Voucher
     */
    public static function processSuccessfulDonation(array $data): Donation
    {
        // `donations.id` is an auto-incrementing BIGINT. Do not pass a generated
        // string ID here: it is not fillable and would be truncated/coerced.
        // The human-readable reference lives in `payment_ref`.
        $donation = Donation::create([
            'donor_name' => $data['donor_name'] ?? 'Anonymous',
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'INR',
            'cause' => $data['cause'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'payment_ref' => $data['payment_ref'],
            'status' => 'Completed',
            'date' => now()->toDateString(),
        ]);

        // Auto-log into General Ledger Financial Account
        try {
            $account = FinancialAccount::where('account_code', '1001')->first() 
                ?? FinancialAccount::first();

            if ($account) {
                $voucherNo = 'VOUCH-DON-' . strtoupper(Str::random(6));
                Transaction::create([
                    'voucher_no' => $voucherNo,
                    'financial_account_id' => $account->id,
                    'type' => 'Income',
                    'category' => 'Donation',
                    'amount' => $donation->amount,
                    'transaction_date' => now()->toDateString(),
                    'payment_method' => $data['payment_method'] ?? 'UPI/Online',
                    'reference_no' => $donation->payment_ref,
                    'payer_payee_name' => $donation->donor_name,
                    'narration' => "Online donation received for {$donation->cause} (Ref: {$donation->payment_ref})",
                ]);

                $account->increment('current_balance', $donation->amount);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info("Financial ledger auto-log skipped: " . $e->getMessage());
        }

        // Send Email Receipt
        if (!empty($donation->email)) {
            try {
                Mail::to($donation->email)->send(new DonationReceiptMail($donation));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info("Donation receipt mail skipped: " . $e->getMessage());
            }
        }

        AuditLog::log('DONATION_RECEIVED', "Amount: ₹{$donation->amount}, Cause: {$donation->cause}, Ref: {$donation->payment_ref}");

        return $donation;
    }
}
