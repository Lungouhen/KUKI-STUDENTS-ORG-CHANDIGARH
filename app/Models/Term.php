<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    public static function current()
    {
        return self::where('is_active', true)->first();
    }
}
