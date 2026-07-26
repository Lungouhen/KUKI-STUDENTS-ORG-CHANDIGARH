<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'position', 'election_date', 'status'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
