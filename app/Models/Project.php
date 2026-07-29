<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['project_code', 'term_id', 'title', 'description', 'budget', 'status'];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    /**
     * Generate a unique human-readable project code (e.g. PROJ-2026-0001).
     *
     * This is stored in `project_code`, not the primary key: `projects.id` is an
     * auto-incrementing BIGINT referenced by `beneficiaries.project_id`.
     */
    public static function generateProjectCode(): string
    {
        $prefix = Setting::get('projectPrefix', 'PROJ-');
        $year = date('Y');

        // Derive the sequence from the highest existing code for this year rather
        // than a row count, so deletions cannot cause a duplicate code.
        $latest = static::query()
            ->where('project_code', 'like', $prefix.$year.'-%')
            ->orderByDesc('project_code')
            ->value('project_code');

        $next = $latest ? ((int) substr($latest, -4)) + 1 : 1;

        return $prefix.$year.'-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
