<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDocumentBatchItem extends Model
{
    protected $fillable = [
        'batch_id',
        'member_id',
        'member_document_id',
        'status',
        'message',
    ];

    public function batch()
    {
        return $this->belongsTo(MemberDocumentBatch::class, 'batch_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function document()
    {
        return $this->belongsTo(MemberDocument::class, 'member_document_id');
    }
}
