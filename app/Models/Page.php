<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if ($page->isDirty('publication_status')) {
                $page->is_published = $page->publication_status === 'published';
            } elseif ($page->isDirty('is_published')) {
                $page->publication_status = $page->is_published ? 'published' : 'draft';
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'template',
        'featured_image',
        'meta_title',
        'meta_description',
        'is_published',
        'publication_status',
        'scheduled_publish_at',
        'sections',
        'view_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'scheduled_publish_at' => 'datetime',
        'sections' => 'array',
    ];

    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class)->orderByDesc('version');
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image', 'path');
    }

    public function templateView(): string
    {
        $template = $this->template;

        return array_key_exists($template, config('page_templates', []))
            ? 'pages.templates.'.$template
            : 'pages.templates.standard';
    }
}
