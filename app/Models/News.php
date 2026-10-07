<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'category',
        'date',
        'content',
        'author',
        'is_important',
        'is_member_post',
    ];

    protected $casts = [
        'date' => 'date',
        'is_important' => 'boolean',
        'is_member_post' => 'boolean',
    ];

    public function scopeOfficial($query)
    {
        return $query->where('is_member_post', false);
    }
}
