<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberCustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberCustomFieldController extends Controller
{
    public function index()
    {
        $fields = MemberCustomField::orderBy('sort_order')->orderBy('label')->get();

        return view('admin.members.custom-fields', compact('fields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/'],
            'field_type' => ['required', 'in:text,textarea,number,date,select,radio,checkbox'],
            'options' => 'nullable|string|max:2000',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['label']);

        MemberCustomField::create([
            'slug' => $slug,
            'label' => $validated['label'],
            'field_type' => $validated['field_type'],
            'options' => $this->normalizeOptions($validated['options'] ?? null),
            'is_required' => (bool) ($validated['is_required'] ?? false),
            'is_active' => true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.memberCustomFields.index')->with('success', 'Custom member field added.');
    }

    public function update(Request $request, $id)
    {
        $field = MemberCustomField::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/', Rule::unique('member_custom_fields', 'slug')->ignore($field->id)],
            'field_type' => ['required', 'in:text,textarea,number,date,select,radio,checkbox'],
            'options' => 'nullable|string|max:2000',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['label']);

        $field->update([
            'slug' => $slug,
            'label' => $validated['label'],
            'field_type' => $validated['field_type'],
            'options' => $this->normalizeOptions($validated['options'] ?? null),
            'is_required' => (bool) ($validated['is_required'] ?? $field->is_required),
            'is_active' => (bool) ($validated['is_active'] ?? $field->is_active),
            'sort_order' => $validated['sort_order'] ?? $field->sort_order,
        ]);

        return redirect()->route('admin.memberCustomFields.index')->with('success', 'Custom member field updated.');
    }

    public function destroy($id)
    {
        $field = MemberCustomField::findOrFail($id);
        $field->delete();

        return redirect()->route('admin.memberCustomFields.index')->with('success', 'Custom member field removed.');
    }

    protected function normalizeOptions($value): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            $items = $value;
        } else {
            $items = preg_split('/\r\n|\n|,/', (string) $value);
        }

        $normalized = array_values(array_filter(array_map(function ($item) {
            return trim((string) $item);
        }, (array) $items), function ($item) {
            return $item !== '';
        }));

        return $normalized === [] ? null : $normalized;
    }
}
