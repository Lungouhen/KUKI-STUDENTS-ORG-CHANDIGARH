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
        $terms = Term::orderBy('start_date', 'desc')->get();

        return view('admin.projects.index', compact('projects', 'terms'));
    }

    /**
     * Projects are created through a modal on the index page, so there is no
     * standalone create screen.
     */
    public function create()
    {
        return redirect()->route('admin.projects.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'term_id' => 'required|exists:terms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        $validated['project_code'] = Project::generateProjectCode();

        $project = Project::create($validated);
        AuditLog::log('CREATE_PROJECT', "Project: {$project->title} ({$project->project_code})");

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $terms = Term::orderBy('start_date', 'desc')->get();

        return view('admin.projects.edit', compact('project', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'term_id' => 'required|exists:terms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        $project->update($validated);
        AuditLog::log('UPDATE_PROJECT', "Project ID: {$id}");

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $title = $project->title;
        $project->delete();

        AuditLog::log('DELETE_PROJECT', "Project: {$title}");

        return back()->with('success', 'Project removed.');
    }
}
