<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'url_or_route',
        'location',
        'icon_class',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
