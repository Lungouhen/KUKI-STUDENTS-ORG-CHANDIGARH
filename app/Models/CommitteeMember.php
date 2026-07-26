<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'institution',
        'phone',
        'email',
        'photo',
        'tenure',
        'display_order',
    ];
}
