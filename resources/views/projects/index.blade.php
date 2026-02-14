@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="bi bi-folder"></i> Project Management
    </h1>
    <p class="text-muted mb-0">Organize your tasks by grouping them into projects</p>
</div>

<div class="row">
    <!-- Add Project Card -->
    <div class="col-lg-5 mb-4">
        <div class="card form-card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Create New Project
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST" id="projectForm">
                    @csrf
                    <div class="mb-3 form-group-enhanced">
                        <label for="name" class="form-label">
                            <i class="bi bi-tag text-primary"></i> Project Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-enhanced @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="e.g., Website Development"
                            required
                            maxlength="255"
                        >
                        <div class="form-helper">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Choose a descriptive name
                            </small>
                            <small class="char-counter-project text-muted">
                                <span id="charCountProject">0</span>/255
                            </small>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-enhanced w-100" id="submitProjectBtn">
                        <i class="bi bi-check-circle"></i> Create Project
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-3">
            <div class="card-body text-center">
                <div class="stat-item">
                    <div class="stat-number">{{ $projects->count() }}</div>
                    <div class="stat-label">Total Projects</div>
                </div>
                <div class="stat-item mt-3">
                    <div class="stat-number">{{ $projects->sum('tasks_count') }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul"></i> All Projects</span>
                <span class="badge bg-secondary">{{ $projects->count() }} Total</span>
            </div>
            <div class="card-body">
                @forelse($projects as $project)
                    <div class="project-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="project-name mb-1">
                                    <i class="bi bi-folder-fill text-primary"></i>
                                    {{ $project->name }}
                                </div>
                                <div class="project-meta">
                                    <span class="badge bg-info text-white">
                                        <i class="bi bi-list-task"></i> {{ $project->tasks_count }} task(s)
                                    </span>
                                    <span class="text-muted ms-2">
                                        <i class="bi bi-calendar3"></i> {{ $project->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteProject({{ $project->id }}, '{{ addslashes($project->name) }}', {{ $project->tasks_count }})" data-bs-toggle="tooltip" title="Delete this project">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                            <form id="deleteProjectForm{{ $project->id }}" action="{{ route('projects.destroy', $project) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-folder-x"></i>
                        <p>No projects yet. Create your first project to get started!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Confirm Delete Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to delete this project?</p>
                <div class="task-preview">
                    <strong id="projectNamePreview"></strong>
                </div>
                <div class="alert alert-danger mt-3 mb-0" id="projectTasksWarning" style="display: none;">
                    <i class="bi bi-exclamation-octagon"></i> <strong>Warning:</strong> This project contains <span id="projectTasksCount"></span> task(s). All tasks will also be deleted!
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProjectBtn">
                    <i class="bi bi-trash"></i> Yes, Delete Project
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

    // Character counter for project name
    const nameInput = document.getElementById('name');
    const charCountProject = document.getElementById('charCountProject');
    
    function updateCharCount() {
        const count = nameInput.value.length;
        charCountProject.textContent = count;
        
        if (count > 240) {
            charCountProject.parentElement.classList.add('text-warning');
        } else {
            charCountProject.parentElement.classList.remove('text-warning');
        }
    }
    
    nameInput.addEventListener('input', updateCharCount);
    updateCharCount();

    // Form submission with loading state
    const projectForm = document.getElementById('projectForm');
    const submitProjectBtn = document.getElementById('submitProjectBtn');
    
    projectForm.addEventListener('submit', function(e) {
        submitProjectBtn.disabled = true;
        submitProjectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
    });

    // Auto-focus on project name input
    nameInput.focus();

    // Delete confirmation
    let deleteProjectId = null;
    const deleteProjectModal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
    
    function confirmDeleteProject(projectId, projectName, tasksCount) {
        deleteProjectId = projectId;
        document.getElementById('projectNamePreview').textContent = projectName;
        
        const warningDiv = document.getElementById('projectTasksWarning');
        const tasksCountSpan = document.getElementById('projectTasksCount');
        
        if (tasksCount > 0) {
            tasksCountSpan.textContent = tasksCount;
            warningDiv.style.display = 'block';
        } else {
            warningDiv.style.display = 'none';
        }
        
        deleteProjectModal.show();
    }
    
    document.getElementById('confirmDeleteProjectBtn').addEventListener('click', function() {
        if (deleteProjectId) {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
            document.getElementById('deleteProjectForm' + deleteProjectId).submit();
        }
    });
</script>
@endpush
