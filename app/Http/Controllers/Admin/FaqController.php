<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\MedicalReliefClaim;
use App\Models\AuditLog;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'required|string',
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'category' => $validated['category'],
            'sort_order' => Faq::count() + 1,
            'is_published' => true,
        ]);

        return back()->with('success', 'FAQ added.');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return back()->with('success', 'FAQ deleted.');
    }
}

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string',
            'author_title' => 'required|string',
            'college_name' => 'required|string',
            'quote' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create($validated);
        return back()->with('success', 'Testimonial added.');
    }

    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimonial deleted.');
    }
}

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

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.audit.index', compact('logs'));
    }
}
