<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ProjectController
 * 
 * Handles CRUD operations for projects with enhanced security
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
        try {
            $projects = Project::withCount('tasks')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('projects.index', compact('projects'));
            
        } catch (\Exception $e) {
            Log::error('Error loading projects: ' . $e->getMessage());
            
            return view('projects.index', ['projects' => collect()])
                ->with('error', 'An error occurred while loading projects.');
        }
    }

    /**
     * Store a newly created project in storage
     * 
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validated();
            
            $project = Project::create($validated);
            
            DB::commit();
            
            Log::info('Project created', ['project_id' => $project->id, 'name' => $project->name]);
            
            return redirect()
                ->route('projects.index')
                ->with('success', 'Project has been created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating project: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create project. Please try again.');
        }
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
        try {
            DB::beginTransaction();
            
            $projectName = $project->name;
            $taskCount = $project->tasks()->count();
            
            $project->delete();
            
            DB::commit();
            
            Log::info('Project deleted', [
                'project_name' => $projectName,
                'tasks_deleted' => $taskCount
            ]);
            
            $message = $taskCount > 0 
                ? "Project '{$projectName}' and {$taskCount} task(s) have been deleted!"
                : "Project '{$projectName}' has been deleted!";
            
            return redirect()
                ->route('projects.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting project: ' . $e->getMessage());
            
            return redirect()
                ->route('projects.index')
                ->with('error', 'Failed to delete project. Please try again.');
        }
    }
}
