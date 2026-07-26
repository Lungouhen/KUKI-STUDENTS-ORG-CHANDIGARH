<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // slider, certificate, achievement, policy, notice, campaign, career
        'title',
        'content',
        'image',
        'link',
        'is_published',
        'display_order'
    ];
}
