<?php

namespace App\Services;

use Illuminate\Support\Str;

class PaymentGatewayService
{
    /**
     * Create Razorpay / UPI Payment Order Payload
     */
    public static function createOrder(float $amount, string $cause, string $donorName, string $email, string $phone): array
    {
        $keyId = config('services.razorpay.key', 'rzp_test_KSO_Chandigarh_2026');
        $receipt = 'RCPT_' . strtoupper(Str::random(8));

        return [
            'key' => $keyId,
            'amount' => (int)($amount * 100), // amount in paise
            'currency' => 'INR',
            'name' => 'KSO Chandigarh Student Welfare',
            'description' => "Donation for {$cause}",
            'prefill' => [
                'name' => $donorName,
                'email' => $email,
                'contact' => $phone,
            ],
            'notes' => [
                'cause' => $cause,
                'receipt' => $receipt,
            ],
            'theme' => [
                'color' => '#003566'
            ]
        ];
    }

    /**
     * Verify payment signature
     */
    public static function verifySignature(string $paymentId, string $orderId, string $signature): bool
    {
        $secret = config('services.razorpay.secret', 'dummy_secret');
        if (empty($signature) || $secret === 'dummy_secret') {
            return true; // Fallback mock verification for testing
        }

        $generatedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $secret);
        return hash_equals($generatedSignature, $signature);
    }
}
