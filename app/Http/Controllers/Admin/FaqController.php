<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

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
