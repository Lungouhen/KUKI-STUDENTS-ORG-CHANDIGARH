<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResource extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Institutional Guides',
        'Scholarships & Financial Aid',
        'Admission Prospectuses',
        'Question Banks',
    ];

    protected $fillable = [
        'title',
        'category',
        'file_path',
        'file_size',
        'download_count',
        'is_active',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function humanFileSize(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024) . ' KB';
        }
        return $bytes . ' B';
    }
}
