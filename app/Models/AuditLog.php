<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $details = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}
