<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->get('project_id');
        
        $tasks = Task::with('project')
            ->when($selectedProject, function($query) use ($selectedProject) {
                return $query->where('project_id', $selectedProject);
            })
            ->orderBy('priority')
            ->get();
        
        return view('tasks.index', compact('tasks', 'projects', 'selectedProject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        return view('tasks.form', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id'
        ]);
        
        // Get max priority and add 1
        $maxPriority = Task::max('priority') ?? 0;
        $validated['priority'] = $maxPriority + 1;
        
        Task::create($validated);
        
        return redirect()->route('tasks.index')->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.form', compact('task', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id'
        ]);
        
        $task->update($validated);
        
        return redirect()->route('tasks.index')->with('success', 'Task berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus!');
    }
    
    /**
     * Update task priorities after reorder
     */
    public function reorder(Request $request)
    {
        $tasks = $request->input('tasks');
        
        foreach ($tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }
        
        return response()->json(['success' => true]);
    }
}
