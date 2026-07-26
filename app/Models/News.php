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
    ];

    protected $casts = [
        'date' => 'date',
        'is_important' => 'boolean',
    ];
}
