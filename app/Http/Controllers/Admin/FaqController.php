<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
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

        $faq = Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'category' => $validated['category'],
            'sort_order' => Faq::count() + 1,
            'is_published' => true,
        ]);

        AuditLog::log('CREATE_FAQ', "FAQ ID: {$faq->id}, question: {$faq->question}");

        return back()->with('success', 'FAQ added.');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'required|string',
        ]);

        $faq->update($validated);

        AuditLog::log('UPDATE_FAQ', "FAQ ID: {$faq->id}, question: {$faq->question}");

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        AuditLog::log('DELETE_FAQ', "FAQ ID: {$faq->id}, question: {$faq->question}");
        $faq->delete();

        return back()->with('success', 'FAQ deleted.');
    }
}
