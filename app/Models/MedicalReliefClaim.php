<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReliefClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'patient_name',
        'hospital_name',
        'nature_of_illness',
        'amount_requested',
        'amount_approved',
        'status',
        'doctor_notes',
        'medical_document',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
