<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MediaAsset;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SeoController extends Controller
{
    public function index()
    {
        return view('admin.seo.index', [
            'settings' => [
                'seoTitle' => Setting::get('seoTitle', ''),
                'seoDescription' => Setting::get('seoDescription', ''),
                'seoSocialImage' => Setting::get('seoSocialImage', ''),
                'seoIndexingEnabled' => filter_var(Setting::get('seoIndexingEnabled', true), FILTER_VALIDATE_BOOLEAN),
            ],
            'images' => MediaAsset::query()
                ->where('mime_type', 'like', 'image/%')
                ->orderBy('original_name')
                ->get(['path', 'original_name', 'alt_text']),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'seoTitle' => 'sometimes|nullable|string|max:255',
            'seoDescription' => 'sometimes|nullable|string|max:500',
            'seoSocialImage' => [
                'sometimes',
                'nullable',
                'string',
                Rule::exists('media_assets', 'path')
                    ->where(fn ($query) => $query->where('mime_type', 'like', 'image/%')),
            ],
            'seoIndexingEnabled' => 'sometimes|boolean',
        ]);

        foreach (['seoTitle', 'seoDescription', 'seoSocialImage'] as $key) {
            if (array_key_exists($key, $validated) && blank($validated[$key])) {
                $validated[$key] = null;
            }
        }

        if (array_key_exists('seoIndexingEnabled', $validated)) {
            $validated['seoIndexingEnabled'] = (bool) $validated['seoIndexingEnabled'];
        }

        if ($validated !== []) {
            DB::transaction(function () use ($validated) {
                foreach ($validated as $key => $value) {
                    Setting::set($key, $value);
                }

                AuditLog::log('UPDATE_SEO_SETTINGS', [
                    'updated_keys' => array_keys($validated),
                ]);
            });
        }

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings saved.');
    }
}
