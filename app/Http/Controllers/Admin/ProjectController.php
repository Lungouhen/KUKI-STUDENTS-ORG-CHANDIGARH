<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Project;
use App\Models\Term;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('term')->orderBy('created_at', 'desc')->paginate(15);
        $terms = Term::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $projectTemplates = config('project_templates', []);

        return view('admin.projects.index', compact('projects', 'terms', 'projectTemplates'));
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:Planned,Active,Completed',
        ]);

        Project::create([
            'term_id' => $validated['term_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'budget' => $validated['budget'],
            'status' => $validated['status'],
        ]);
        AuditLog::log('CREATE_PROJECT', "Project: {$validated['title']}");

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }
}
