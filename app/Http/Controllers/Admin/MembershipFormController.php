<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MembershipFormController extends Controller
{
    private const MODULES = [
        'personal' => 'Personal details',
        'academic' => 'College and academic details',
        'address' => 'Addresses and emergency contact',
        'membership' => 'Membership category',
        'photo' => 'Student photograph',
        'declaration' => 'Applicant declaration and office use',
    ];

    public function index(Request $request)
    {
        return view('admin.membership-forms.index', [
            'modules' => self::MODULES,
            'selectedModules' => $this->selectedModules($request),
            'onlineRegistrationUrl' => route('membership.register'),
        ]);
    }

    public function print(Request $request)
    {
        return view('admin.membership-forms.print', [
            'selectedModules' => $this->selectedModules($request),
        ]);
    }

    public function download(Request $request)
    {
        $html = view('admin.membership-forms.print', [
            'selectedModules' => $this->selectedModules($request),
            'download' => true,
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="kso-membership-form.html"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function selectedModules(Request $request): array
    {
        $validated = $request->validate([
            'modules' => 'sometimes|array',
            'modules.*' => 'string|in:' . implode(',', array_keys(self::MODULES)),
        ]);

        return array_values($validated['modules'] ?? array_keys(self::MODULES));
    }
}
