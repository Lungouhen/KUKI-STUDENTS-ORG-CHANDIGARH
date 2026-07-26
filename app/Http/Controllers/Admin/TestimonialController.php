<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

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
