<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'issued', 'rejected', 'revoked'];

    protected $fillable = [
        'member_id',
        'document_type',
        'purpose',
        'document_details',
        'member_snapshot',
        'certificate_number',
        'status',
        'issued_by',
        'issued_by_name',
        'issued_at',
        'revoked_at',
        'resolution_note',
    ];

    protected $casts = [
        'member_snapshot' => 'array',
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function typeLabel(): string
    {
        return config("member_documents.types.{$this->document_type}.label", 'Official Certificate');
    }

    public function typeStatement(): string
    {
        return config("member_documents.types.{$this->document_type}.statement", 'This certificate confirms the information approved by the organisation.');
    }
}
