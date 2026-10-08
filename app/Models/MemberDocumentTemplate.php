<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDocumentTemplate extends Model
{
    protected $fillable = [
        'document_type',
        'version',
        'title',
        'statement',
        'style',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(MemberDocument::class, 'template_id');
    }

    public function batches()
    {
        return $this->hasMany(MemberDocumentBatch::class, 'template_id');
    }
}
