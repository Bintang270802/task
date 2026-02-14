<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

/**
 * ProjectController
 * 
 * Handles CRUD operations for projects including:
 * - Listing all projects with task counts
 * - Creating new projects
 * - Deleting projects (cascade deletes tasks)
 */
class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Store a newly created project in storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateProject($request);
        
        Project::create($validated);
        
        return redirect()
            ->route('projects.index')
            ->with('success', 'Project has been created successfully!');
    }

    /**
     * Remove the specified project from storage
     * 
     * Note: This will cascade delete all tasks in the project
     * 
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project)
    {
        $projectName = $project->name;
        $taskCount = $project->tasks_count ?? $project->tasks()->count();
        
        $project->delete();
        
        $message = $taskCount > 0 
            ? "Project '{$projectName}' and {$taskCount} task(s) have been deleted!"
            : "Project '{$projectName}' has been deleted!";
        
        return redirect()
            ->route('projects.index')
            ->with('success', $message);
    }
    
    /**
     * Validate project input data
     * 
     * @param Request $request
     * @return array
     */
    private function validateProject(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255|min:3|unique:projects,name'
        ]);
    }
}
