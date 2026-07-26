<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Term;
use App\Models\AuditLog;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('term')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $terms = Term::all();
        return view('admin.projects.create', compact('terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'term_id' => 'required|exists:terms,id',
            'title' => 'required|string',
            'budget' => 'required|numeric',
            'status' => 'required|string',
        ]);

        Project::create([
            'id' => \App\Models\Project::generateProjectId(),
            'term_id' => $validated['term_id'],
            'title' => $validated['title'],
            'budget' => $validated['budget'],
            'status' => $validated['status'],
        ]);
        AuditLog::log('CREATE_PROJECT', "Project: {$validated['title']}");

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }
}
