<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

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
            'hasRazorpaySecret' => filled(Setting::get('razorpaySecret')),
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
        $validated = $request->validate([
            'siteName' => 'sometimes|required|string|max:255',
            'tagline' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'sometimes|nullable|string|max:30',
            'helpline' => 'sometimes|nullable|string|max:30',
            'address' => 'sometimes|nullable|string|max:1000',
            'announcement' => 'sometimes|nullable|string|max:500',
            'upiId' => 'sometimes|nullable|string|max:255',
            'razorpayKey' => 'sometimes|nullable|string|max:255',
            'razorpaySecret' => 'sometimes|nullable|string|max:4096',
            'memberPrefix' => ['sometimes', 'required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/'],
            'donorPrefix' => ['sometimes', 'required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/'],
            'beneficiaryPrefix' => ['sometimes', 'required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/'],
            'projectPrefix' => ['sometimes', 'required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/'],
            'primaryColor' => ['sometimes', 'nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accentColor' => ['sometimes', 'nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'baseMemberCount' => 'sometimes|nullable|integer|min:0|max:1000000',
            'collegesCount' => 'sometimes|nullable|integer|min:0|max:1000000',
            'baseEventCount' => 'sometimes|nullable|integer|min:0|max:1000000',
            'mapEmbedUrl' => 'sometimes|nullable|url|starts_with:https://',
            'facebook' => 'sometimes|nullable|url|starts_with:https://',
            'instagram' => 'sometimes|nullable|url|starts_with:https://',
            'whatsapp' => ['sometimes', 'nullable', 'string', 'max:30', 'regex:/^\+?[0-9]+$/'],
            'mail_host' => 'sometimes|nullable|string|max:255',
            'mail_port' => 'sometimes|nullable|integer|min:1|max:65535',
            'mail_username' => 'sometimes|nullable|string|max:255',
            'mail_password' => 'sometimes|nullable|string|max:4096',
            'mail_encryption' => 'sometimes|in:tls,ssl,none',
        ]);

        foreach (['razorpaySecret', 'mail_password'] as $secretKey) {
            if (array_key_exists($secretKey, $validated)
                && ($validated[$secretKey] === null || trim($validated[$secretKey]) === '')) {
                unset($validated[$secretKey]);
            }
        }

        if ($validated !== []) {
            DB::transaction(function () use ($validated) {
                foreach ($validated as $key => $value) {
                    Setting::set($key, $value);
                }

                AuditLog::log('UPDATE_SETTINGS', [
                    'updated_keys' => array_keys($validated),
                ]);
            });
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
            'hasMailPassword' => filled(Setting::get('mail_password')),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
        ];
        return view('admin.settings.smtp', compact('settings'));
    }

    public function gateways()
    {
        $settings = [
            'razorpayKey' => Setting::get('razorpayKey', ''),
            'hasRazorpaySecret' => filled(Setting::get('razorpaySecret')),
            'upiId' => Setting::get('upiId', ''),
        ];
        return view('admin.settings.gateways', compact('settings'));
    }
}
