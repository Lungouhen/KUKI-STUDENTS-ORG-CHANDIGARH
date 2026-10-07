<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberFeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'period',
        'amount',
        'payment_method',
        'reference_no',
        'voucher_no',
        'paid_on',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_on' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * The membership year label for a given date, aligned with the
     * July-June validity window used by Member::calculateValidityDate().
     * e.g. 2026-10-07 => "2026-27", 2027-03-01 => "2026-27".
     */
    public static function periodFor(?\DateTimeInterface $date = null): string
    {
        $date = $date ?: now();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');

        $startYear = $month >= 7 ? $year : $year - 1;

        return $startYear . '-' . substr((string) ($startYear + 1), -2);
    }
}
