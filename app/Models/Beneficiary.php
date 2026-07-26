<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'phone', 'type', 'support_needed', 'address'];

    /**
     * Generate a unique Beneficiary ID
     */
    public static function generateBeneficiaryId()
    {
        $prefix = Setting::get('beneficiaryPrefix', 'BEN-');
        $year = date('Y');
        $count = self::count() + 1;
        return $prefix . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
