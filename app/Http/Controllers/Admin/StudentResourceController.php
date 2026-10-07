<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentResource;
use App\Models\AuditLog;

class StudentResourceController extends Controller
{
    public function index()
    {
        $resources = StudentResource::orderByDesc('created_at')->paginate(15);
        return view('admin.resources.index', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', StudentResource::CATEGORIES),
            'resourceFile' => 'required|file|mimes:pdf,zip,doc,docx,ppt,pptx,xls,xlsx|max:20480',
        ]);

        $file = $request->file('resourceFile');
        $size = $file->getSize();
        $path = $file->store('uploads/resources', 'public');

        $resource = StudentResource::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'file_path' => $path,
            'file_size' => $size,
        ]);

        AuditLog::log('CREATE_STUDENT_RESOURCE', "Resource: {$resource->title} (ID: {$resource->id})");

        return back()->with('success', 'Resource uploaded to the student library.');
    }

    public function toggle($id)
    {
        $resource = StudentResource::findOrFail($id);
        $resource->update(['is_active' => ! $resource->is_active]);
        AuditLog::log('TOGGLE_STUDENT_RESOURCE', "Resource ID: {$id}, Active: " . ($resource->is_active ? 'yes' : 'no'));

        return back()->with('success', 'Resource visibility updated.');
    }

    public function destroy($id)
    {
        $resource = StudentResource::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        AuditLog::log('DELETE_STUDENT_RESOURCE', "Resource: {$resource->title} (ID: {$id})");

        return back()->with('success', 'Resource removed from the library.');
    }
}
