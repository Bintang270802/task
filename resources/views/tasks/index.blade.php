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

<!-- Drag & Drop Instruction Banner (shows on first visit) -->
@if($tasks->count() > 1)
<div class="drag-instruction-banner" id="dragInstructionBanner">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="instruction-icon">
                <i class="bi bi-hand-index"></i>
            </div>
            <div>
                <strong>Pro Tip:</strong> Hover over the <i class="bi bi-grip-vertical"></i> grip icon and drag tasks to reorder them. Your priority will be saved automatically!
            </div>
        </div>
        <button type="button" class="btn-close-instruction" onclick="closeDragInstruction()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

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
                <div class="drag-handle me-3" data-bs-toggle="tooltip" data-bs-placement="left" title="Drag to reorder">
                    <i class="bi bi-grip-vertical fs-4"></i>
                    <div class="drag-hint">
                        <i class="bi bi-arrows-move"></i>
                        <span>Drag to reorder</span>
                    </div>
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
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit this task">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteTask({{ $task->id }}, '{{ addslashes($task->name) }}')" data-bs-toggle="tooltip" title="Delete this task">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    <form id="deleteTaskForm{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to delete this task?</p>
                <div class="task-preview">
                    <strong id="taskNamePreview"></strong>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> <strong>Warning:</strong> This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTaskBtn">
                    <i class="bi bi-trash"></i> Yes, Delete Task
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Close drag instruction banner
    function closeDragInstruction() {
        const banner = document.getElementById('dragInstructionBanner');
        if (banner) {
            banner.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                banner.style.display = 'none';
                localStorage.setItem('dragInstructionClosed', 'true');
            }, 300);
        }
    }

    // Check if instruction was already closed
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('dragInstructionBanner');
        if (banner && localStorage.getItem('dragInstructionClosed') === 'true') {
            banner.style.display = 'none';
        }
    });

    // Delete confirmation
    let deleteTaskId = null;
    const deleteTaskModal = new bootstrap.Modal(document.getElementById('deleteTaskModal'));
    
    function confirmDeleteTask(taskId, taskName) {
        deleteTaskId = taskId;
        document.getElementById('taskNamePreview').textContent = taskName;
        deleteTaskModal.show();
    }
    
    document.getElementById('confirmDeleteTaskBtn').addEventListener('click', function() {
        if (deleteTaskId) {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
            document.getElementById('deleteTaskForm' + deleteTaskId).submit();
        }
    });

    // Drag and Drop functionality
    const taskList = document.getElementById('task-list');
    const taskCards = document.querySelectorAll('.task-card');
    let draggedElement = null;

    taskCards.forEach(card => {
        const dragHandle = card.querySelector('.drag-handle');
        
        // Show hint on hover
        dragHandle.addEventListener('mouseenter', function() {
            const hint = this.querySelector('.drag-hint');
            if (hint) {
                hint.style.opacity = '1';
                hint.style.transform = 'translateX(0)';
            }
        });

        dragHandle.addEventListener('mouseleave', function() {
            const hint = this.querySelector('.drag-hint');
            if (hint) {
                hint.style.opacity = '0';
                hint.style.transform = 'translateX(-10px)';
            }
        });

        card.addEventListener('dragstart', function(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            
            // Hide all hints during drag
            document.querySelectorAll('.drag-hint').forEach(hint => {
                hint.style.display = 'none';
            });
        });

        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            draggedElement = null;
            
            // Show hints again
            document.querySelectorAll('.drag-hint').forEach(hint => {
                hint.style.display = 'flex';
            });
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
        
        // Show saving indicator
        const savingIndicator = document.createElement('div');
        savingIndicator.className = 'saving-indicator';
        savingIndicator.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Saving order...';
        document.body.appendChild(savingIndicator);
        
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
                
                // Show success indicator
                savingIndicator.innerHTML = '<i class="bi bi-check-circle-fill"></i> Order saved!';
                savingIndicator.classList.add('success');
                
                setTimeout(() => {
                    savingIndicator.style.animation = 'slideUp 0.3s ease';
                    setTimeout(() => savingIndicator.remove(), 300);
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            savingIndicator.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Failed to save';
            savingIndicator.classList.add('error');
            setTimeout(() => savingIndicator.remove(), 2000);
        });
    });
</script>
@endpush
