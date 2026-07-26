<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'financial_account_id',
        'type',
        'category',
        'amount',
        'transaction_date',
        'payment_method',
        'reference_no',
        'payer_payee_name',
        'narration',
        'attachment',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
