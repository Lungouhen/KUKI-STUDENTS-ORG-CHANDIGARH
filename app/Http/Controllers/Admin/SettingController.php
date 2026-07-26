<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'siteName' => Setting::get('siteName', 'Kuki Students\' Organisation Chandigarh'),
            'tagline' => Setting::get('tagline', 'Empowering Students • Preserving Culture • Serving Community'),
            'email' => Setting::get('email', 'ksochandigarh@gmail.com'),
            'phone' => Setting::get('phone', '+91 98765 43210'),
            'helpline' => Setting::get('helpline', '+91 98765 43211'),
            'address' => Setting::get('address', 'Room 12, Student Centre, Panjab University, Sector 14, Chandigarh, 160014'),
            'announcement' => Setting::get('announcement', '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN.'),
            'upiId' => Setting::get('upiId', 'ksochandigarh@upi'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except('_token');
        foreach ($inputs as $key => $val) {
            Setting::set($key, $val);
        }

        return back()->with('success', 'Website settings saved.');
    }
}
