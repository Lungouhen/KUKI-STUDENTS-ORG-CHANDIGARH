<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalReliefClaim;
use App\Models\AuditLog;

class MedicalReliefController extends Controller
{
    public function index()
    {
        $claims = MedicalReliefClaim::with('member')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.medical.index', compact('claims'));
    }

    public function updateStatus(Request $request, $id)
    {
        $claim = MedicalReliefClaim::findOrFail($id);
        $claim->status = $request->input('status');
        $claim->amount_approved = $request->input('amount_approved', $claim->amount_approved);
        $claim->save();

        AuditLog::log('MEDICAL_CLAIM_STATUS', "Claim #{$claim->id} status: {$claim->status}");

        return back()->with('success', 'Medical relief claim status updated.');
    }
}
