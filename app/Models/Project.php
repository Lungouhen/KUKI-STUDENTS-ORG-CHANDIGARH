<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'title', 'description', 'budget', 'status'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    /**
     * Generate a unique Project ID
     */
    public static function generateProjectId()
    {
        $prefix = Setting::get('projectPrefix', 'PROJ-');
        $year = date('Y');
        $count = self::count() + 1;
        return $prefix . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
