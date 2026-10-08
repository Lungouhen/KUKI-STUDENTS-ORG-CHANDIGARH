<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'date',
        'time',
        'venue',
        'description',
        'image',
        'status',
        'registration_link',
        'is_featured',
        'publication_status',
        'scheduled_publish_at',
    ];

    protected $casts = [
        'date' => 'date',
        'is_featured' => 'boolean',
        'scheduled_publish_at' => 'datetime',
    ];
}
