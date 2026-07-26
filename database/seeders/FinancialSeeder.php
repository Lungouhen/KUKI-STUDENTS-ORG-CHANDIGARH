<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialAccount;
use App\Models\Transaction;

class FinancialSeeder extends Seeder
{
    public function run(): void
    {
        $acc1 = FinancialAccount::updateOrCreate(['account_code' => '1001'], [
            'account_name' => 'Main Bank Account (SBI Panjab University Branch)',
            'account_type' => 'Asset',
            'current_balance' => 145000.00,
            'description' => 'Primary KSO Chandigarh operational bank account'
        ]);

        $acc2 = FinancialAccount::updateOrCreate(['account_code' => '1002'], [
            'account_name' => 'Emergency Medical Relief Fund',
            'account_type' => 'Asset',
            'current_balance' => 62500.00,
            'description' => 'Restricted student health emergency desk'
        ]);

        $acc3 = FinancialAccount::updateOrCreate(['account_code' => '4001'], [
            'account_name' => 'Membership Registration Fee Income',
            'account_type' => 'Income',
            'current_balance' => 38000.00,
            'description' => 'Annual student membership registration collection'
        ]);

        $acc4 = FinancialAccount::updateOrCreate(['account_code' => '5001'], [
            'account_name' => 'Cultural Event & Freshers Expense',
            'account_type' => 'Expense',
            'current_balance' => 24000.00,
            'description' => 'Auditorium booking, sound, costumes, and catering'
        ]);

        // Transactions
        Transaction::updateOrCreate(['voucher_no' => 'VOUCH-2026-0001'], [
            'financial_account_id' => $acc1->id,
            'type' => 'Income',
            'category' => 'Donation',
            'amount' => 5000.00,
            'transaction_date' => '2026-07-10',
            'payment_method' => 'UPI',
            'reference_no' => 'UPI/628190381023',
            'payer_payee_name' => 'Dr. Haokip (Alumni)',
            'narration' => 'Contribution towards Student Emergency Welfare Fund'
        ]);

        Transaction::updateOrCreate(['voucher_no' => 'VOUCH-2026-0002'], [
            'financial_account_id' => $acc2->id,
            'type' => 'Expense',
            'category' => 'Medical Relief',
            'amount' => 12000.00,
            'transaction_date' => '2026-07-18',
            'payment_method' => 'Bank Transfer',
            'reference_no' => 'TXN/9981023812',
            'payer_payee_name' => 'PGIMER Hospital Billing Counter',
            'narration' => 'Emergency hospital grant for student accident treatment'
        ]);
    }
}
