<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralContent extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (GeneralContent $content) {
            if ($content->isDirty('publication_status')) {
                $content->is_published = $content->publication_status === 'published';
            } elseif ($content->isDirty('is_published')) {
                $content->publication_status = $content->is_published ? 'published' : 'draft';
            }
        });
    }

    protected $fillable = [
        'type', // slider, certificate, achievement, policy, notice, campaign, career
        'title',
        'content',
        'image',
        'link',
        'is_published',
        'display_order',
        'publication_status',
        'scheduled_publish_at',,
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'scheduled_publish_at' => 'datetime',
    ];

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'image', 'path');
    }
}
