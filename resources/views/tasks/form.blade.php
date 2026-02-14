@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">
                <i class="bi bi-{{ isset($task) ? 'pencil-square' : 'plus-circle' }}"></i>
                {{ isset($task) ? 'Edit Task' : 'Create New Task' }}
            </h1>
        </div>

        <!-- Main Form Card -->
        <div class="card">
            <div class="card-header">
                Task Information
            </div>
            <div class="card-body">
                <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" method="POST">
                    @csrf
                    @if(isset($task))
                        @method('PUT')
                    @endif

                    <!-- Task Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Task Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $task->name ?? '') }}"
                            placeholder="Enter task name"
                            required
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Project Selection -->
                    <div class="mb-4">
                        <label for="project_id" class="form-label">
                            Project (Optional)
                        </label>
                        <select 
                            class="form-select @error('project_id') is-invalid @enderror" 
                            id="project_id" 
                            name="project_id"
                        >
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $project)
                                <option 
                                    value="{{ $project->id }}" 
                                    {{ old('project_id', $task->project_id ?? '') == $project->id ? 'selected' : '' }}
                                >
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            Choose a project to organize your tasks
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            {{ isset($task) ? 'Update Task' : 'Create Task' }}
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box mt-4">
            <h6><i class="bi bi-info-circle"></i> Information</h6>
            <ul>
                <li>New tasks will be added to the bottom of your task list</li>
                <li>You can reorder tasks using drag and drop on the main page</li>
                <li>Task priority is automatically updated based on the order</li>
                <li>Assigning a project helps organize related tasks together</li>
            </ul>
        </div>
    </div>
</div>
@endsection
