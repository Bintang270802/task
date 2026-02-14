<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * TaskController
 * 
 * Handles CRUD operations for tasks including:
 * - Listing tasks with optional project filter
 * - Creating new tasks
 * - Updating existing tasks
 * - Deleting tasks
 * - Reordering tasks via drag & drop
 */
class TaskController extends Controller
{
    /**
     * Display a listing of tasks
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProjectId = $request->get('project_id');
        
        $tasks = Task::with('project')
            ->when($selectedProjectId, function ($query, $projectId) {
                return $query->where('project_id', $projectId);
            })
            ->orderBy('priority')
            ->get();
        
        return view('tasks.index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'selectedProject' => $selectedProjectId
        ]);
    }

    /**
     * Show the form for creating a new task
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $projects = Project::all();
        
        return view('tasks.form', compact('projects'));
    }

    /**
     * Store a newly created task in storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateTask($request);
        
        // Set priority to be last in the list
        $validated['priority'] = $this->getNextPriority();
        
        Task::create($validated);
        
        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task has been created successfully!');
    }

    /**
     * Show the form for editing the specified task
     * 
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        
        return view('tasks.form', [
            'task' => $task,
            'projects' => $projects
        ]);
    }

    /**
     * Update the specified task in storage
     * 
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $validated = $this->validateTask($request);
        
        $task->update($validated);
        
        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task has been updated successfully!');
    }

    /**
     * Remove the specified task from storage
     * 
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $taskName = $task->name;
        
        $task->delete();
        
        return redirect()
            ->route('tasks.index')
            ->with('success', "Task '{$taskName}' has been deleted successfully!");
    }
    
    /**
     * Update task priorities after drag & drop reorder
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $taskIds = $request->input('tasks', []);
        
        if (empty($taskIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No tasks provided'
            ], 400);
        }
        
        DB::transaction(function () use ($taskIds) {
            foreach ($taskIds as $index => $taskId) {
                Task::where('id', $taskId)
                    ->update(['priority' => $index + 1]);
            }
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Task order updated successfully'
        ]);
    }
    
    /**
     * Validate task input data
     * 
     * @param Request $request
     * @return array
     */
    private function validateTask(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255|min:3',
            'project_id' => 'nullable|exists:projects,id'
        ]);
    }
    
    /**
     * Get the next available priority number
     * 
     * @return int
     */
    private function getNextPriority(): int
    {
        return (Task::max('priority') ?? 0) + 1;
    }
}
