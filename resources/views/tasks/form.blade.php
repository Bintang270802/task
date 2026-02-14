@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}"><i class="bi bi-house-door"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active">{{ isset($task) ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">
                <i class="bi bi-{{ isset($task) ? 'pencil-square' : 'plus-circle' }}"></i>
                {{ isset($task) ? 'Edit Task' : 'Create New Task' }}
            </h1>
        </div>

        <!-- Multi-Step Progress (Only for Create) -->
        @if(!isset($task))
        <div class="wizard-steps mb-4">
            <div class="wizard-step active" data-step="1">
                <div class="wizard-step-circle">
                    <i class="bi bi-pencil-fill"></i>
                    <span class="wizard-step-number">1</span>
                </div>
                <div class="wizard-step-label">Task Details</div>
            </div>
            <div class="wizard-step-line"></div>
            <div class="wizard-step" data-step="2">
                <div class="wizard-step-circle">
                    <i class="bi bi-eye-fill"></i>
                    <span class="wizard-step-number">2</span>
                </div>
                <div class="wizard-step-label">Review & Save</div>
            </div>
        </div>
        @endif

        <!-- Step 1: Task Details Form -->
        <div class="card form-card" id="step1" style="display: block;">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i> Task Information
                <span class="required-badge">* Required fields</span>
            </div>
            <div class="card-body">
                <form id="taskDetailsForm">
                    <!-- Task Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="bi bi-card-text text-primary"></i> Task Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $task->name ?? '') }}"
                            placeholder="e.g., Design homepage mockup"
                            required
                            autofocus
                            maxlength="255"
                        >
                        <div class="form-helper d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Be specific and descriptive
                            </small>
                            <small class="char-counter text-muted">
                                <span id="charCount">0</span>/255
                            </small>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Project Selection -->
                    <div class="mb-4">
                        <label for="project_id" class="form-label">
                            <i class="bi bi-folder text-success"></i> Project (Optional)
                        </label>
                        <select 
                            class="form-select @error('project_id') is-invalid @enderror" 
                            id="project_id" 
                            name="project_id"
                        >
                            <option value="">-- No Project --</option>
                            @foreach($projects as $project)
                                <option 
                                    value="{{ $project->id }}" 
                                    data-name="{{ $project->name }}"
                                    {{ old('project_id', $task->project_id ?? '') == $project->id ? 'selected' : '' }}
                                >
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted d-block mt-2">
                            <i class="bi bi-lightbulb"></i> Group related tasks by assigning them to a project
                        </small>
                        @error('project_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-4 border-top">
                        @if(isset($task))
                            <button type="button" class="btn btn-primary" onclick="submitDirectly()">
                                <i class="bi bi-check-circle"></i> Update Task
                            </button>
                        @else
                            <button type="button" class="btn btn-primary" onclick="goToReview()">
                                Next: Review <i class="bi bi-arrow-right"></i>
                            </button>
                        @endif
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Step 2: Review & Save (Only for Create) -->
        @if(!isset($task))
        <div class="card form-card" id="step2" style="display: none;">
            <div class="card-header">
                <i class="bi bi-eye"></i> Review Your Task
            </div>
            <div class="card-body">
                <div class="review-section">
                    <h5 class="mb-4">Please review the information before saving</h5>
                    
                    <!-- Review Content -->
                    <div class="review-item">
                        <div class="review-label">
                            <i class="bi bi-card-text text-primary"></i> Task Name
                        </div>
                        <div class="review-value" id="reviewName">-</div>
                    </div>

                    <div class="review-item">
                        <div class="review-label">
                            <i class="bi bi-folder text-success"></i> Project
                        </div>
                        <div class="review-value" id="reviewProject">
                            <span class="badge bg-secondary">No Project</span>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-label">
                            <i class="bi bi-star text-warning"></i> Priority
                        </div>
                        <div class="review-value">
                            <span class="badge bg-primary">Will be set automatically</span>
                        </div>
                    </div>

                    <!-- Confirmation -->
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Note:</strong> Your task will be added to the bottom of the list. You can reorder it later using drag & drop.
                    </div>
                </div>

                <!-- Action Buttons -->
                <form action="{{ route('tasks.store') }}" method="POST" id="finalSubmitForm">
                    @csrf
                    <input type="hidden" name="name" id="finalName">
                    <input type="hidden" name="project_id" id="finalProjectId">
                    
                    <div class="d-flex gap-2 pt-4 border-top">
                        <button type="button" class="btn btn-secondary" onclick="goBackToEdit()">
                            <i class="bi bi-arrow-left"></i> Back to Edit
                        </button>
                        <button type="submit" class="btn btn-success" id="finalSubmitBtn">
                            <i class="bi bi-check-circle"></i> Save Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- Direct Submit Form for Edit -->
        <form action="{{ route('tasks.update', $task) }}" method="POST" id="editForm" style="display: none;">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" id="editName">
            <input type="hidden" name="project_id" id="editProjectId">
        </form>
        @endif

        <!-- Help Cards -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="bi bi-lightbulb-fill"></i>
                    </div>
                    <h6>Quick Tips</h6>
                    <ul class="mb-0">
                        <li>Use clear, action-oriented task names</li>
                        <li>Group related tasks in projects</li>
                        <li>Reorder tasks by priority on the main page</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="bi bi-keyboard-fill"></i>
                    </div>
                    <h6>Keyboard Shortcuts</h6>
                    <ul class="mb-0">
                        <li><kbd>Esc</kbd> - Cancel and go back</li>
                        <li><kbd>Tab</kbd> - Navigate between fields</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter
        const nameInput = document.getElementById('name');
        const charCount = document.getElementById('charCount');
        
        if (nameInput && charCount) {
            function updateCharCount() {
                const count = nameInput.value.length;
                charCount.textContent = count;
                
                if (count > 240) {
                    charCount.parentElement.classList.add('text-warning');
                } else {
                    charCount.parentElement.classList.remove('text-warning');
                }
            }
            
            nameInput.addEventListener('input', updateCharCount);
            updateCharCount();
            
            // Form validation
            nameInput.addEventListener('blur', function() {
                if (this.value.trim().length > 0 && this.value.trim().length < 3) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }
    });

    // Go to Review Step
    function goToReview() {
        const name = document.getElementById('name');
        const projectSelect = document.getElementById('project_id');
        const reviewName = document.getElementById('reviewName');
        const reviewProject = document.getElementById('reviewProject');
        const finalName = document.getElementById('finalName');
        const finalProjectId = document.getElementById('finalProjectId');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        
        if (!name || !projectSelect || !reviewName || !reviewProject || !finalName || !finalProjectId || !step1 || !step2) {
            console.error('Required elements not found');
            return;
        }
        
        const nameValue = name.value.trim();
        
        if (!nameValue) {
            alert('Please enter a task name');
            name.focus();
            return;
        }

        if (nameValue.length < 3) {
            alert('Task name must be at least 3 characters');
            name.focus();
            return;
        }

        // Get form data
        const projectId = projectSelect.value;
        const projectName = projectSelect.options[projectSelect.selectedIndex].getAttribute('data-name');

        // Update review content
        reviewName.innerHTML = `<strong>${nameValue}</strong>`;
        
        if (projectId) {
            reviewProject.innerHTML = `
                <span class="project-badge">
                    <i class="bi bi-folder"></i> ${projectName}
                </span>
            `;
        } else {
            reviewProject.innerHTML = `
                <span class="badge bg-secondary">No Project</span>
            `;
        }

        // Set hidden form values
        finalName.value = nameValue;
        finalProjectId.value = projectId;

        // Update wizard steps
        const wizardStep1 = document.querySelector('[data-step="1"]');
        const wizardStep2 = document.querySelector('[data-step="2"]');
        
        if (wizardStep1 && wizardStep2) {
            wizardStep1.classList.remove('active');
            wizardStep1.classList.add('completed');
            wizardStep2.classList.add('active');
        }

        // Show/hide steps with animation
        step1.style.animation = 'slideOutLeft 0.3s ease';
        setTimeout(() => {
            step1.style.display = 'none';
            step2.style.display = 'block';
            step2.style.animation = 'slideInRight 0.3s ease';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }

    // Go back to edit
    function goBackToEdit() {
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const wizardStep1 = document.querySelector('[data-step="1"]');
        const wizardStep2 = document.querySelector('[data-step="2"]');
        
        if (!step1 || !step2) {
            console.error('Step elements not found');
            return;
        }
        
        // Update wizard steps
        if (wizardStep1 && wizardStep2) {
            wizardStep2.classList.remove('active');
            wizardStep1.classList.remove('completed');
            wizardStep1.classList.add('active');
        }

        // Show/hide steps with animation
        step2.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            step2.style.display = 'none';
            step1.style.display = 'block';
            step1.style.animation = 'slideInLeft 0.3s ease';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }

    // Submit directly for edit mode
    function submitDirectly() {
        const name = document.getElementById('name');
        const editName = document.getElementById('editName');
        const editProjectId = document.getElementById('editProjectId');
        const editForm = document.getElementById('editForm');
        const projectSelect = document.getElementById('project_id');
        
        if (!name || !editName || !editProjectId || !editForm || !projectSelect) {
            console.error('Required form elements not found');
            return;
        }
        
        const nameValue = name.value.trim();
        
        if (!nameValue || nameValue.length < 3) {
            alert('Please enter a valid task name (at least 3 characters)');
            return;
        }

        editName.value = nameValue;
        editProjectId.value = projectSelect.value;
        editForm.submit();
    }

    // Final submit with loading state
    const finalSubmitForm = document.getElementById('finalSubmitForm');
    if (finalSubmitForm) {
        finalSubmitForm.addEventListener('submit', function() {
            const btn = document.getElementById('finalSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc to cancel
        if (e.key === 'Escape') {
            const step2 = document.getElementById('step2');
            if (step2 && step2.style && step2.style.display === 'block') {
                goBackToEdit();
            } else {
                window.location.href = '{{ route("tasks.index") }}';
            }
        }
    });
</script>
@endpush
