<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'field_type',
        'options',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('label');
    }

    public function optionList(): array
    {
        $options = $this->options ?? [];

        if (is_string($options)) {
            $options = json_decode($options, true);
        }

        if (! is_array($options)) {
            $options = [];
        }

        return array_values(array_filter(array_map(function ($option) {
            return trim((string) $option);
        }, $options), fn ($option) => $option !== ''));
    }

    public function values()
    {
        return $this->hasMany(MemberCustomFieldValue::class, 'field_id');
    }
}
