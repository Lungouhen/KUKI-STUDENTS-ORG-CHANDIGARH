<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

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
        'view_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function templateView(): string
    {
        $template = $this->template;

        return array_key_exists($template, config('page_templates', []))
            ? 'pages.templates.' . $template
            : 'pages.templates.standard';
    }
}
