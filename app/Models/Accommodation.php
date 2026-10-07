<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    public const TYPES = [
        'Co-Living PG',
        'Girls PG',
        'Boys PG',
        'Hostel',
        'Furnished Flatshare',
    ];

    protected $fillable = [
        'name',
        'type',
        'location',
        'landmark',
        'rent_monthly',
        'description',
        'contact_phone',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'rent_monthly' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
