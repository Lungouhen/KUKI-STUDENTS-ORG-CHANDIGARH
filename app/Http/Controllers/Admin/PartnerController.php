<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('name')->paginate(15);
        return view('admin.partners.index', compact('partners'));
    }

    public function exportCsv()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Contact Person', 'Email', 'Phone', 'Type', 'Category', 'Status', 'Address']);

            Partner::chunk(100, function ($partners) use ($handle) {
                foreach ($partners as $p) {
                    fputcsv($handle, [
                        $p->id,
                        $p->name,
                        $p->contact_person,
                        $p->email,
                        $p->phone,
                        $p->type,
                        $p->category,
                        $p->status,
                        $p->address
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="kso_partners_export.csv"');

        return $response;
    }

    /**
     * Partners are created through a modal on the index page, so there is no
     * standalone create screen. Kept for route-model completeness.
     */
    public function create()
    {
        return redirect()->route('admin.partners.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'type' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
        ]);

        Partner::create($validated);
        AuditLog::log('CREATE_PARTNER', "Partner: {$validated['name']}");

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'type' => 'required|string|max:50',
            'category' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        $partner->update($validated);
        AuditLog::log('UPDATE_PARTNER', "Partner ID: {$id}");

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated.');
    }

    public function destroy($id)
    {
        Partner::findOrFail($id)->delete();
        return back()->with('success', 'Partner removed.');
    }
}
