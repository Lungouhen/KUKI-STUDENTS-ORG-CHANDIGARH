<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralContent;
use App\Models\AuditLog;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'slider');
        $contents = GeneralContent::where('type', $type)->orderBy('display_order')->paginate(15);
        
        $titles = [
            'slider' => 'Homepage Sliders',
            'certificate' => 'Official Certificates',
            'achievement' => 'Achievements & Awards',
            'policy' => 'Organization Policies',
            'notice' => 'Official Notices',
            'campaign' => 'NGO Campaigns',
            'career' => 'Careers & Opportunities'
        ];
        
        $title = $titles[$type] ?? 'Content Management';

        return view('admin.content.index', compact('contents', 'type', 'title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'link' => 'nullable|string',
            'imageFile' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('imageFile')) {
            $path = $request->file('imageFile')->store('uploads/content', 'public');
            $imagePath = '/storage/' . $path;
        }

        GeneralContent::create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'link' => $validated['link'],
            'image' => $imagePath,
            'display_order' => GeneralContent::where('type', $validated['type'])->count() + 1,
        ]);

        AuditLog::log('CREATE_CONTENT', "Type: {$validated['type']}, Title: {$validated['title']}");

        return back()->with('success', 'Content added successfully.');
    }

    public function destroy($id)
    {
        $content = GeneralContent::findOrFail($id);
        AuditLog::log('DELETE_CONTENT', "Type: {$content->type}, Title: {$content->title}");
        $content->delete();
        return back()->with('success', 'Item deleted.');
    }
}
