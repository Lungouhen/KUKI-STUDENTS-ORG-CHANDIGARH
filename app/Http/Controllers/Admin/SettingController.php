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
            'razorpayKey' => Setting::get('razorpayKey', 'rzp_test_KSO_Chandigarh'),
            'razorpaySecret' => Setting::get('razorpaySecret', ''),
            'memberPrefix' => Setting::get('memberPrefix', 'KSO-CHD-'),
            'donorPrefix' => Setting::get('donorPrefix', 'DONOR-'),
            'beneficiaryPrefix' => Setting::get('beneficiaryPrefix', 'BEN-'),
            'projectPrefix' => Setting::get('projectPrefix', 'PROJ-'),
            'primaryColor' => Setting::get('primaryColor', '#003566'),
            'accentColor' => Setting::get('accentColor', '#FFBF00'),
            'baseMemberCount' => Setting::get('baseMemberCount', 150),
            'collegesCount' => Setting::get('collegesCount', 12),
            'baseEventCount' => Setting::get('baseEventCount', 25),
            'mapEmbedUrl' => Setting::get('mapEmbedUrl', ''),
            'facebook' => Setting::get('facebook', 'https://facebook.com/ksochandigarh'),
            'instagram' => Setting::get('instagram', 'https://instagram.com/kso_chandigarh'),
            'whatsapp' => Setting::get('whatsapp', '+919876543210'),
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

    public function integrations()
    {
        return view('admin.settings.integrations');
    }

    public function smtp()
    {
        $settings = [
            'mail_host' => Setting::get('mail_host', 'smtp.mailtrap.io'),
            'mail_port' => Setting::get('mail_port', '2525'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password' => Setting::get('mail_password', ''),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
        ];
        return view('admin.settings.smtp', compact('settings'));
    }

    public function gateways()
    {
        $settings = [
            'razorpayKey' => Setting::get('razorpayKey', ''),
            'razorpaySecret' => Setting::get('razorpaySecret', ''),
            'upiId' => Setting::get('upiId', ''),
        ];
        return view('admin.settings.gateways', compact('settings'));
    }
}
