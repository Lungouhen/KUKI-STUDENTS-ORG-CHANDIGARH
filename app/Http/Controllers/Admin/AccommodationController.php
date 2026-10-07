<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\AuditLog;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::orderByDesc('created_at')->paginate(15);
        return view('admin.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        return view('admin.accommodations.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/accommodations', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $accommodation = Accommodation::create($validated);
        AuditLog::log('CREATE_ACCOMMODATION', "Accommodation: {$accommodation->name} (ID: {$accommodation->id})");

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation listing added.');
    }

    public function edit($id)
    {
        $accommodation = Accommodation::findOrFail($id);
        return view('admin.accommodations.edit', compact('accommodation'));
    }

    public function update(Request $request, $id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $validated = $this->validated($request);

        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/accommodations', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $accommodation->update($validated);
        AuditLog::log('UPDATE_ACCOMMODATION', "Accommodation ID: {$id}");

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation listing updated.');
    }

    public function destroy($id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $accommodation->delete();
        AuditLog::log('DELETE_ACCOMMODATION', "Accommodation: {$accommodation->name} (ID: {$id})");

        return back()->with('success', 'Accommodation listing removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', Accommodation::TYPES),
            'location' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'rent_monthly' => 'required|numeric|min:0|max:99999999.99',
            'description' => 'nullable|string|max:2000',
            'contact_phone' => 'required|string|max:30',
            'photoFile' => 'nullable|image|max:5120',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
