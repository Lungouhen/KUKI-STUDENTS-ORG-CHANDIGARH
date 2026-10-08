<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $testimonials = Testimonial::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('author_name', 'like', '%'.$search.'%')
                        ->orWhere('author_title', 'like', '%'.$search.'%')
                        ->orWhere('college_name', 'like', '%'.$search.'%')
                        ->orWhere('quote', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', compact('testimonials', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_title' => 'required|string|max:255',
            'college_name' => 'required|string|max:255',
            'quote' => 'required|string|max:10000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create($validated);
        return back()->with('success', 'Testimonial added.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_title' => 'required|string|max:255',
            'college_name' => 'required|string|max:255',
            'quote' => 'required|string|max:10000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimonial deleted.');
    }
}
