<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'full_name',
        'gender',
        'dob',
        'phone',
        'email',
        'blood_group',
        'institution',
        'course',
        'department',
        'year_of_study',
        'roll_no',
        'permanent_address',
        'current_address',
        'emergency_contact',
        'emergency_phone',
        'photo',
        'status',
        'membership_type',
        'applied_date',
        'approval_date',
        'valid_until',
        'designation',
        'membership_category',
        'family_count',
        'is_active',
    ];

    protected $casts = [
        'dob' => 'date',
        'applied_date' => 'date',
        'approval_date' => 'date',
        'valid_until' => 'date',
    ];

    /**
     * Generate a unique Membership ID
     */
    public static function generateMembershipId()
    {
        $prefix = Setting::get('memberPrefix', 'KSO-CHD-');
        $year = date('Y');
        $count = self::count() + 1;
        return $prefix . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate default validity date (June 30th of next year)
     */
    public static function calculateValidityDate()
    {
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n');

        // If registered in or after July, valid until June next year.
        // If registered before July, valid until June this year.
        if ($currentMonth >= 7) {
            $validYear = $currentYear + 1;
        } else {
            $validYear = $currentYear;
        }

        return "{$validYear}-06-30";
    }
}
