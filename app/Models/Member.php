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
    ];

    protected $casts = [
        'dob' => 'date',
        'applied_date' => 'date',
        'approval_date' => 'date',
        'valid_until' => 'date',
    ];
}
