<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MediaAsset extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'alt_text',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public static function storeUpload(UploadedFile $file, string $altText): self
    {
        $storedPath = $file->store('uploads/media', 'public');

        try {
            return static::create([
                'path' => '/storage/'.$storedPath,
                'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
                'alt_text' => $altText,
                'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPath);
            throw $exception;
        }
    }

    public function deleteStoredFile(): void
    {
        $prefix = '/storage/uploads/media/';
        if (str_starts_with($this->path, $prefix)) {
            Storage::disk('public')->delete(substr($this->path, strlen('/storage/')));
        }

        $this->delete();
    }
}
