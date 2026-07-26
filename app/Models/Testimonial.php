<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'author_title',
        'college_name',
        'quote',
        'photo',
        'rating',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
