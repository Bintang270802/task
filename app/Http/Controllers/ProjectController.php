<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('tasks')->get();
        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        Project::create($validated);
        
        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
    }
}
