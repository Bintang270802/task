<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * TaskController
 * 
 * Handles CRUD operations for tasks
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
        try {
            $projects = Project::all();
            $selectedProjectId = $request->get('project_id');
            
            // Validate project_id if provided
            if ($selectedProjectId && !is_numeric($selectedProjectId)) {
                return redirect()->route('tasks.index')
                    ->with('error', 'Invalid project filter.');
            }
            
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
        } catch (\Exception $e) {
            Log::error('Error loading tasks: ' . $e->getMessage());
            return redirect()->route('tasks.index')
                ->with('error', 'An error occurred while loading tasks.');
        }
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
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validated();
            
            // Set priority to be last in the list
            $validated['priority'] = $this->getNextPriority();
            
            $task = Task::create($validated);
            
            DB::commit();
            
            Log::info('Task created', ['task_id' => $task->id, 'name' => $task->name]);
            
            return redirect()
                ->route('tasks.index')
                ->with('success', 'Task has been created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating task: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create task. Please try again.');
        }
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
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validated();
            
            $task->update($validated);
            
            DB::commit();
            
            Log::info('Task updated', ['task_id' => $task->id, 'name' => $task->name]);
            
            return redirect()
                ->route('tasks.index')
                ->with('success', 'Task has been updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating task: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update task. Please try again.');
        }
    }

    /**
     * Remove the specified task from storage
     * 
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        try {
            $taskName = $task->name;
            $taskId = $task->id;
            
            $task->delete();
            
            Log::info('Task deleted', ['task_id' => $taskId, 'name' => $taskName]);
            
            return redirect()
                ->route('tasks.index')
                ->with('success', "Task '{$taskName}' has been deleted successfully!");
                
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            
            return redirect()
                ->route('tasks.index')
                ->with('error', 'Failed to delete task. Please try again.');
        }
    }
    
    /**
     * Update task priorities after drag & drop reorder
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'tasks' => 'required|array|min:1',
                'tasks.*' => 'required|integer|exists:tasks,id'
            ]);
            
            $taskIds = $validated['tasks'];
            
            // Check for duplicates
            if (count($taskIds) !== count(array_unique($taskIds))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate task IDs detected'
                ], 400);
            }
            
            DB::transaction(function () use ($taskIds) {
                foreach ($taskIds as $index => $taskId) {
                    Task::where('id', $taskId)
                        ->update(['priority' => $index + 1]);
                }
            });
            
            Log::info('Tasks reordered', ['count' => count($taskIds)]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task order updated successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid task data',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error reordering tasks: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task order'
            ], 500);
        }
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
