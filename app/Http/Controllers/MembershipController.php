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
            'membership_category' => 'required|string',
            'family_count' => 'nullable|integer',
            'photoFile' => 'nullable|image|max:5120',
        ]);

        // Generate ID
        $id = Member::generateMembershipId();

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
            'membership_category' => $validated['membership_category'],
            'family_count' => $validated['family_count'] ?? 0,
            'photo' => $photoPath,
            'status' => 'Pending',
            'membership_type' => 'Regular Student Member',
            'applied_date' => now()->toDateString(),
            'valid_until' => Member::calculateValidityDate(),
        ]);

        // Send registration confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\MemberRegisteredMail($member));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info("Mail send skipped: " . $e->getMessage());
        }

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

    public function verifyDirect($id)
    {
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
            return redirect()->route('membership.portal');
        }

        $member = Member::find($memberId);
        if (!$member) {
            session()->forget('member_id');
            return redirect()->route('membership.portal');
        }

        $medicalClaims = \App\Models\MedicalReliefClaim::where('member_id', $memberId)->get();
        
        // Fetch all news/notices & student updates as a social feed
        $posts = \App\Models\News::orderBy('created_at', 'desc')->get();
        
        // Mock data for dashboard overview requirements
        $totalFeesPaid = 250;
        $paymentsCount = 1;

        return view('membership.portal_dashboard', compact('member', 'medicalClaims', 'totalFeesPaid', 'paymentsCount', 'posts'));
    }

    public function storeStudentPost(Request $request)
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('membership.portal');
        }

        $member = Member::find($memberId);
        if (!$member) {
            return redirect()->route('membership.portal');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'category' => 'nullable|string',
        ]);

        \App\Models\News::create([
            'title' => 'Update from ' . $member->full_name,
            'category' => $validated['category'] ?? 'General',
            'content' => $validated['content'],
            'author' => $member->full_name,
            'date' => now()->toDateString(),
            'is_important' => false,
        ]);

        return back()->with('success', 'Your update has been shared with the student feed! 🚀');
    }

    public function submitMedicalClaim(Request $request)
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('membership.portal');
        }

        $validated = $request->validate([
            'patient_name' => 'required|string',
            'hospital_name' => 'required|string',
            'nature_of_illness' => 'required|string',
            'amount_requested' => 'required|numeric|min:1',
            'medical_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $docPath = null;
        if ($request->hasFile('medical_document')) {
            $path = $request->file('medical_document')->store('uploads/medical', 'public');
            $docPath = '/storage/' . $path;
        }

        \App\Models\MedicalReliefClaim::create([
            'member_id' => $memberId,
            'patient_name' => $validated['patient_name'],
            'hospital_name' => $validated['hospital_name'],
            'nature_of_illness' => $validated['nature_of_illness'],
            'amount_requested' => $validated['amount_requested'],
            'status' => 'Pending',
            'medical_document' => $docPath,
        ]);

        return back()->with('success', 'Your medical relief claim has been submitted to the KSO Executive Body.');
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
