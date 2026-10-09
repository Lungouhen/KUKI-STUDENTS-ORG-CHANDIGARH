<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'field_id',
        'value',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo(MemberCustomField::class, 'field_id');
    }

    public function formattedValue(): string
    {
        if ($this->field && $this->field->field_type === 'checkbox') {
            return $this->value === '1' || $this->value === 'true' ? 'Yes' : 'No';
        }

        return (string) ($this->value ?? '');
    }
}
