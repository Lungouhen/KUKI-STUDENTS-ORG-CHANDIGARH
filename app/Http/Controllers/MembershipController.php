<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function registerForm()
    {
        return view('membership.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'blood_group' => 'required|string',
            'institution' => 'required|string',
            'course' => 'required|string',
            'department' => 'nullable|string',
            'year_of_study' => 'required|string',
            'roll_no' => 'nullable|string',
            'permanent_address' => 'required|string',
            'current_address' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
            'photoFile' => 'nullable|image|max:5120',
        ]);

        // Generate ID
        $count = Member::count() + 1;
        $id = 'KSO-CHD-2026-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $photoPath = $request->gender === 'Female' ? '/images/default-avatar-f.png' : '/images/default-avatar-m.png';
        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/members', 'public');
            $photoPath = '/storage/' . $path;
        }

        $member = Member::create([
            'id' => $id,
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'blood_group' => $validated['blood_group'],
            'institution' => $validated['institution'],
            'course' => $validated['course'],
            'department' => $validated['department'] ?? null,
            'year_of_study' => $validated['year_of_study'],
            'roll_no' => $validated['roll_no'] ?? null,
            'permanent_address' => $validated['permanent_address'],
            'current_address' => $validated['current_address'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_phone' => $validated['emergency_phone'],
            'photo' => $photoPath,
            'status' => 'Pending',
            'membership_type' => 'Regular Student Member',
            'applied_date' => now()->toDateString(),
            'valid_until' => '2027-06-30',
        ]);

        return redirect()->route('membership.idCard', $member->id)->with('success', 'Registration submitted successfully! Your digital ID card is below.');
    }

    public function verifyForm()
    {
        return view('membership.verify');
    }

    public function verify(Request $request)
    {
        $id = trim($request->input('member_id'));
        $member = Member::find($id);

        return view('membership.verify', compact('member', 'id'));
    }

    public function portalForm()
    {
        return view('membership.portal');
    }

    public function portalLogin(Request $request)
    {
        $identifier = trim($request->input('identifier'));
        $member = Member::where('id', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$member) {
            return back()->with('error', 'No member account found matching details.');
        }

        session(['member_id' => $member->id]);
        return redirect()->route('membership.portal');
    }

    public function portalDashboard()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('membership.portalLogin');
        }

        $member = Member::find($memberId);
        if (!$member) {
            session()->forget('member_id');
            return redirect()->route('membership.portalLogin');
        }

        return view('membership.portal_dashboard', compact('member'));
    }

    public function portalLogout()
    {
        session()->forget('member_id');
        return redirect()->route('membership.portalLogin');
    }

    public function idCard($id)
    {
        $member = Member::findOrFail($id);
        return view('membership.id_card', compact('member'));
    }
}
