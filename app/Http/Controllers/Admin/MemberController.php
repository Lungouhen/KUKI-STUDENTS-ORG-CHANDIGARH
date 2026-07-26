<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $is_volunteer = $request->query('is_volunteer');

        $query = Member::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($is_volunteer !== null) {
            $query->where('is_volunteer', $is_volunteer);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.members.index', compact('members', 'status', 'search'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dob' => 'nullable|date',
            'phone' => 'required|string',
            'email' => 'required|email',
            'blood_group' => 'required|string',
            'institution' => 'required|string',
            'course' => 'required|string',
            'department' => 'nullable|string',
            'year_of_study' => 'required|string',
            'permanent_address' => 'required|string',
            'current_address' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
            'status' => 'required|string',
            'photoFile' => 'nullable|image|max:5120',
        ]);

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
            'dob' => $validated['dob'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'blood_group' => $validated['blood_group'],
            'institution' => $validated['institution'],
            'course' => $validated['course'],
            'department' => $validated['department'] ?? null,
            'year_of_study' => $validated['year_of_study'],
            'permanent_address' => $validated['permanent_address'],
            'current_address' => $validated['current_address'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_phone' => $validated['emergency_phone'],
            'photo' => $photoPath,
            'status' => $validated['status'],
            'membership_type' => 'Regular Student Member',
            'applied_date' => now()->toDateString(),
            'valid_until' => Member::calculateValidityDate(),
        ]);

        AuditLog::log('ADMIN_CREATE_MEMBER', "Member ID: {$member->id}, Name: {$member->full_name}");

        return redirect()->route('admin.members.index')->with('success', "Member {$member->id} registered successfully!");
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('admin.members.show', compact('member'));
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dob' => 'nullable|date',
            'phone' => 'required|string',
            'email' => 'required|email',
            'blood_group' => 'required|string',
            'institution' => 'required|string',
            'course' => 'required|string',
            'department' => 'nullable|string',
            'year_of_study' => 'required|string',
            'permanent_address' => 'required|string',
            'current_address' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
            'status' => 'required|string',
            'photoFile' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/members', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $member->update($validated);
        AuditLog::log('ADMIN_UPDATE_MEMBER', "Member ID: {$member->id}");

        return redirect()->route('admin.members.show', $member->id)->with('success', 'Member record updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $status = $request->input('status');

        $member->status = $status;
        if ($status === 'Approved' && !$member->approval_date) {
            $member->approval_date = now()->toDateString();
            try {
                \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\MemberApprovedMail($member));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info("Mail send skipped: " . $e->getMessage());
            }
        }
        $member->save();

        AuditLog::log('ADMIN_STATUS_MEMBER', "Member ID: {$member->id}, Status: {$status}");

        return back()->with('success', "Member {$member->id} status updated to {$status}.");
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        AuditLog::log('ADMIN_DELETE_MEMBER', "Member ID: {$id}");
        return back()->with('success', "Member {$id} deleted successfully.");
    }

    public function exportCsv()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Full Name', 'Gender', 'DOB', 'Phone', 'Email', 'Blood Group', 'Institution', 'Course', 'Department', 'Year', 'Roll No', 'Permanent Address', 'Current Address', 'Emergency Contact', 'Emergency Phone', 'Status', 'Applied Date']);

            Member::chunk(100, function ($members) use ($handle) {
                foreach ($members as $m) {
                    fputcsv($handle, [
                        $m->id,
                        $m->full_name,
                        $m->gender,
                        $m->dob ? $m->dob->format('Y-m-d') : '',
                        $m->phone,
                        $m->email,
                        $m->blood_group,
                        $m->institution,
                        $m->course,
                        $m->department,
                        $m->year_of_study,
                        $m->roll_no,
                        $m->permanent_address,
                        $m->current_address,
                        $m->emergency_contact,
                        $m->emergency_phone,
                        $m->status,
                        $m->applied_date ? $m->applied_date->format('Y-m-d') : '',
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="kso_members_export.csv"');

        return $response;
    }

    public function fees()
    {
        $members = Member::where('status', 'Approved')->paginate(15);
        return view('admin.members.fees', compact('members'));
    }
}
