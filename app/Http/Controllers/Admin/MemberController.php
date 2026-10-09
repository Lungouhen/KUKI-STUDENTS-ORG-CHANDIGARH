<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Member;
use App\Models\MemberCustomField;
use App\Models\MemberCustomFieldValue;
use App\Models\MemberFeePayment;
use App\Models\FinancialAccount;
use App\Models\Transaction;
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
        $customFields = MemberCustomField::active()->get();

        return view('admin.members.create', compact('customFields'));
    }

    public function store(Request $request)
    {
        $customFieldRules = $this->customFieldValidationRules();

        $validated = $request->validate(array_merge([
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
            'status' => 'required|in:Pending,Approved,Rejected',
            'photoFile' => 'nullable|image|max:5120',
        ], $customFieldRules));

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

        $this->saveCustomFieldValues($member, $request);

        AuditLog::log('ADMIN_CREATE_MEMBER', "Member ID: {$member->id}, Name: {$member->full_name}");

        return redirect()->route('admin.members.index')->with('success', "Member {$member->id} registered successfully!");
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        $documents = $member->documents()->latest()->limit(10)->get();
        return view('admin.members.show', compact('member', 'documents'));
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $customFields = MemberCustomField::active()->get();

        return view('admin.members.edit', compact('member', 'customFields'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $customFieldRules = $this->customFieldValidationRules();

        $validated = $request->validate(array_merge([
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
            'status' => 'required|in:Pending,Approved,Rejected',
            'photoFile' => 'nullable|image|max:5120',
        ], $customFieldRules));

        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/members', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $member->update($validated);
        $this->saveCustomFieldValues($member, $request);
        AuditLog::log('ADMIN_UPDATE_MEMBER', "Member ID: {$member->id}");

        return redirect()->route('admin.members.show', $member->id)->with('success', 'Member record updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);
        $status = $validated['status'];

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

        if ($member->feePayments()->exists() || $member->electionVotes()->exists() || $member->candidacies()->exists() || $member->documents()->exists()) {
            return back()->with('error', 'This member has financial, election, or official document records and cannot be deleted. Deactivate the member instead.');
        }

        $member->delete();
        AuditLog::log('ADMIN_DELETE_MEMBER', "Member ID: {$id}");
        return back()->with('success', "Member {$id} deleted successfully.");
    }

    public function exportCsv()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            $header = ['ID', 'Full Name', 'Gender', 'DOB', 'Phone', 'Email', 'Blood Group', 'Institution', 'Course', 'Department', 'Year', 'Roll No', 'Permanent Address', 'Current Address', 'Emergency Contact', 'Emergency Phone', 'Status', 'Applied Date'];
            $customFields = MemberCustomField::active()->get();

            foreach ($customFields as $field) {
                $header[] = $field->label;
            }

            fputcsv($handle, $header);

            Member::chunk(100, function ($members) use ($handle, $customFields) {
                foreach ($members as $m) {
                    $row = [
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
                    ];

                    foreach ($customFields as $field) {
                        $value = $m->customFieldValues()->where('field_id', $field->id)->value('value');
                        $row[] = $value ?? '';
                    }

                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="kso_members_export.csv"');

        return $response;
    }

    protected function customFieldValidationRules(): array
    {
        $rules = [];

        foreach (MemberCustomField::active()->get() as $field) {
            $key = 'custom_fields.' . $field->slug;
            $rule = [];

            if ($field->field_type === 'checkbox') {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'boolean';
            } elseif ($field->field_type === 'number') {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'numeric';
            } elseif ($field->field_type === 'date') {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'date';
            } elseif ($field->field_type === 'textarea') {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'string';
                $rule[] = 'max:2000';
            } elseif ($field->field_type === 'select' || $field->field_type === 'radio') {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'string';
                $rule[] = 'max:255';
                if ($field->optionList() !== []) {
                    $rule[] = 'in:' . implode(',', $field->optionList());
                }
            } else {
                $rule[] = 'sometimes';
                $rule[] = 'nullable';
                $rule[] = 'string';
                $rule[] = 'max:255';
            }

            if ($field->is_required) {
                $rule = array_values(array_filter($rule, fn ($item) => $item !== 'nullable'));
                $rule[] = 'required';
            }

            $rules[$key] = $rule;
        }

        return $rules;
    }

    protected function saveCustomFieldValues(Member $member, Request $request): void
    {
        foreach (MemberCustomField::active()->get() as $field) {
            $key = 'custom_fields.' . $field->slug;
            $value = $request->input($key);

            if ($value === null || $value === '') {
                $member->customFieldValues()->where('field_id', $field->id)->delete();
                continue;
            }

            $fieldValue = $member->customFieldValues()->firstOrNew(['field_id' => $field->id]);
            $fieldValue->value = is_array($value) ? json_encode($value) : (string) $value;
            $fieldValue->save();
        }
    }

    public function fees()
    {
        $currentPeriod = MemberFeePayment::periodFor(now());
        $members = Member::where('status', 'Approved')
            ->with(['feePayments' => fn ($q) => $q->orderByDesc('paid_on')])
            ->paginate(15);
        $accounts = FinancialAccount::where('is_active', true)->orderBy('account_name')->get();

        return view('admin.members.fees', compact('members', 'currentPeriod', 'accounts'));
    }

    public function recordFeePayment(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'period' => ['required', 'string', 'max:10', 'regex:/^\d{4}-\d{2}$/'],
            'amount' => 'required|numeric|decimal:0,2|min:0.01|max:9999999999.99',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'paid_on' => 'required|date',
        ]);

        if ($member->feePayments()->where('period', $validated['period'])->exists()) {
            throw ValidationException::withMessages([
                'period' => "A fee payment for {$validated['period']} is already recorded for this member.",
            ]);
        }

        $voucherNo = 'VOUCH-' . date('Y') . '-' . strtoupper(Str::random(6));

        try {
            DB::transaction(function () use ($validated, $voucherNo, $member) {
                $account = FinancialAccount::whereKey($validated['financial_account_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $account->is_active) {
                    throw ValidationException::withMessages([
                        'financial_account_id' => 'Fee payments can only be posted to active accounts.',
                    ]);
                }

                Transaction::create([
                    'voucher_no' => $voucherNo,
                    'financial_account_id' => $account->id,
                    'type' => 'Income',
                    'category' => 'Membership Fee',
                    'amount' => $validated['amount'],
                    'transaction_date' => $validated['paid_on'],
                    'payment_method' => $validated['payment_method'],
                    'reference_no' => $validated['reference_no'] ?? null,
                    'payer_payee_name' => "{$member->full_name} ({$member->id})",
                    'narration' => "Membership fee {$validated['period']} — {$member->full_name} ({$member->id})",
                    'created_by' => auth()->id(),
                ]);

                $account->increment('current_balance', $validated['amount']);

                MemberFeePayment::create([
                    'member_id' => $member->id,
                    'period' => $validated['period'],
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'reference_no' => $validated['reference_no'] ?? null,
                    'voucher_no' => $voucherNo,
                    'paid_on' => $validated['paid_on'],
                    'recorded_by' => auth()->id(),
                ]);

                AuditLog::log('RECORD_FEE_PAYMENT', "Member: {$member->id}, Period: {$validated['period']}, Amount: ₹{$validated['amount']}, Voucher: {$voucherNo}");
            });
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            throw ValidationException::withMessages([
                'period' => "A fee payment for {$validated['period']} is already recorded for this member.",
            ]);
        }

        return back()->with('success', "Fee payment for {$member->full_name} ({$validated['period']}) recorded — voucher {$voucherNo}.");
    }
}
