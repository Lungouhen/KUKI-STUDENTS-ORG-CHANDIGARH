<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Member::query();

        if ($status) {
            $query->where('status', $status);
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

    public function updateStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $status = $request->input('status');

        $member->status = $status;
        if ($status === 'Approved' && !$member->approval_date) {
            $member->approval_date = now()->toDateString();
        }
        $member->save();

        return back()->with('success', "Member {$member->id} status updated to {$status}.");
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
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
}
