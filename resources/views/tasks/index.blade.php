@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">
        <i class="bi bi-list-check"></i> Task List
    </h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Task
    </a>
</div>

<!-- Filter Card -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-funnel"></i> Filter Options
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Filter by Project</label>
                <select name="project_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                @if($selectedProject)
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Task List -->
<div id="task-list">
    @forelse($tasks as $task)
        <div class="task-card" data-id="{{ $task->id }}" draggable="true">
            <div class="d-flex align-items-start">
                <div class="drag-handle me-3">
                    <i class="bi bi-grip-vertical fs-4"></i>
                </div>
                
                <div class="flex-grow-1">
                    <h5 class="task-title">{{ $task->name }}</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                        <span class="badge-priority">
                            Priority {{ $task->priority }}
                        </span>
                        @if($task->project)
                            <span class="project-badge">
                                <i class="bi bi-folder"></i> {{ $task->project->name }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="ms-3 d-flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>No tasks found. Start by creating your first task!</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create First Task
            </a>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    // Drag and Drop functionality
    const taskList = document.getElementById('task-list');
    const taskCards = document.querySelectorAll('.task-card');
    let draggedElement = null;

    taskCards.forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            draggedElement = null;
        });

        card.addEventListener('dragover', function(e) {
            e.preventDefault();
            const afterElement = getDragAfterElement(taskList, e.clientY);
            if (afterElement == null) {
                taskList.appendChild(draggedElement);
            } else {
                taskList.insertBefore(draggedElement, afterElement);
            }
        });
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.task-card:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Save new order when drag ends
    taskList.addEventListener('dragend', function() {
        const taskIds = [...document.querySelectorAll('.task-card')].map(card => card.dataset.id);
        
        fetch('{{ route("tasks.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ tasks: taskIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update priority badges
                document.querySelectorAll('.task-card').forEach((card, index) => {
                    const badge = card.querySelector('.badge-priority');
                    badge.textContent = `Priority ${index + 1}`;
                });
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this task?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
