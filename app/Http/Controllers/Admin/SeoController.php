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
            'seoTitle' => 'nullable|string|max:255',
            'seoDescription' => 'nullable|string|max:500',
            'seoSocialImage' => [
                'nullable',
                'string',
                Rule::exists('media_assets', 'path')
                    ->where(fn ($query) => $query->where('mime_type', 'like', 'image/%')),
            ],
            'seoIndexingEnabled' => 'required|boolean',
        ]);

        foreach (['seoTitle', 'seoDescription', 'seoSocialImage'] as $key) {
            if (blank($validated[$key] ?? null)) {
                $validated[$key] = null;
            }
        }

        $validated['seoIndexingEnabled'] = (bool) $validated['seoIndexingEnabled'];

        DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                Setting::set($key, $value);
            }

            AuditLog::log('UPDATE_SEO_SETTINGS', [
                'updated_keys' => array_keys($validated),
            ]);
        });

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings saved.');
    }
}
