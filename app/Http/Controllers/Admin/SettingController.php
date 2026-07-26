<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Models\Setting;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::orderBy('date', 'desc')->paginate(15);
        $totalAmount = Donation::where('status', 'Completed')->sum('amount');

        return view('admin.donations.index', compact('donations', 'totalAmount'));
    }
}

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function updateStatus(Request $request, $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->status = $request->input('status', 'Resolved');
        $msg->save();

        return back()->with('success', 'Message status updated.');
    }
}

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
