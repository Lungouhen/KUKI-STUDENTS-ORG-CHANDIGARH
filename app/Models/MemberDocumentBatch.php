<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDocumentBatch extends Model
{
    protected $fillable = [
        'idempotency_key',
        'document_type',
        'template_id',
        'template_version',
        'created_by',
        'status',
        'shared_details',
        'criteria',
        'recipient_count',
        'issued_count',
        'skipped_count',
        'failed_count',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'criteria' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(MemberDocumentTemplate::class, 'template_id');
    }

    public function items()
    {
        return $this->hasMany(MemberDocumentBatchItem::class, 'batch_id');
    }

    public function documents()
    {
        return $this->hasMany(MemberDocument::class, 'batch_id');
    }
}
